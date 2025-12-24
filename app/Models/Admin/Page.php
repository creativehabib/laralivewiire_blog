<?php

namespace App\Models\Admin;

use App\Models\Admin\Comment;
use App\Models\Concerns\HasMetaBoxes;
use App\Models\Concerns\HasSeoMeta;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Page extends Model
{
    use HasMetaBoxes, HasSeoMeta, SoftDeletes, HasSlug;

    protected $fillable = [
        'name', 'template', 'content', 'status', 'seo_score', 'author_id', 'author_type','created_at','updated_at',
    ];

    protected $dates = ['deleted_at'];
    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
