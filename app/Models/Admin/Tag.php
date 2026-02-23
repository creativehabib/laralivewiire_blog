<?php

namespace App\Models\Admin;

use App\Models\Post;
use App\Models\Concerns\HasMetaBoxes;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, HasMetaBoxes, HasSlug;

    protected $fillable = [
        'name',
        'description',
        'status',
        'author_id',
        'author_type',
    ];

    protected static function booted(): void
    {
        static::deleting(function (self $tag): void {
            $tag->posts()->detach();
        });
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }
}
