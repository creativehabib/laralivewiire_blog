<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tag;
use App\Models\Admin\Page;
use App\Models\Category;
use App\Models\Post;
use App\Support\CacheSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index(): Response
    {
        if (! $this->isSitemapEnabled()) abort(404);

        $itemsPerPage = $this->itemsPerPage();
        $postTypes = $this->postTypes();

        // Cache key generation
        $cacheKey = 'sitemap_index_'.$itemsPerPage.'_'.md5(json_encode($postTypes));

        $content = $this->remember($cacheKey, function () use ($itemsPerPage, $postTypes) {
            $includePosts = in_array('post', $postTypes);
            $includeCategories = in_array('category', $postTypes);
            $includePages = in_array('page', $postTypes);
            $includeTags = in_array('tag', $postTypes);

            $postGroups = collect();

            if ($includePosts) {
                $postGroups = Post::query()->published()
                    ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(id) as total_posts'))
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')
                    ->get()
                    ->map(function ($group) use ($itemsPerPage) {
                        $group->pages = max(1, (int) ceil(($group->total_posts ?? 0) / $itemsPerPage));
                        return $group;
                    });
            }

            return view('frontend.sitemap-index', [
                'postGroups' => $postGroups,
                'categoryLastUpdated' => $includeCategories ? Category::max('updated_at') : null,
                'pageLastUpdated' => $includePages ? Page::max('updated_at') : null,
                'tagLastUpdated' => $includeTags ? Tag::where('status', 'published')->max('updated_at') : null,
                'includePosts' => $includePosts,
                'includeCategories' => $includeCategories,
                'includePages' => $includePages,
                'includeTags' => $includeTags,
            ])->render();
        });

        return response($content)->header('Content-Type', 'application/xml');
    }

    public function tags(): Response
    {
        if (! $this->isSitemapEnabled() || ! in_array('tag', $this->postTypes())) abort(404);

        $config = $this->getConfigFor('tag');
        $cacheKey = 'sitemap_tags_'.md5(json_encode($config));

        $content = $this->remember($cacheKey, function () use ($config) {
            $tags = Tag::query()
                ->select('id', 'updated_at', 'created_at')
                ->with('slugRecord')
                ->where('status', 'published')
                ->orderByDesc('updated_at')
                ->get();

            return view('frontend.sitemap-tags', [
                'tags' => $tags,
                'changeFrequency' => $config['frequency'],
                'priority' => $config['priority'],
            ])->render();
        });

        return response($content)->header('Content-Type', 'application/xml');
    }

    public function posts(string $year, string $month, Request $request): Response
    {
        if (! $this->isSitemapEnabled() || ! in_array('post', $this->postTypes())) abort(404);

        $itemsPerPage = $this->itemsPerPage();
        $page = max(1, (int) $request->integer('page', 1));

        // Get Config for Posts
        $config = $this->getConfigFor('post');

        $cacheKey = "sitemap_posts_{$year}_{$month}_{$page}_".md5(json_encode($config));

        $content = $this->remember($cacheKey, function () use ($year, $month, $itemsPerPage, $page, $config) {
            $query = Post::query()->published()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderByDesc('updated_at');

            $totalPosts = (clone $query)->count();
            $posts = $query->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->get();

            if ($posts->isEmpty()) return null;

            return view('frontend.sitemap-posts', [
                'posts' => $posts,
                'changeFrequency' => $config['frequency'],
                'priority' => $config['priority'],
                'includeImages' => (bool) setting('sitemap_include_images', true),
            ])->render();
        });

        if (!$content) abort(404);
        return response($content)->header('Content-Type', 'application/xml');
    }

    public function categories(): Response
    {
        if (! $this->isSitemapEnabled() || ! in_array('category', $this->postTypes())) abort(404);

        $config = $this->getConfigFor('category');
        $cacheKey = 'sitemap_categories_'.md5(json_encode($config));

        $content = $this->remember($cacheKey, function () use ($config) {
            $categories = Category::query()
                ->select('id', 'updated_at')
                ->with('slugRecord')
                ->orderByDesc('updated_at')
                ->get();
            return view('frontend.sitemap-categories', [
                'categories' => $categories,
                'changeFrequency' => $config['frequency'],
                'priority' => $config['priority'],
            ])->render();
        });

        return response($content)->header('Content-Type', 'application/xml');
    }

    public function pages(): Response
    {
        if (! $this->isSitemapEnabled() || ! in_array('page', $this->postTypes())) abort(404);

        $config = $this->getConfigFor('page');
        $cacheKey = 'sitemap_pages_'.md5(json_encode($config));

        $content = $this->remember($cacheKey, function () use ($config) {

            // ১. স্ট্যাটিক পেজ (যেমন হোমপেজ) - কালেকশন হিসেবে তৈরি করুন
            $pages = collect([
                [
                    'url' => route('home'),
                    'lastmod' => now(),
                    'priority' => '1.0' // হোমপেজের প্রায়োরিটি ১.০
                ],
            ]);

            // ২. ডাটাবেজ পেজ
            $dbPages = Page::query()->published()->orderByDesc('updated_at')->get()
                ->map(function ($page) use ($config) {
                    return [
                        'url' => route('pages.show', ['page' => $page->slug]),
                        'lastmod' => $page->updated_at,
                        'priority' => $config['priority'] // ডাইনামিক সেটিংস থেকে প্রায়োরিটি
                    ];
                });

            // ৩. দুটি কালেকশন মার্জ করা হলো
            $allPages = $pages->merge($dbPages);

            return view('frontend.sitemap-pages', [
                'pages' => $allPages, // এখন ভিউ ফাইলে $pages পাওয়া যাবে
                'changeFrequency' => $config['frequency'],
            ])->render();
        });

        return response($content)->header('Content-Type', 'application/xml');
    }

    // --- Helpers (এখানেই ফিক্স করা হয়েছে) ---

    private function isSitemapEnabled(): bool { return (bool) setting('sitemap_enabled', true); }
    private function itemsPerPage(): int { return (int) setting('sitemap_items_per_page', 1000); }

    // ফিক্স ১: postTypes মেথডটি এখন Array এবং String দুটোই চেক করবে
    private function postTypes(): array {
        $types = setting('sitemap_post_types');

        if (is_array($types)) {
            return $types;
        }

        return is_string($types) ? json_decode($types, true) : ($types ?? ['post', 'page', 'category', 'tag']);
    }

    // ফিক্স ২: getConfigFor মেথডটিও একইভাবে চেক করবে
    private function getConfigFor(string $type): array {
        $rawSettings = setting('sitemap_type_settings');

        $allSettings = [];
        if (is_array($rawSettings)) {
            $allSettings = $rawSettings;
        } elseif (is_string($rawSettings)) {
            $allSettings = json_decode($rawSettings, true) ?? [];
        }

        return $allSettings[$type] ?? ['frequency' => 'daily', 'priority' => '0.5'];
    }

    private function remember(string $cacheKey, callable $callback)
    {
        $duration = $this->cacheDuration();
        if(! $duration) return $callback();
        return Cache::remember($cacheKey, $duration, $callback);
    }

    private function cacheDuration(): ?\DateTimeInterface
    {
        $minutes = CacheSettings::sitemapLifetimeMinutes();
        if( $minutes <= 0 ) return null;
        return now()->addMinutes($minutes);
    }
}
