<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/24
 * Time: 15:31
 */

namespace App\Services\Admin;


use App\Models\Admin\LoginLog;
use App\Models\Admin\Manager;
use App\Models\Admin\OperateLog;
use App\Models\Admin\Privilege;
use App\Http\Common\Enum\BaseEnum;
use App\Http\Common\Utils\Helper;
use App\Http\Common\Utils\ReturnData;

class LogService extends BaseService
{

    /**
     * 登录日志
     * @param string $message 登录提示信息
     * @param int $status 状态 0成功,1失败
     * @param string $account 登录账号
     * @return bool
     */
    public static function addLoginLog($message='',$status=0,$account='')
    {
        //获取当前请求路由
        $request = request();
        $routeUrl = $request->route()->uri();
        //获取当前登录人员
        $manager_id = $request->session()->get('manager_id');
        $managerModel = new Manager();
        $manager = $managerModel->findOne(['id'=>$manager_id]);
        //获取useragent
        $useragent = Helper::getBrowser();
        $model = new LoginLog();
        $insert = [
            'user_id'=>$manager['id'],
            'username'=>empty($manager['account'])?$account:$manager['account'],
            'ip'=>$request->ip(),
            'status'=>$status,
            'message'=>$message,
            'operating_system'=>Helper::getOS(),
            'useragent'=>$useragent['name'],
            'create_time'=>time(),
        ];
        return $model->load($insert)->save();
    }


    /**
     * 操作日志
     * @param array $data 请求参数
     * @param string $message 提示信息
     * @param string $rm 响应信息
     * @param int $status 状态 0:成功,1:失败
     * @return bool
     */
    public static function addOperateLog($data, $message = '',$rm='',$status=0)
    {
        //获取当前请求路由
        $request = request();
        $routeUrl = $request->route()->uri();
        //获取当前登录人员
        $manager_id = $request->session()->get('manager_id');
        $managerModel = new Manager();
        $manager = $managerModel->findOne(['id'=>$manager_id]);

        //获取useragent
        $useragent = Helper::getBrowser();
        $privilegeService = new Privilege();
        $privilege = $privilegeService->findModel(false,['route_url'=>'/'.$routeUrl]);
        $insert = [
            'title'=>$privilege->privilege_name,
            'user_id'=>$manager['id'],
            'username'=>$manager['account'],
            'url'=>'/'.$routeUrl,
            'method'=>$request->getMethod(),
            'ip'=>$request->ip(),
            'request_params'=>json_encode($data),
            'response_params'=>$rm,
            'status'=>$status,
            'message'=>$message,
            'operating_system'=>Helper::getOS(),
            'useragent'=>$useragent['name'],
            'create_time'=>time(),
        ];

        $model = new OperateLog();
        return $model->load($insert)->save();
    }

    /**
     * 获取登录日志列表
     * @param array $data 参数
     * @return array
     */
    public static function getLoginLogList($data=[]){
        $model = new LoginLog();
        $where = '';
        if(!empty($data) && isset($data['searchParams'])){
            $search = json_decode($data['searchParams'],true);

            $where = function($query) use ($search){
                if(isset($search['username'])) {
                    $query->where('username', 'like', '%' . $search['username'] . '%');
                }
            };
        }

        $count = $model->getCount($where);
        $list = [];
        if($count > 0){
            $list = $model->getAll($where,['create_time','desc'])->each(function($item){
                $item['status'] = BaseEnum::getBoolValue($item['status']);
                $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
            });
        }
        return ReturnData::success($list,$count);
    }

    /**
     * 获取操作日志
     * @param array $data 参数
     * @return array
     */
    public static function getOperateLogList($data){
        $model = new OperateLog();
        $where = '';
        if(!empty($data) && isset($data['searchParams'])){
            $search = json_decode($data['searchParams'],true);
            $where = function($query) use ($search){
                if(isset($search['username'])) {
                    $query->where('username', 'like', '%' . $search['username'] . '%');
                }
            };
        }

        $count = $model->getCount($where);
        $list = [];
        if($count > 0){
            $list = $model->getAll($where,['create_time','desc'])->each(function($item){
                $item['status'] = BaseEnum::getBoolValue($item['status']);
                $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
            });
        }
        return ReturnData::success($list,$count);
    }

    /**
     * 删除一条数据
     * @param $id
     * @param array $where
     * @return array
     */
    public function delSysOperateLog($id,$where=[]){
        $model = new OperateLog();
        $model = $model->findModel($id,$where);
        try {
            $model->delete();
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
    public function delSysLoginLog($id,$where=[]){
        $model = new LoginLog();
        $model = $model->findModel($id,$where);
        try {
            $model->delete();
        } catch (\Exception $ex) {
            return ReturnData::create(ReturnData::PARAMS_ERROR, null, $ex->getMessage());
        }
        return ReturnData::create(ReturnData::SUCCESS);
    }
}
