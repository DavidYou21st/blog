<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/17
 * Time: 18:03
 */

namespace App\Validate\Admin\Blog;
use Illuminate\Http\Request;

class CommentValidate extends Request
{
    //总的验证规则
    protected $rules = [
        'branch_id' => 'required|integer',
        'branch_name' => 'required|max:50',
        'type' => 'integer|between:2,2',
        'parent_id' => 'integer',
        'orders' => 'integer|between:0,9999',
    ];

    //总的自定义错误信息
    protected $messages = [
        'branch_id.required' => 'ID必填',
        'branch_id.integer' => 'ID必须为数字',
        'branch_name.required' => '部门名称必填',
        'branch_name.max' => '部门名称不能大于50个字',
        'type.integer'=>'角色状态必须是数字',
        'type.between'=>'角色状态的值必须在1和2之间',
        'is_cate.integer'=>'权限类型必须是数字',
        'is_cate.between'=>'权限类型的值必须在0和1之间',
        'parent_id.integer'=>'上级权限值必须是数字',
        'orders.integer'=>'排序的值必须是数字',
        'orders.between'=>'排序的值必须在0与9999之间',
    ];

    //场景验证规则
    protected $scene = [
        'add'  => ['branch_name', 'type','orders','parent_id'],
        'edit' => ['branch_id','branch_name', 'type','orders'],
        'del'  => ['branch_id'],
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
