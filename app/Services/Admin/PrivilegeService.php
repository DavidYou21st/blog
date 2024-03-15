<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/18
 * Time: 8:38
 */

namespace App\Services\Admin;

use App\Validate\Admin\PrivilegeValidate;
use App\Models\Admin\Privilege as PrivilegeModel;
use App\Http\Common\Enum\PrivilegeEnum;
use App\Http\Common\Utils\Helper;
use App\Http\Common\Utils\ReturnData;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class PrivilegeService extends BaseService implements ServiceInterface
{

    /**
     * 获取模型
     * @return PrivilegeModel
     */
    public static function getModel()
    {
        return new PrivilegeModel();
    }

    /**
     * @param $data array 要保存至数据库的数据
     *
     */
    public function add($data)
    {
        //验证器
        $validate = new PrivilegeValidate();
        $validator = self::getValidate($data,'add',$validate);
        if ($validator->fails()){
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());
        }
        try {
            self::getModel()->add($data);

        } catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }

    //修改
    public function edit($data, $where = [])
    {
        if(empty($data)){return ReturnData::create(ReturnData::SUCCESS);}

        //验证器
        $validate = new PrivilegeValidate();
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
                $item['is_menu_name'] = PrivilegeEnum::getName('is_menu',$item['is_menu'],true);
            });
        }
        return ['count'=>$count,'data'=>$list];
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
     * @param int $parent_id
     * @param int $select_id
     * @return array
     */
    public function getSortChildren($parent_id=0,$select_id=0)
    {
        $list = self::getModel()->getAll()->toArray();
        //print_r($list);
        return $this->_sortChildren($list,$parent_id,$select_id);
    }


    /**
     * 获取菜单
     * @param array $privilege 菜单数据
     * @return array
     */
    public function getSystemMenu($privilege=[])
    {
        //获取当前登录管理员的id
        $manager_id = request()->session()->get('manager_id');

        //如果当前是超级管理员
        if($manager_id == 1)
        {
            $arr = self::getModel()->getAll(['is_menu' => 1])->toArray();
        }
        else
        {
            //获取当前管理员的角色
            $arr = $this->getRolePrivileges($manager_id);
        }

        $list = $this->_sortMenu($arr);
        return $list;
    }

    public function _sortMenu($arr,$parent_id=0,$lev=1){
        //取出所有的权限并递归排序
        $tree = [];
        foreach($arr as $k=>$v) {
            if ($v['parent_id'] == $parent_id) {
                $v['lev'] = $lev;
                $v['title'] = $v['privilege_name'];
                $v['href'] = $v['route_url'] != '#'?$v['route_url']:'';
                $v['icon'] = 'fa '.$v['privilege_icon'];
                $v['child'] = $this->_sortMenu($arr,$v['id'],$lev+1);
                if($v['is_menu'] == 0){
                    continue;
                }
                $tree[] = $v;
            }

        }
        return $tree;
    }


    /**
     * 获取指定管理员的可访问菜单
     * @param $manager_id int 管理员id
     * @return mixed
     */
    public function getRolePrivileges($manager_id){
        //$sql = 'select DISTINCT a.* from ol_privilege a left join ol_pri_role b on a.id = b.pri_id left join ol_admin_role c on b.role_id = c.role_id where c.admin_id = '.$manager_id;
        $privilegeData = PrivilegeModel::select('privilege.*')->distinct()->join('role_privilege', 'privilege.id', '=', 'role_privilege.privilege_id')->join('manager_role','role_privilege.role_id','=','manager_role.role_id')->where('manager_role.manager_id',$manager_id)->get()->toArray();
        return $privilegeData;
    }

    /**
     * 后台用户验证权限
     * @return bool
     */
    public function verifyAuthority(){
        $request = request();
        $manager_id = $request->session()->get('manager_id');
        //如果管理员id为1,为系统默认的超级管理员,拥有全部权限
        if($manager_id == 1){
            return TRUE;
        }

        //$manager_id = 17;
        //获取当前请求路由的模块,控制器,方法
        $dashboard = Helper::getModuleControllerAction();

        $url = $dashboard['modules'].'/'.$dashboard['controller'].'/'.$dashboard['action'];
        //获取当前路由
        $routeUrl = $request->route()->uri;

        //不需要验证的路由
        $notVerify = [
            'admin',//后台首页框架
            'admin/login',//后台登录
            'admin/home',//后台首页
            'admin/getMenu',//获取菜单
            'admin/verify',//后台登录验证用户信息
            'admin/login_out'//后台用户退出

        ];
        if(in_array($routeUrl,$notVerify)){
            return TRUE;
        }

        $privileges = $this->getRolePrivileges($manager_id);


        $map = [];
        foreach($privileges as $menu){
            if($menu['route_url'] == '#') continue;
            $map[$menu['module_name'].'/'.$menu['controller_name'].'/'.$menu['action_name']] = $menu['id'];
            //$map[$menu['route_url']] = $menu['id'];
        }
        if(!isset($map[$url])){
            return FALSE;
        }
        return TRUE;
    }

}
