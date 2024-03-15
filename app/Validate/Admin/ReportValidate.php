<?php 
namespace App\Validate\Admin;
use Illuminate\Http\Request;
class ReportValidate extends Request
{
    //验证规则
    protected $rules = array(
		'type' => 'max:50',
		'status' => 'max:8',
		'menu_id' => 'max:50',
		'create_user' => 'require|max:50',
		'create_organize' => 'require|max:50',
		'update_user' => 'max:50',
		'field_462909' => 'max:1000',
		'field_988324' => 'max:1000',
		'field_948351' => 'max:1000',
		'field_833418' => 'max:1000',
		'field_711926' => 'max:1000',
	);
    //验证失败提示信息
    protected $messages = array(
		'type.max'=>'类型的值不能超过50个字符',
		'status.max'=>'状态的值不能超过8个字符',
		'menu_id.max'=>'菜单ID的值不能超过50个字符',
		'create_user.require'=>'创建人不能为空',
		'create_user.max'=>'创建人的值不能超过50个字符',
		'create_organize.require'=>'创建组织不能为空',
		'create_organize.max'=>'创建组织的值不能超过50个字符',
		'update_user.max'=>'修改人的值不能超过50个字符',
		'field_462909.max'=>'标题的值不能超过1000个字符',
		'field_988324.max'=>'内容的值不能超过1000个字符',
		'field_948351.max'=>'填报人的值不能超过1000个字符',
		'field_833418.max'=>'填报日期的值不能超过1000个字符',
		'field_711926.max'=>'备注的值不能超过1000个字符',
);
    //验证场景
    protected $scene = array(
		'add'=>array('type','status','menu_id','create_user','create_organize','update_user','field_462909','field_988324','field_948351','field_833418','field_711926'),
		'edit'=>array('id','type','status','menu_id','create_user','create_organize','update_user','field_462909','field_988324','field_948351','field_833418','field_711926'),
	);

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