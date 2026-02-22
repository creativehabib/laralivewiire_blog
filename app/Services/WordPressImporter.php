<?php

namespace App\Services;

use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\Slug;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WordPressImporter
{
    public function import(string $sourceUrl, array $options = []): array
    {
        $sourceUrl = rtrim($sourceUrl, '/');
        $perPage = (int) ($options['per_page'] ?? 50);
        $maxPages = (int) ($options['max_pages'] ?? 0);
        $importMedia = (bool) ($options['import_media'] ?? true);
        $defaultStatus = (string) ($options['default_status'] ?? 'published');
        $authorId = $this->resolveAuthorId($options['author_id'] ?? null);

        $summary = [
            'categories' => 0,
            'tags' => 0,
            'posts' => 0,
            'media_downloaded' => 0,
        ];

        $categoryMap = $this->importCategories($sourceUrl, $perPage, $maxPages, $summary, $authorId);
        $tagMap = $this->importTags($sourceUrl, $perPage, $maxPages, $summary, $authorId);

        $posts = $this->fetchAll($sourceUrl, 'posts', [
            '_embed' => '1',
            'status' => 'publish',
            'orderby' => 'date',
            'order' => 'asc',
        ], $perPage, $maxPages);

        foreach ($posts as $wpPost) {
            $slug = (string) ($wpPost['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $post = $this->findModelBySlug(Post::class, $slug) ?: new Post();
            $post->name = html_entity_decode((string) Arr::get($wpPost, 'title.rendered', ''), ENT_QUOTES | ENT_HTML5);
            $post->description = Str::limit(strip_tags((string) Arr::get($wpPost, 'excerpt.rendered', '')), 380);
            $post->content = (string) Arr::get($wpPost, 'content.rendered', '');
            $post->status = (($wpPost['status'] ?? 'publish') === 'publish') ? $defaultStatus : (string) ($wpPost['status'] ?? $defaultStatus);
            $post->author_id = $authorId;
            $post->author_type = User::class;
            $post->slug = $slug;

            $featured = Arr::get($wpPost, '_embedded.wp:featuredmedia.0.source_url');
            if ($featured) {
                $post->image = $importMedia
                    ? ($this->importImage((string) $featured) ?? (string) $featured)
                    : (string) $featured;

                if ($importMedia && ! Str::startsWith((string) $post->image, ['http://', 'https://'])) {
                    $summary['media_downloaded']++;
                }
            }

            $post->save();
            $summary['posts']++;

            $categoryIds = collect($wpPost['categories'] ?? [])
                ->map(fn ($wpId) => $categoryMap[(int) $wpId] ?? null)
                ->filter()
                ->values()
                ->all();

            if (! empty($categoryIds)) {
                $post->categories()->sync($categoryIds);
            }

            $tagIds = collect($wpPost['tags'] ?? [])
                ->map(fn ($wpId) => $tagMap[(int) $wpId] ?? null)
                ->filter()
                ->values()
                ->all();

            if (! empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        }

        return $summary;
    }

    private function importCategories(string $sourceUrl, int $perPage, int $maxPages, array &$summary, ?int $authorId): array
    {
        $categories = $this->fetchAll($sourceUrl, 'categories', [], $perPage, $maxPages);

        $map = [];
        $parentMap = [];

        foreach ($categories as $wpCategory) {
            $slug = (string) ($wpCategory['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $category = $this->findModelBySlug(Category::class, $slug) ?: new Category();
            $category->name = html_entity_decode((string) ($wpCategory['name'] ?? ''), ENT_QUOTES | ENT_HTML5);
            $category->description = (string) ($wpCategory['description'] ?? '');
            $category->status = 'published';
            $category->author_id = $authorId;
            $category->author_type = User::class;
            $category->slug = $slug;
            $category->save();

            $wpId = (int) ($wpCategory['id'] ?? 0);
            $map[$wpId] = $category->id;
            $parentMap[$wpId] = (int) ($wpCategory['parent'] ?? 0);
            $summary['categories']++;
        }

        foreach ($parentMap as $wpId => $wpParentId) {
            if ($wpParentId > 0 && isset($map[$wpId], $map[$wpParentId])) {
                Category::whereKey($map[$wpId])->update(['parent_id' => $map[$wpParentId]]);
            }
        }

        return $map;
    }

    private function importTags(string $sourceUrl, int $perPage, int $maxPages, array &$summary, ?int $authorId): array
    {
        $tags = $this->fetchAll($sourceUrl, 'tags', [], $perPage, $maxPages);

        $map = [];

        foreach ($tags as $wpTag) {
            $slug = (string) ($wpTag['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $tag = $this->findModelBySlug(Tag::class, $slug) ?: new Tag();
            $tag->name = html_entity_decode((string) ($wpTag['name'] ?? ''), ENT_QUOTES | ENT_HTML5);
            $tag->description = (string) ($wpTag['description'] ?? '');
            $tag->status = 'published';
            $tag->author_id = $authorId;
            $tag->author_type = User::class;
            $tag->slug = $slug;
            $tag->save();

            $wpId = (int) ($wpTag['id'] ?? 0);
            $map[$wpId] = $tag->id;
            $summary['tags']++;
        }

        return $map;
    }

    private function fetchAll(string $sourceUrl, string $endpoint, array $query, int $perPage, int $maxPages): array
    {
        $page = 1;
        $items = [];

        do {
            $response = Http::timeout(30)->acceptJson()->get("{$sourceUrl}/wp-json/wp/v2/{$endpoint}", array_merge($query, [
                'per_page' => min(100, max(1, $perPage)),
                'page' => $page,
            ]));

            if (! $response->successful()) {
                break;
            }

            $data = $response->json();
            if (! is_array($data) || empty($data)) {
                break;
            }

            $items = array_merge($items, $data);
            $page++;
        } while (($maxPages === 0 || $page <= $maxPages));

        return $items;
    }

    private function importImage(string $url): ?string
    {
        $response = Http::timeout(60)->get($url);
        if (! $response->successful()) {
            return null;
        }

        $ext = pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION);
        $ext = $ext !== '' ? $ext : 'jpg';

        $fileName = 'imports/wordpress/'.now()->format('Y/m').'/'.Str::uuid().'.'.$ext;
        Storage::disk('public')->put($fileName, $response->body());

        return $fileName;
    }

    private function findModelBySlug(string $modelClass, string $slug): ?object
    {
        $slugRecord = Slug::query()
            ->where('reference_type', $modelClass)
            ->where('key', $slug)
            ->first();

        return $slugRecord?->reference;
    }

    private function resolveAuthorId($authorId): ?int
    {
        if ($authorId) {
            return (int) $authorId;
        }

        return User::query()->value('id');
    }
}
