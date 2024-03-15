<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/15
 * Time: 11:05
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\LogService;
use App\Services\Admin\PrivilegeService;
use App\Http\Common\Utils\Helper;
use App\Http\Common\Utils\ReturnData;
use App\Models\Admin\Privilege as PrivilegeModel;

class PrivilegeController extends BaseController
{

    public function index(Request $request)
    {
        if(Helper::isAjaxRequest()){
            $list = $this->getService(PrivilegeService::class)->getList();
            return ReturnData::success($list['data'],$list['count']);
        }
        return view('admin/privilege/index');
    }


    /**
     * 新增权限
     * @return Renderable
     */
    public function create(Request $request)
    {
        $service = $this->getService(PrivilegeService::class);

        if(Helper::isPostRequest()){
            $post = $request->post();
            return $service->add($post);
        }

        //递归无限级排序
        $privileges = $service->getSortChildren(0,1);
        return view('admin/privilege/create',['privileges'=>$privileges]);
    }


    /**
     * 修改权限
     * @return Renderable
     */
    public function update(Request $request)
    {
        $id = $request->get('privilege_id');
        $service = $this->getService(PrivilegeService::class);
        $privilegeModel =  $service->findModel($id);
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            return $service->edit($post,['id'=>$post['privilege_id']]);
        }
        $privileges = $service->getSortChildren(0,$privilegeModel->id);
        return view('admin/privilege/update',['privileges'=>$privileges,'model'=>$privilegeModel]);
    }


    /*
     * 删除权限
     */
    public function delete(Request $request){
        if(Helper::isAjaxRequest()){
            $id = $request->post('privilege_id');
            return $this->getService(PrivilegeService::class)->del($id);
        }
    }


}
