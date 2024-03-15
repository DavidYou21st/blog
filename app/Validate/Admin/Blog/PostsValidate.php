<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/17
 * Time: 18:03
 */

namespace App\Validate\Admin\Blog;
use Illuminate\Http\Request;

class PostsValidate extends Request
{
    //总的验证规则
    protected $rules = [
        'id' => 'required|integer',
        'title' => 'required|max:60',
        'status' => 'integer|between:0,1',
    ];

    //总的自定义错误信息
    protected $messages = [
        'id.required' => 'ID必填',
        'id.integer' => 'ID必须为数字',
        'title.required' => '标题名称必填',
        'title.max' => '标题长度不能超过20',
        'status.integer'=>'排序的值必须是数字',
        'status.between'=>'排序的值必须在0与1之间',
    ];

    //场景验证规则
    protected $scene = [
        'add'  => ['title','status'],
        'edit' => ['id','status'],
        'del'  => ['id'],
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
