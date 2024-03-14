<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/16
 * Time: 17:58
 */

namespace App\Http\Controllers\Admin;

use App\Http\Common\Utils\ReturnData;
use Illuminate\Http\Request;
use App\Services\Admin\BranchService;
use App\Services\Admin\ManagerService;
use App\Services\Admin\RoleService;
use App\Http\Common\Utils\Helper;


class ManagerController extends BaseController
{

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $service = $this->getService(ManagerService::class);
        if(Helper::isAjaxRequest())
        {
            $post = $request->post();
            return $service->getList($post);
        }

        $branch = $this->getService(BranchService::class)->getSortChildren(0);
        $roles = $this->getService(RoleService::class)->getSortChildren(0,1);
        return view('admin/manager/index',[
            'branch'=>$branch,
            'roles'=>$roles
        ]);
    }


    /*
     * 显示表单
     * */
    public function form(Request $request)
    {

        $branch = $this->getService(BranchService::class)->getSortChildren(0);
        $roles = $this->getService(RoleService::class)->getSortChildren(0,1);
        return view('admin/manager/form',[
            'branch'=>$branch,
            'roles'=>$roles
        ]);
    }

    /*
     * 获取详细
     * */
    public function detail(Request $request){
        if(Helper::isAjaxRequest())
        {
            $id = $request->get('manager_id');
            $service = $this->getService(ManagerService::class);
            $mangerModel =  $service->findModel($id);
            if(!empty($mangerModel->roles)) {
                $mangerModel->role_ids = array_column($mangerModel->roles->toArray(), 'role_id');
            }
            return ReturnData::success($mangerModel);
        }
    }

    /**
     * 新增管理员
     * @return Renderable
     */
    public function create(Request $request)
    {
        $service = $this->getService(ManagerService::class);
        if(Helper::isAjaxRequest())
        {
            $post = $request->post();
            return $service->add($post);
        }
        $branch = $this->getService(BranchService::class)->getSortChildren(0);
        $roles = $this->getService(RoleService::class)->getSortChildren(0,1);
        return view('admin/manager/create',[
            'branch'=>$branch,
            'roles'=>$roles
        ]);
    }


    /**
     * 修改管理员
     * @return Renderable
     */
    public function update(Request $request)
    {
        $id = $request->get('manager_id');
        $service = $this->getService(ManagerService::class);
        $mangerModel =  $service->findModel($id);
        if(Helper::isAjaxRequest())
        {
            $post = $request->post();
            return $service->edit($post,['id'=>$post['manager_id']]);
        }
        $branch = $this->getService(BranchService::class)->getSortChildren(0);
        $roles = $this->getService(RoleService::class)->getSortChildren(0,1);
        if(!empty($mangerModel->roles)) {
            $mangerModel->roles = array_column($mangerModel->roles->toArray(), 'role_id');
        }
        return view('admin/manager/update',[
            //'roles'=>$roles,
            'model'=>$mangerModel,
            'branch'=>$branch,
            'roles'=>$roles
        ]);
    }

    /*
       * 删除管理员
       */
    public function delete(Request $request){
        if(Helper::isAjaxRequest())
        {
            $id = $request->post('manager_id');
            return $this->getService(ManagerService::class)->del($id);
        }
    }


    /*
     * 重置密码
     * */
    public function resetPassword(Request $request){
        if(Helper::isAjaxRequest())
        {
            $service = app()->make(ManagerService::class);
            $post = $request->post();
            return $service->resetPassword($post,['id'=>$post['manager_id']]);
        }
    }

    /*
     * 基本资料
     * @return Renderable
     * */
    public function manageSetting(Request $request){
        //获取当前管理员信息
        $service = $this->getService(ManagerService::class);
        $manager = $service->getLoginManager();
        if(Helper::isAjaxRequest())
        {
            $post = $request->post();
            return $this->getService(ManagerService::class)->manageSetting($post);
        }
        return view('admin/manager/setting',['manager'=>$manager]);
    }

    /*
     * 修改密码
     * @return Renderable
     * */
    public function updatePassword(Request $request){
        if(Helper::isPostRequest())
        {
            $post = $request->post();
            return $this->getService(ManagerService::class)->updatePassword($post);
        }
        return view('admin/manager/update_password');
    }
}

