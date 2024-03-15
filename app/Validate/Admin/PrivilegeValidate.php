<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/17
 * Time: 18:03
 */

namespace App\Validate\Admin;
use Illuminate\Http\Request;

class PrivilegeValidate extends Request
{
    //总的验证规则
    protected $rules = [
        'privilege_id' => 'required|integer',
        'privilege_name' => 'required|max:20',
        'module_name' => 'required|max:20',
        'controller_name' => 'required|max:20',
        'action_name' => 'required|max:30',
        'route_url' => 'required|max:100',
        'route_name' => 'max:30',
        'parameter' => 'max:50',
        'is_menu' => 'integer|between:0,1',
        'parent_id' => 'integer',
        'orders' => 'integer|between:0,9999',
    ];

    //总的自定义错误信息
    protected $messages = [
        'privilege_id.required' => 'ID必填',
        'privilege_id.integer' => 'ID必须为数字',
        'privilege_name.required' => '权限名称必填',
        'privilege_name.max' => '权限名称不能大于20个字',
        'module_name.required' => '模块名称必填',
        'module_name.max' => '模块名称不能大于20个字',
        'controller_name.required' => '控制器名称必填',
        'controller_name.max' => '控制器名称不能大于20个字',
        'action_name.required' => '方法名称必填',
        'action_name.max' => '方法名称不能大于20个字',
        'route_url.required' => '路由地址必填',
        'route_url.max' => '路由地址长度不能大于100',
        'route_name.max'=>'路由名称长度不能大于30',
        'parameter.max'=>'参数长度不能大于50',
        'is_menu.integer'=>'权限类型必须是数字',
        'is_menu.between'=>'权限类型的值必须在0和1之间',
        'parent_id.integer'=>'上级权限值必须是数字',
        'orders.integer'=>'排序的值必须是数字',
        'orders.between'=>'排序的值必须在0与9999之间',
    ];

    //场景验证规则
    protected $scene = [
        'add'  => ['privilege_name', 'module_name', 'controller_name', 'action_name', 'route_url','route_name','parameter','privilege_icon','target','orders','is_menu'],
        'edit' => ['privilege_id','privilege_name', 'module_name', 'controller_name', 'action_name', 'route_url','route_name','parameter','privilege_icon','target','orders','is_menu'],
        'del'  => ['privilege_id'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //修改为true
    }


    /**
     * 获取应用到请求的验证规则
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * 获取被定义验证规则的错误消息.
     *
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    //获取场景验证规则
    public function getSceneRules($name, $fields = null)
    {
        $res = [];

        if(!isset($this->scene[$name]))
        {
            return false;
        }

        $scene = $this->scene[$name];
        if($fields != null && is_array($fields))
        {
            $scene = $fields;
        }

        foreach($scene as $k=>$v)
        {
            if(isset($this->rules[$v])){$res[$v] = $this->rules[$v];}
        }

        return $res;
    }

    //获取场景验证规则自定义错误信息
    public function getSceneRulesMessages()
    {
        return $this->messages;
    }
}
