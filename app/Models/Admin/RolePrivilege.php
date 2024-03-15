<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/6/21
 * Time: 16:28
 */

namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Http\Common\Traits\ModelTrait;

class RolePrivilege extends BaseModel
{
    use HasFactory;

    use ModelTrait;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'role_privilege';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    protected $dateFormat = 'U';
}
