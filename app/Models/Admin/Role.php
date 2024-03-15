<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Http\Common\Traits\ModelTrait;

class Role extends BaseModel
{
    use HasFactory;

    use ModelTrait;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'role';

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

    protected $dateFormat = 'U';


    /**
     * 获取该角色下的所有权限
     */
    public function privileges()
    {
        return $this->hasMany('App\Models\Admin\RolePrivilege','role_id','id');
    }

}
