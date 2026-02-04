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
    public static function fromArray(array $meta = []): array
    {
        return static::normalize($meta);
    }

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
        if (! $post) return static::normalize($overrides);

        $seoMeta = method_exists($post, 'getSeoMeta') ? $post->getSeoMeta() : [];

        $meta = [
            'title' => $seoMeta['seo_title'] ?? $post->name,
            'description' => $seoMeta['seo_description'] ?? $post->excerpt,
            'image' => $seoMeta['seo_image'] ?? $post->image_url ?? null,
            'index' => $seoMeta['index'] ?? 'index',
            'url' => post_permalink($post),
            'type' => 'article',
            'author' => $post->author->name ?? null,
            'published_time' => optional($post->created_at)->toIso8601String(),
            'modified_time' => optional($post->updated_at)->toIso8601String(),
        ];

        return static::normalize(array_merge($meta, $overrides));
    }

    public static function forCategory(?Category $category, array $overrides = []): array
    {
        if (! $category) return static::normalize($overrides);

        $seoMeta = method_exists($category, 'getSeoMeta') ? $category->getSeoMeta() : [];

        $meta = [
            'title' => $seoMeta['seo_title'] ?? $category->name,
            'description' => $seoMeta['seo_description'] ?? $category->description,
            'image' => $seoMeta['seo_image'] ?? $category->image ?? null,
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

        // টাইটেল ফরম্যাটিং
        $merged['site_name'] = $merged['site_name'] ?? setting('site_title', config('app.name'));
        $baseTitle = trim((string) ($merged['title'] ?? $defaults['title']));

        if ($merged['site_name'] && $baseTitle !== $merged['site_name'] && ! Str::contains($baseTitle, $merged['site_name'])) {
            $merged['title'] = "{$baseTitle} | {$merged['site_name']}";
        } else {
            $merged['title'] = $baseTitle;
        }

        // ডেসক্রিপশন লিমিট (SEO Best Practice)
        $merged['description'] = Str::limit(trim((string) ($merged['description'] ?? '')), 160);

        $merged['url'] = $merged['url'] ?? url()->current();
        $merged['canonical'] = $merged['canonical'] ?? $merged['url'];
        $merged['image'] = static::absoluteUrl($merged['image'] ?? $defaults['image']);

        // ইনডেক্সিং লজিক
        $indexSetting = setting('seo_indexing', true) ? 'index' : 'noindex';
        $index = Str::lower((string) ($merged['index'] ?? $indexSetting));
        $merged['robots'] = ($indexSetting === 'noindex' || $index === 'noindex') ? 'noindex,follow' : 'index,follow';

        $merged['twitter_card'] = $merged['twitter_card'] ?? ($merged['image'] ? 'summary_large_image' : 'summary');

        // Schema.org JSON-LD জেনারেশন
        $merged['schema'] = static::generateSchema($merged);

        return $merged;
    }

    protected static function generateSchema(array $meta): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $meta['type'] === 'article' ? 'BlogPosting' : 'WebPage',
            'headline' => $meta['title'],
            'description' => $meta['description'],
            'url' => $meta['url'],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $meta['url']],
        ];

        if (!empty($meta['image'])) {
            $schema['image'] = ['@type' => 'ImageObject', 'url' => $meta['image']];
        }

        if (!empty($meta['published_time'])) {
            $schema['datePublished'] = $meta['published_time'];
            $schema['dateModified'] = $meta['modified_time'] ?? $meta['published_time'];
        }

        if (!empty($meta['author'])) {
            $schema['author'] = ['@type' => 'Person', 'name' => $meta['author']];
        }

        return $schema;
    }

    protected static function defaults(): array
    {
        return [
            'title' => setting('seo_default_title', setting('site_title', config('app.name'))),
            'description' => setting('seo_default_description') ?? setting('site_description'),
            'keywords' => setting('site_keywords', ''),
            'image' => static::absoluteUrl(setting('seo_default_og_image')),
            'type' => 'website',
            'site_name' => setting('site_title', config('app.name')),
        ];
    }

    protected static function absoluteUrl(?string $path): ?string
    {
        if (!$path) return null;
        return Str::startsWith($path, ['http://', 'https://', '//']) ? $path : asset($path);
    }
}
