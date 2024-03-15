<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/16
 * Time: 17:58
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\Admin\ManagerService;
use App\Http\Common\Utils\Helper;

class LoginController extends Controller
{
    /**
     * 登录视图
     * @return Renderable
     */
    public function login()
    {
        return view('admin/admin/login');
    }

    /**
     * 账户登录方法
     * @param Request $request
     * @return bool
     */
    public function verify(Request $request){
        if(Helper::isAjaxRequest()){
            $post = $request->post();
            $service = new ManagerService();
            return $service->doLogin($post);
        }
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginOut(Request $request){
        if($request->session()->has('manager_id')){
            $request->session()->flush();
        }
        return redirect("/admin/login");
    }

}

