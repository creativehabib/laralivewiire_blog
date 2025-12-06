<?php
// app/Models/Category.php

namespace App\Models;

use App\Models\Concerns\HasMetaBoxes;
use App\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasMetaBoxes, HasSeoMeta;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'icon',
        'is_featured',
        'order',
        'is_default',
        'status',
        'author_id',
        'author_type',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_default'  => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getParentPathAttribute(): ?string
    {
        $names = [];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($names, $parent->name); // শুরুতে যোগ
            $parent = $parent->parent;
        }

        return count($names) ? implode(' → ', $names) : null;
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->with('childrenRecursive')
            ->withCount('posts')
            ->orderBy('order');
    }

    public function author()
    {
        return $this->morphTo(__FUNCTION__, 'author_type', 'author_id');
    }

    public function posts()
    {
        // pivot table: post_categories (post_id, category_id)
        return $this->belongsToMany(Post::class, 'post_categories')
            ->withTimestamps();
    }

}
