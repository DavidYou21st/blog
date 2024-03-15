<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/18
 * Time: 8:38
 */

namespace App\Services\Admin;

use App\Validate\Admin\ManagerValidate;
use App\Models\Admin\Manager as ManagerModel;
use App\Models\Admin\Manager;
use App\Models\Admin\ManagerRole as ManagerRoleModel;
use App\Http\Common\Enum\BaseEnum;
use App\Http\Common\Enum\ManagerEnum;
use App\Http\Common\Utils\Random;
use App\Http\Common\Utils\ReturnData;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ManagerService extends BaseService implements ServiceInterface
{

    /**
     * 获取模型
     * @return ManagerModel
     */
    public static function getModel()
    {
        return new ManagerModel();
    }

    /**
     * 新增数据库的方法
     * @param $data array 要保存至数据库的数据
     *
     */
    public function add($data)
    {
        //验证器
        $validate = new ManagerValidate();
        $validator = self::getValidate($data,'add',$validate);
        if ($validator->fails()){
            return ReturnData::create(ReturnData::FAIL, null, $validator->errors()->first());
        }
        $model = self::getModel();
        //生成6位salt字符串
        $salt = Random::alnum();
        if(!empty($data['password'])){
            $data['salt'] = $salt;
            $data['entry_password'] = $data['password'];
            $data['password'] = md5(md5($data['password'].$salt));
        }

        try {
            ManagerModel::beginTrans();
            $model->load($data)->save();
            //组装角色
            if(!empty($data['role_ids'])){
                $roles = explode(',',$data['role_ids']);
                $roleTmp = [];
                foreach($roles as $key => $val){
                    $roleTmp[] = ['role_id'=>$val,'manager_id'=>$model->id];
                }
                $mangerRoleModel = new ManagerRoleModel;
                $mangerRoleModel->add($roleTmp,1);
            }
            ManagerModel::commitTrans();
        } catch (\Exception $ex) {
            ManagerModel::rollbackTrans();
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }

    /**
     * 修改方法
     * @param $data array 要修改的数组
     * @param array $where 修改条件
     * @return array
     */
    public function edit($data, $where = [])
    {
        if(empty($data)){return ReturnData::create(ReturnData::SUCCESS);}
        //验证器
        $validate = new ManagerValidate();
        $validator = self::getValidate($data,'edit',$validate);
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
        $model = $this->getModel()->findModel($data['manager_id']);
        //生成6位salt字符串
        $salt = Random::alnum();
        //如果回填了密码,则重新更新密码
        if(!empty($data['password'])){
            $data['salt'] = $salt;
            $data['entry_password'] = $data['password'];
            $data['password'] = md5(md5($data['password'].$salt));
        }
        try {
            //开启事务
            ManagerModel::beginTrans();
            //组装角色
            if(!empty($data['role_ids'])){
                $roles = explode(',',$data['role_ids']);
                $roleTmp = [];
                foreach($roles as $key => $val){
                    $roleTmp[] = ['role_id'=>$val,'manager_id'=>$model->id];
                }
                $mangerRoleModel = new ManagerRoleModel;
                //先删除之前的
                $mangerRoleModel->delByWhere(['manager_id'=>$model->id]);
                $mangerRoleModel->add($roleTmp,1);
            }
            $model->load($data)->save();
            ManagerModel::commitTrans();
        } catch (\Exception $ex) {
            ManagerModel::rollbackTrans();
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }


    /**
     * 删除一条数据
     * @param $id
     * @param array $where
     * @return array
     */
    public function del($id,$where = [])
    {
        $model = $this->findModel($id,$where);
        try {
            $model->delete();
        } catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }


    /**
     * 得到模型
     * @param $id
     * @param array $where
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function findModel($id,$where = [])
    {
        if (empty($id) && empty($where)) {
            throw new RouteNotFoundException();
        }
        return self::getModel()->findModel($id,$where);
    }

    /**
     * 获取列表的方法
     * @return array
     */
    public function getList($data=[])
    {
        $model = self::getModel();
        $where = '';
        if(!empty($data) && isset($data['searchParams'])){
            $search = json_decode($data['searchParams'],true);

            $where = function($query) use ($search){
                if(isset($search['full_name'])) {
                    $query->where('full_name', 'like', '%' . $search['full_name'] . '%');
                }
            };
        }

        $count = $model->getCount($where);
        $list = [];
        if($count > 0){
            $list = $model->getAll($where)->each(function($item){
                $item['status_name'] = BaseEnum::getBoolValue($item['status']);
            });
        }
        return ReturnData::success($list,$count);
    }

    /*
     * 递归排序获取所有子栏目
     * $param array $arr
     * $param $parent_id int
     * $param $lev int
     * $param $isClear boolean
     * */
    public function _sort($arr,$parent_id=0,$lev=1,$isClear=true)
    {
        static $tree = [];
        if($isClear==true){
            $tree = [];
        }

        foreach($arr as $k=>$v) {
            if ($v['parent_id'] == $parent_id) {
                $v['name'] = $v['privilege_name'];
                $v['lev'] = $lev;
                $tree[] = $v;
                $this->_sort($arr,$v['id'],$lev+1,false);
            }
        }
        return $tree;
    }

    /**
     * 获取一个id下所有子类
     * @param int $parent_id
     * @return array
     */
    public function getSortList($parent_id = 0)
    {
        $list = self::getModel()->getAll()->toArray();
        return self::_sort($list,$parent_id);
    }

    /*
     *递归获取上下级关系
     *@param mix
     *@return array
    * */
    public function _sortChildren($arr,$parent_id,$select_id,$lev=0)
    {
        //取出所有的权限并递归排序
        $tree = [];
        foreach($arr as $k=>$v) {
            if ($v['parent_id'] == $parent_id) {
                $v['lev'] = $lev;
                //$v['selected'] = ($v['id'] == $select_id?true:false);
                //$v['name'] = $v['privilege_name'];
                //$v['value'] = $v['id'];
                $v['children'] = $this->_sortChildren($arr,$v['id'],$select_id,$lev+1);

                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 获取所有下级
     * @param int $parent_id 顶级id
     * @param int $select_id 是否选中,前端返回使用
     * @return array
     */
    public function getSortChildren($parent_id=0,$select_id=0)
    {
        $list = self::getModel()->getAll()->toArray();
        //print_r($list);
        return $this->_sortChildren($list,$parent_id,$select_id);
    }

    /**
     * 登录方法
     * @param $data array 用户输入的登录信息
     * @return bool
     */
    public function doLogin($data)
    {
        $account = isset($data['account'])?$data['account']:'';
        $password = isset($data['password'])?$data['password']:'';

        try {
            //验证用户名与密码是否为空
            if(empty($account) || empty($password)){
                throw new \Exception('账号或密码不能为空');
            }
            //验证数据表里有没有此用户
            $user = self::getModel()->getOne(['account'=>$account]);

            if(empty($user)){
                throw new \Exception('用户不存在');
            }

            //判断用户启用状态
            if($user->status == ManagerEnum::STATUS_ZERO){
                throw new \Exception('账号已锁定');
            }

            //接下来验证密码
            if($user->password != md5(md5($password.$user->salt))){
                throw new \Exception('密码错误');
            }
            //验证通用,把账户信息存入session 或者 数据库, redis,这里选择session
            $request = request();

            //print_r($user->toArray());die();
            $request->session()->put('managerUser',$user->toArray());
            $request->session()->put('manager_id',$user->id);

            //更新用户登录ip,登录时间
            $editField = [
                'last_ip' => $request->ip(),
                'last_time'=>time()
            ];
            $user->load($editField)->save();
        } catch (\Exception $ex) {
            LogService::addLoginLog( $ex->getMessage(),1,$account);
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        LogService::addLoginLog( ReturnData::getCodeText(ReturnData::SUCCESS));
        return ReturnData::create(ReturnData::SUCCESS);

    }


    public function getLoginManager($manager_id = 0,$session=false){
        $manager = null;
        $request = request();

        if($session){
            $manager = $request->session()->get('managerUser');
            if(!empty($manager)){
                return $manager;
            }
        }
        //获取当前登录人员
        $id = !empty($manager_id)?$manager_id:$request->session()->get('manager_id');
        if(!empty($id)) {
            $managerModel = new Manager();
            $manager = $managerModel->findOne(['id' => $id]);
        }
        return $manager;
    }


    /**
     * 重置密码
     * @param $data
     * @param array $where
     * @return array
     */
    public function resetPassword($data,$where=[]){
        $model = $this->getModel();
        //$model = $this->getModel()->findModel($data['manager_id']);
        //生成6位salt字符串
        $salt = Random::alnum();
        //如果回填了密码,则重新更新密码
        $data['password'] = '123456';
        if(!empty($data['password'])){
            $data['salt'] = $salt;
            $data['entry_password'] = $data['password'];
            $data['password'] = md5(md5($data['password'].$salt));
        }
        $model->edit($data,$where);
        return ReturnData::create(ReturnData::SUCCESS);
    }

    public function updatePassword($data)
    {
        //验证器
        $validate = new ManagerValidate();
        $validator = self::getValidate($data,'updatePassword',$validate);
        if ($validator->fails()){
            return ReturnData::create(ReturnData::FAIL, null, $validator->errors()->first());
        }
        $manager = $this->getLoginManager();
        //生成6位salt字符串
        try{
            if(md5(md5($data['old_password'].$manager['salt'])) !== $manager['password']){
                throw new \Exception('旧密码输入不正确');
            }
            $salt = Random::alnum();
            if(!empty($data['password'])){
                $data['salt'] = $salt;
                $data['entry_password'] = $data['password'];
                $data['password'] = md5(md5($data['password'].$salt));
            }
            $manager->load($data)->save();
        }catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }


    public function manageSetting($data){
        //验证器
        $validate = new ManagerValidate();
        $validator = self::getValidate($data,'manageSetting',$validate);
        if ($validator->fails()){
            return ReturnData::create(ReturnData::FAIL, null, $validator->errors()->first());
        }
        $manager = $this->getLoginManager();
        try{
            $manager->load($data)->save();
        }catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }
}

