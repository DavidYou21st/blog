<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/15
 * Time: 11:05
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\PrivilegeService;
use App\Services\Admin\RoleService;
use App\Http\Common\Utils\Helper;
use App\Http\Common\Utils\ReturnData;

class RoleController extends BaseController
{
    public function index()
    {
        if(Helper::isAjaxRequest()){
            $list = $this->getService(RoleService::class)->getList();
            return ReturnData::success($list['data'],$list['count']);
            //return $this->getService(RoleService::class)->getList();
        }
        return view('admin/role/index');
    }


    /**
     * 新增角色
     * @return Renderable
     */
    public function create(Request $request)
    {
        $service = $this->getService(RoleService::class);
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            return $service->add($post);
        }
        //递归无限级排序
        $roles = $service->getSortChildren(0,1);
        //print_r($roles);die();
        return view('admin/role/create',[
            'roles'=>$roles
        ]);
    }

    /**
     * 修改角色
     * @return Renderable
     */
    public function update(Request $request)
    {
        $id = $request->get('role_id');
        $service = $this->getService(RoleService::class);
        $roleModel =  $service->findModel($id);
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            return $service->edit($post,['id'=>$post['role_id']]);
        }
        $roles = $service->getSortChildren(0,$roleModel->id);
        return view('admin/role/update',[
            'roles'=>$roles,
            'model'=>$roleModel
        ]);
    }

    /*
     * 删除角色
     */
    public function delete(Request $request){
        if(Helper::isAjaxRequest()){
            $id = $request->post('role_id');
            return $this->getService(RoleService::class)->del($id);
        }
    }

    /**
     * 角色授权
     * @param Request $request
     */
    public function authorize(Request $request)
    {
        $id = $request->get('role_id');
        $service = $this->getService(RoleService::class);
        $roleModel =  $service->findModel($id);
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            //print_r($post);die();
            $tmp = [];
            if(!empty($post['privilege_ids'])){
               return $service->saveRolePrivilege($post);
            }
        }
        $roleModel->rolePrivileges = array_column($roleModel->privileges->toArray(),'privilege_id');
        //获取所有权限
        $privileges = $this->getService(PrivilegeService::class)->getSortList();
        //print_r($privileges);die();
        /*
        return view('admin/role/authorize',[
            'privileges'=>$privileges,
            'model'=>$roleModel
        ]);
        */
        return ReturnData::success(['privileges'=>$privileges,'role'=>$roleModel]);
    }
}
