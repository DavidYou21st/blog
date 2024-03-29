<?php

namespace App\Models\Admin\Blog;

use App\Http\Common\Traits\ModelTrait;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends BaseModel
{
    use SoftDeletes, Sortable, ModelTrait, HasFactory;

    protected $table = 'blog_category';

    protected $fillable = [
        'title',
        'order',
        'status',
    ];

    protected $sortable = [
        'id',
        'title',
        'order',
        'status',
        'created_at',
    ];

    // ----------------------------------------------------------------------------

    public function seo($type = null)
    {
        if ($type === null) {
            return;
        }

        if ($type === 'title') {
            if (! empty($this->seo_title)) {
                return str_limit($this->seo_title, 70);
            }

            return str_limit($this->title, 70);
        }

        if ($type === 'description') {
            if (! empty($this->seo_description)) {
                return str_limit($this->seo_description, 170);
            }

            return str_limit($this->summary, 170);
        }

        if ($type === 'keywords') {
            return $this->seo_keywords;
        }
    }

    // ----------------------------------------------------------------------------
}
