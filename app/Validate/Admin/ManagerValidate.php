<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/17
 * Time: 18:03
 */

namespace App\Validate\Admin;
use Illuminate\Http\Request;

class ManagerValidate extends Request
{
    //总的验证规则
    protected $rules = [
        'manager_id' => 'required|integer',
        'full_name' => 'required|max:20',
        'account' => 'required|unique:manager|max:20',
        'password' => 'required',
        'email'    => 'email:rfc,dns',
        'status' => 'integer|between:0,1',
        'old_password'=>'required',
        'confirm_password'=>'required|same:password',
    ];

    //总的自定义错误信息
    protected $messages = [
        'manager_id.required' => 'ID必填',
        'manager_id.integer' => 'ID必须为数字',
        'full_name.required' => '权限名称必填',
        'full_name.max' => '姓名长度不能超过20',
        'account.required'=>'登录账号不能为空',
        'account.unique'=>'登录账号保证唯一',
        'account.max'=>'登录账号长度不能超过20',
        'password.required'=>'登录密码不能为空',
        'email.email'=>'邮箱不能为空',
        'status.integer'=>'排序的值必须是数字',
        'status.between'=>'排序的值必须在0与9999之间',
        'old_password'=>'旧密码不能为空',
        'confirm_password.required'=>'确认密码不能为空',
        'confirm_password.same'=>'确认密码输入不一致',
    ];

    //场景验证规则
    protected $scene = [
        'add'  => ['full_name','account','password','email', 'tel','status'],
        'edit' => ['manager_id','full_name','email', 'tel','status'],
        'del'  => ['manager_id'],
        'updatePassword'=>['password','old_password','confirm_password'],
        'manageSetting'=>['full_name','email', 'tel']
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
