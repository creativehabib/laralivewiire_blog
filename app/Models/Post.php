<?php

namespace App\Models;

use App\Models\Admin\Tag;
use App\Models\Concerns\HasMetaBoxes; // যদি meta ব্যবহার করো
use App\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function primaryCategory(): ?Category
    {
        if ($this->relationLoaded('categories')) {
            return $this->categories->first();
        }

        return $this->categories()->first();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getImageUrlAttribute(): string
    {
        $placeholder = 'https://placehold.co/800x450?text=News+Image';

        if (! $this->image) {
            return $placeholder;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return asset('storage/'.$this->image);
    }

    public function getExcerptAttribute(): ?string
    {
        if ($this->description) {
            return $this->description;
        }

        if ($this->content) {
            return Str::limit(strip_tags($this->content), 160);
        }

        return null;
    }

    public function getSeoScoreAttribute()
    {
        return $this->analyzeSeo()['score'];
    }
}
