<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/18
 * Time: 8:41
 */

namespace App\Services\Admin;


use Illuminate\Support\Facades\Validator;

class BaseService
{

    //获取验证方法
    public static function getValidate($data, $scene_name,$validate)
    {
        //数据验证
        //$validate = new GoodsTypeRequest();
        return Validator::make($data, $validate->getSceneRules($scene_name), $validate->getSceneRulesMessages());
    }
}
