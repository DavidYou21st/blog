<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/18
 * Time: 8:38
 */

namespace App\Services\Admin;

use App\Validate\Admin\RoleValidate;
use App\Models\Admin\Role as RoleModel;
use App\Models\Admin\RolePrivilege;
use App\Http\Common\Enum\RoleEnum;
use App\Http\Common\Utils\ReturnData;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class RoleService extends BaseService implements ServiceInterface
{

    /**
     * 获取模型
     * @return  RoleModel
     */
    public static function getModel()
    {
        return new RoleModel();
    }

    /**
     * @param $data array 要保存至数据库的数据
     */
    public function add($data)
    {
        //验证器
        $validate = new RoleValidate();
        $validator = self::getValidate($data,'add',$validate);
        if ($validator->fails()){
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }

        try {
            self::getModel()->add($data);
        } catch (\Exception $ex) {
            LogService::addOperateLog($data,ReturnData::getCodeText(ReturnData::PARAMS_ERROR),$ex->getTraceAsString(),1);
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }

        LogService::addOperateLog($data,'操作成功',response()->json(ReturnData::create(ReturnData::SUCCESS)));
        return ReturnData::create(ReturnData::SUCCESS);
    }

    //修改
    public function edit($data, $where = [])
    {
        if(empty($data)){return ReturnData::create(ReturnData::SUCCESS);}

        //验证器
        $validate = new RoleValidate();
        $validator = self::getValidate($data,'edit',$validate);
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}

        try {
            $res = $this->getModel()->edit($data,$where);
        } catch (\Exception $ex) {
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
     * @return array
     */
    public function getList()
    {
        $model = self::getModel();
        $count = $model->getCount();
        $list = [];
        if($count > 0){
            $list = $model->getAll()->each(function($item){
                $item['is_cate_name'] = RoleEnum::getName('is_cate',$item['is_cate'],true);
                $item['status_name'] = RoleEnum::getName('status',$item['status']);
            });
        }
        return ['count'=>$count,'data'=>$list];
        //return ReturnData::success($list,$count);
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
                $v['name'] = $v['role_name'];
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
                $v['name'] = $v['role_name'];
                $v['value'] = $v['id'];
                $a = $this->_sortChildren($arr,$v['id'],$select_id,$lev+1);
                if(!empty($a)){
                    $v['children'] = $a;
                }

                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 获取所有下级
     * @param int $parent_id
     * @param int $select_id
     * @return array
     */
    public function getSortChildren($parent_id=0,$select_id=0)
    {
        $list = self::getModel()->getAll(['status'=>1])->toArray();
        //print_r($list);
        return $this->_sortChildren($list,$parent_id,$select_id);
    }


    /**
     * 批量保存角色权限数据
     * @param $data
     * @return array
     */
    public function saveRolePrivilege($data){
        $tmp = [];
        if(!empty($data['privilege_ids'])){
            foreach($data['privilege_ids'] as $key => $value){
                $tmp[] = [
                    'role_id'=>$data['role_id'],
                    'privilege_id'=>$value
                ];
            }

            $rolePrivilegeModel = new RolePrivilege();
            try {
                //先删除之前的
                $rolePrivilegeModel->delByWhere(['role_id'=>$data['role_id']]);
                $rolePrivilegeModel->add($tmp,1);
            } catch (\Exception $ex) {
                return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
            }
            return ReturnData::create(ReturnData::SUCCESS);
        }
    }
}
