<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/17
 * Time: 18:03
 */

namespace App\Validate\Admin;
use Illuminate\Http\Request;

class RoleValidate extends Request
{
    //总的验证规则
    protected $rules = [
        'role_id' => 'required|integer',
        'role_name' => 'required|max:20',
        'remark' => 'max:300',
        'status' => 'integer|between:0,1',
        'is_cate' => 'integer|between:0,1',
        'parent_id' => 'integer',
        'orders' => 'integer|between:0,9999',
    ];

    //总的自定义错误信息
    protected $messages = [
        'privilege_id.required' => 'ID必填',
        'privilege_id.integer' => 'ID必须为数字',
        'role_name.required' => '权限名称必填',
        'role_name.max' => '权限名称不能大于20个字',
        'status.integer'=>'角色状态必须是数字',
        'status.between'=>'角色状态的值必须在0和1之间',
        'is_cate.integer'=>'权限类型必须是数字',
        'is_cate.between'=>'权限类型的值必须在0和1之间',
        'parent_id.integer'=>'上级权限值必须是数字',
        'orders.integer'=>'排序的值必须是数字',
        'orders.between'=>'排序的值必须在0与9999之间',
    ];

    //场景验证规则
    protected $scene = [
        'add'  => ['role_name', 'status','orders','is_cate','remark'],
        'edit' => ['role_id','role_name', 'status','orders','is_cate','remark'],
        'del'  => ['role_id'],
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
