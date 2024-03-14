<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Http\Common\Traits\ModelTrait;

class Config extends BaseModel
{
    use HasFactory;

    use ModelTrait;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

}
