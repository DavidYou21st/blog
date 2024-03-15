<?php

namespace App\Models\Admin\Blog;

use App\Http\Common\Traits\ModelTrait;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends BaseModel
{
    use SoftDeletes, ModelTrait, HasFactory;

    protected $table = 'blog_comments';

    protected $fillable = [
        'post_id',
        'name',
        'email',
        'description',
        'status',
    ];

    public function post()
    {
        return $this->belongsTo('App\Models\Admin\Blog\Posts');
    }
}
