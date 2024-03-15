<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/25
 * Time: 11:52
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Admin\LogService;
use App\Http\Common\Utils\Helper;

class LogController extends BaseController
{
    /*
     * 操作日志
     */
    public function sysOperateLog(Request $request){
        if(Helper::isAjaxRequest())
        {
            $post = $request->post();
            return LogService::getOperateLogList($post);
        }
        return view('admin/admin/sys_operate_log');
    }

    /*
     * 登录日志
     */
    public function sysLoginLog(Request $request){
        if(Helper::isAjaxRequest())
        {
            $post = $request->post();
            return LogService::getLoginLogList($post);
        }
        return view('admin/admin/sys_login_log');
    }

    //删除操作日志
    public function deleteSysOperateLog(Request $request){
        if(Helper::isAjaxRequest()){
            $id = $request->post('log_id');
            return $this->getService(LogService::class)->delSysOperateLog($id);
        }
    }

    //删除登录日志
    public function deleteSysLoginLog(Request $request){
        if(Helper::isAjaxRequest()){
            $id = $request->post('log_id');
            return $this->getService(LogService::class)->delSysLoginLog($id);
        }
    }

}
