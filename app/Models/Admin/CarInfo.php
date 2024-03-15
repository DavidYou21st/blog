<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Http\Common\Traits\ModelTrait;

class CarInfo extends BaseModel
{
    use HasFactory;

    use ModelTrait;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'car_info';

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
    public $timestamps = false;





}
