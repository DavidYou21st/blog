<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Http\Common\Traits\ModelTrait;

class Branch extends BaseModel
{
    use HasFactory;

    use ModelTrait;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'branch';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
     * 获取该角色下的所有权限
     */
    public function privileges()
    {
        return $this->hasMany('App\Models\Admin\RolePrivilege','role_id','id');
    }

}
