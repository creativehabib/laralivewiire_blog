<?php

namespace App\Support;

use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class Seo
{
    /**
     * Normalize arbitrary meta array with defaults.
     */
    public static function fromArray(array $meta = []): array
    {
        return static::normalize($meta);
    }

    /**
     * Homepage / generic site level SEO data.
     */
    public static function forHomepage(array $overrides = []): array
    {
        $meta = [
            'title' => setting('site_title', config('app.name')),
            'description' => setting('site_description'),
            'url' => route('home'),
            'type' => 'website',
        ];

        return static::normalize(array_merge($meta, $overrides));
    }

    public static function forPost(?Post $post, array $overrides = []): array
    {
        if (! $post) {
            return static::normalize($overrides);
        }

        $seoMeta = method_exists($post, 'getSeoMeta') ? $post->getSeoMeta() : [];

        $meta = [
            'title' => $seoMeta['seo_title'] ?? $post->name,
            'description' => $seoMeta['seo_description'] ?? $post->excerpt,
            'image' => $seoMeta['seo_image'] ?? $post->image_url ?? null,
            'index' => $seoMeta['index'] ?? 'index',
            'url' => post_permalink($post),
            'type' => 'article',
            'published_time' => optional($post->created_at)->toIso8601String(),
            'modified_time' => optional($post->updated_at)->toIso8601String(),
        ];

        return static::normalize(array_merge($meta, $overrides));
    }

    public static function forCategory(?Category $category, array $overrides = []): array
    {
        if (! $category) {
            return static::normalize($overrides);
        }

        $seoMeta = method_exists($category, 'getSeoMeta') ? $category->getSeoMeta() : [];

        $meta = [
            'title' => $seoMeta['seo_title'] ?? $category->name,
            'description' => $seoMeta['seo_description'] ?? $category->description,
            'image' => $seoMeta['seo_image'] ?? $category->image ?? null,
            'index' => $seoMeta['index'] ?? 'index',
            'url' => route('categories.show', ['category' => $category->slug]),
            'type' => 'website',
        ];

        return static::normalize(array_merge($meta, $overrides));
    }

    public static function forTag(?Tag $tag, array $overrides = []): array
    {
        if (! $tag) {
            return static::normalize($overrides);
        }

        $seoMeta = method_exists($tag, 'getSeoMeta') ? $tag->getSeoMeta() : [];

        $meta = [
            'title' => $seoMeta['seo_title'] ?? $tag->name,
            'description' => $seoMeta['seo_description'] ?? $tag->description,
            'image' => $seoMeta['seo_image'] ?? null,
            'index' => $seoMeta['index'] ?? 'index',
            'url' => route('tags.show', ['tag' => $tag->slug]),
            'type' => 'website',
        ];

        return static::normalize(array_merge($meta, $overrides));
    }

    public static function forPage(?Page $page, array $overrides = []): array
    {
        if (! $page) {
            return static::normalize($overrides);
        }

        $seoMeta = method_exists($page, 'getSeoMeta') ? $page->getSeoMeta() : [];

        $meta = [
            'title' => $seoMeta['seo_title'] ?? $page->name,
            'description' => $seoMeta['seo_description'] ?? null,
            'image' => $seoMeta['seo_image'] ?? null,
            'index' => $seoMeta['index'] ?? 'index',
            'url' => page_permalink($page),
            'type' => 'article',
        ];

        return static::normalize(array_merge($meta, $overrides));
    }

    public static function forAuthor(?User $author, array $overrides = []): array
    {
        if (! $author) {
            return static::normalize($overrides);
        }

        $meta = [
            'title' => $author->name,
            'description' => __('Latest posts published by :name', ['name' => $author->name]),
            'url' => route('authors.show', $author),
            'type' => 'profile',
        ];

        return static::normalize(array_merge($meta, $overrides));
    }

    /**
     * Merge provided meta with defaults and normalize.
     */
    protected static function normalize(array $meta): array
    {
        $defaults = static::defaults();
        $overrides = array_filter($meta, fn ($value) => ! is_null($value));

        $merged = array_merge($defaults, $overrides);
        $merged['title'] = trim((string) ($merged['title'] ?? $defaults['title']));
        $merged['description'] = trim((string) ($merged['description'] ?? '')) ?: ($defaults['description'] ?? '');

        $merged['url'] = $merged['url'] ?? url()->current();
        $merged['canonical'] = $merged['canonical'] ?? $merged['url'];
        $merged['image'] = static::absoluteUrl($merged['image'] ?? $defaults['image']);
        $merged['type'] = $merged['type'] ?? 'website';
        $merged['site_name'] = $merged['site_name'] ?? setting('site_title', config('app.name'));

        $indexSetting = setting('seo_indexing', true) ? 'index' : 'noindex';
        $index = Str::lower((string) ($merged['index'] ?? $indexSetting));
        if ($indexSetting === 'noindex') {
            $index = 'noindex';
        }

        $merged['index'] = $index;
        $merged['robots'] = $index === 'noindex' ? 'noindex,follow' : 'index,follow';
        $merged['twitter_card'] = $merged['twitter_card'] ?? ($merged['image'] ? 'summary_large_image' : 'summary');

        return $merged;
    }

    protected static function defaults(): array
    {
        return [
            'title' => setting('seo_default_title', setting('site_title', config('app.name'))),
            'description' => setting('seo_default_description') ?? setting('site_description'),
            'image' => static::absoluteUrl(setting('seo_default_og_image')),
            'index' => setting('seo_indexing', true) ? 'index' : 'noindex',
            'type' => 'website',
            'site_name' => setting('site_title', config('app.name')),
            'url' => url()->current(),
        ];
    }

    protected static function absoluteUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        return asset($path);
    }
}
