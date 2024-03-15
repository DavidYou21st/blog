<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/16
 * Time: 17:58
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\BranchService;
use App\Http\Common\Utils\Helper;

class BranchController extends BaseController
{
    /**
     * 部门列表
     * @return Renderable
     */
    public function index()
    {
        $service = $this->getService(BranchService::class);
        if(Helper::isAjaxRequest()){
            return $service->getList();
            //return $this->getService(RoleService::class)->getList();
        }

        $branch = $service->getSortChildren(0);

        return view('admin/branch/index',['branch'=>$branch]);
    }


    /**
     * 新增部门
     * @return Renderable
     */
    public function create(Request $request)
    {
        $service = $this->getService(BranchService::class);
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            return $service->add($post);
        }
    }

    /**
     * 修改部门
     * @return Renderable
     */
    public function update(Request $request)
    {
        $id = $request->get('branch_id');
        $service = $this->getService(BranchService::class);
        $roleModel =  $service->findModel($id);
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            return $service->edit($post,['id'=>$post['branch_id']]);
        }
    }


    /*
    * 删除部门
    */
    public function delete(Request $request){
        if(Helper::isAjaxRequest()){
            $id = $request->post('branch_id');
            return $this->getService(BranchService::class)->del($id);
        }
    }



    /**
     * 新增部门
     * @return Renderable
     */
    public function view(Request $request)
    {
        $service = $this->getService(BranchService::class);
        $branch = $service->getSortChildren2(0);
        return view('admin/branch/view_framework',[
            'branch'=>$branch,
        ]);
    }
}

