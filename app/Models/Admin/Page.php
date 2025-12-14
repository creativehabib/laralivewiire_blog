<?php

namespace App\Models\Admin;

use App\Models\Concerns\HasMetaBoxes;
use App\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasMetaBoxes, HasSeoMeta, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'template', 'content', 'status', 'seo_score', 'author_id', 'author_type','created_at','updated_at',
    ];

    protected $dates = ['deleted_at'];
    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }
}
