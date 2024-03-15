<?php

namespace Modules\Common\Models;

use App\Models\BaseModel;
use App\Http\Common\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
* This is the model class for table "{hp_report}".
*
* @property string $id 主键id
* @property string $type 类型
* @property string $status 状态
* @property string $menu_id 菜单ID
* @property string $create_time 创建时间
* @property string $create_user 创建人
* @property string $create_organize 创建组织
* @property string $update_user 修改人
* @property string $update_time 修改时间
* @property string $field_462909 标题
* @property string $field_988324 内容
* @property string $field_948351 填报人
* @property string $field_833418 填报日期
* @property string $field_711926 备注
*/
class Report extends BaseModel{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'id';
    protected $table = 'report';

    use HasFactory;
    use ModelTrait;

    /**
    * 表明模型是否应该被打上时间戳
    *
    * @var bool
    */
    public $timestamps = true;


    /**
    * 获取当前时间
    *
    * @return int
    */
    public function freshTimestamp() {
        return time();
    }

    /**
    * 避免转换时间戳为时间字符串
    *
    * @param DateTime|int $value
    * @return DateTime|int
    */
    public function fromDateTime($value) {
        return $value;
    }

    /**
    * select的时候避免转换时间为Carbon
    *
    * @param mixed $value
    * @return mixed
    */
    //  protected function asDateTime($value) {
    //	  return $value;
    //  }

    /**
    * 从数据库获取的为获取时间戳格式
    *
    * @return string
    */
    public function getDateFormat() {
        return 'U';
    }


    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'type' => '类型',
            'status' => '状态',
            'menu_id' => '菜单ID',
            'create_time' => '创建时间',
            'create_user' => '创建人',
            'create_organize' => '创建组织',
            'update_user' => '修改人',
            'update_time' => '修改时间',
            'field_462909' => '标题',
            'field_988324' => '内容',
            'field_948351' => '填报人',
            'field_833418' => '填报日期',
            'field_711926' => '备注',
        ];
    }

}


?>