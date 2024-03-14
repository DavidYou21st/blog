<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/17
 * Time: 17:16
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Admin\ServiceInterface;

class BaseController extends Controller
{
    /*
    protected $request;
    protected $response;
    public function __construct(Request $request,Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    */

    //获取验证方法
    public function getValidate($data, $scene_name,$validate)
    {
        //数据验证
        //$validate = new GoodsTypeRequest();
        return Validator::make($data, $validate->getSceneRules($scene_name), $validate->getSceneRulesMessages());
    }

    //获取服务
    public function getService($service)
    {
        return new $service();
    }

}
