<?php

namespace App\Models;

use App\Models\Admin\Tag;
use App\Models\Admin\Comment;
use App\Models\Concerns\HasMetaBoxes; // যদি meta ব্যবহার করো
use App\Models\Concerns\HasSeoMeta;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;
    use HasMetaBoxes;
    use HasSeoMeta;
    use HasSlug;

    const CONTENT_TYPE_VIDEO = 'video';

    protected $fillable = [
        'name',
        'description',
        'content',
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
        return $query->whereIn('status', ['published', 'publish']);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->getImageUrl();
    }

    public function getImageUrl(?int $width = null, ?int $height = null): string
    {
        $placeholder = 'https://placehold.co/800x450?text=News+Image';

        if (! $this->image) {
            return $this->applyImageOptimization($placeholder, $width, $height);
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            $url = $this->image;
        } elseif (Storage::disk('public')->exists($this->image)) {
            $url = Storage::disk('public')->url($this->image);
        } else {
            $url = asset('storage/'.$this->image);
        }

        return $this->applyImageOptimization($url, $width, $height);
    }

    private function applyImageOptimization(string $url, ?int $width, ?int $height): string
    {
        if (! setting('image_optimize_enabled', false)) {
            return $url;
        }

        $params = [];
        $defaultQuery = trim((string) setting('image_optimize_query', ''));

        if ($defaultQuery !== '') {
            $defaultQuery = ltrim($defaultQuery, '?');
            if ($defaultQuery !== '') {
                $params[] = $defaultQuery;
            }
        }

        if ($width) {
            $params[] = 'w='.$width;
        }

        if ($height) {
            $params[] = 'h='.$height;
        }

        if ($params === []) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.implode('&', $params);
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
