<?php

namespace App\Models;

use App\Models\Admin\Tag;
use App\Models\Concerns\HasMetaBoxes; // যদি meta ব্যবহার করো
use App\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    use HasMetaBoxes;
    use HasSeoMeta;

    protected $fillable = [
        'name',
        'description',
        'content',
        'slug',
        'status',
        'author_id',
        'author_type',
        'is_featured',
        'image',
        'views',
        'allow_comments',
        'is_breaking',
        'format_type',
        'seo_score',
    ];

    // Categories (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_categories');
    }

    // Tags (many-to-many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getSeoScoreAttribute()
    {
        return $this->analyzeSeo()['score'];
    }
}
