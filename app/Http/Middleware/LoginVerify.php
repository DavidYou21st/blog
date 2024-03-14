<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2024/3/12
 * Time: 17:50
 */

namespace App\Http\Middleware;



use Closure;
use App\Services\Admin\PrivilegeService;
use App\Http\Common\Utils\ReturnData;

class LoginVerify
{

    public function handle($request, Closure $next)
    {
        // 执行动作
        $routeUrl = $request->route()->uri;
        //过滤路由
        $arr = ['admin/login','admin/verify'];
        if(!$request->session()->has('manager_id') && !in_array($routeUrl,$arr) ){
            return redirect("/admin/login");
        }

        $service = new PrivilegeService();
        if(!$service->verifyAuthority()){
            //throw new \Exception(ReturnData::getCodeText(ReturnData::FORBIDDEN),ReturnData::FORBIDDEN);
        }
        return $next($request);
    }
}
