<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/7/29
 * Time: 9:42
 */

namespace App\Http\Controllers\Admin;

use App\Http\Common\Utils\ReturnData;
use App\Services\Admin\ManagerService;
use Illuminate\Http\Request;
use App\Http\Common\Utils\Helper;
use App\Services\Admin\PrivilegeService;

class AdminController extends BaseController
{

    public function index(){
        $service = $this->getService(ManagerService::class);
        $manager = $service->getLoginManager();
        return view('admin.admin.index',[
            'manager'=>$manager,
        ]);
    }


    /**
     * 获取菜单
     * @param Request $request
     * @return mixed
     */
    public function getMenu(Request $request){
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            $service = new PrivilegeService();
            $menu = $service->getSystemMenu();

            return ReturnData::success($menu);
        }
    }

    /*
    * 404页面
    * */
    public function error404(){
        return view('admin.admin.error404');
    }

    public function home(){
        return view('admin.admin.home');
    }

    public function theme(){


        return view('admin.admin.theme');
    }

    public function updatePassword(){

        return view('admin.admin.updatePassword');
    }

    public function note(){

        return view('admin.admin.tpl-note');
    }


    public function lockScreen(){

        return view('admin.admin.tpl-lock-screen');
    }

    public function message(){

        return view('admin.admin.tpl-message');
    }

    /*
     * 基础配置
     * */
    public function system(){
        return view('admin/admin/system');
    }



}
