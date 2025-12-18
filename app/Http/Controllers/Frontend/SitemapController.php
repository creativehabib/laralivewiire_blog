<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    // ক্যাশ টাইম নির্ধারণ (যেমন: ৬০ মিনিট)
    private int $cacheTime = 60 * 60;

    /**
     * sitemap.xml (মূল ইনডেক্স ফাইল) দেখান।
     */
    public function index(): Response
    {
        if (! $this->isSitemapEnabled()) {
            abort(404);
        }

        $itemsPerPage = $this->itemsPerPage();
        $postTypes = $this->postTypes();
        $cacheKey = 'sitemap_index_'
            .$itemsPerPage.'_'
            .md5(json_encode([
                'postTypes' => $postTypes,
                'frequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
            ]));

        // Caching শুরু
        $content = Cache::remember($cacheKey, $this->cacheTime, function () use ($itemsPerPage, $postTypes) {

            $includePosts = in_array('post', $postTypes, true);
            $postGroups = collect();

            if ($includePosts) {
                $postGroups = Post::query()
                    ->published() // আপনার কোড অনুযায়ী published() স্কোপ
                    ->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('MAX(updated_at) as lastmod'),
                        DB::raw('COUNT(id) as total_posts')
                    )
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->get()
                    ->map(function ($group) use ($itemsPerPage) {
                        $group->pages = max(1, (int) ceil(($group->total_posts ?? 0) / $itemsPerPage));
                        return $group;
                    });
            }

            // ক্যাটাগরি লাস্ট আপডেট চেক
            $categoryLastUpdated = Category::max('updated_at');

            // view render করে স্ট্রিং রিটার্ন করা হচ্ছে
            return view('frontend.sitemap-index', [
                'postGroups' => $postGroups,
                'categoryLastUpdated' => $categoryLastUpdated,
                'itemsPerPage' => $itemsPerPage,
                'includePosts' => $includePosts,
                'includePages' => in_array('page', $postTypes, true),
                'changeFrequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
            ])->render();
        });

        return response($content)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * sitemap-posts-{year}-{month}.xml (পোস্টের লিস্ট) দেখান।
     */
    public function posts(string $year, string $month, Request $request): Response
    {
        if (! $this->isSitemapEnabled() || ! $this->shouldIncludePosts()) {
            abort(404);
        }

        $itemsPerPage = $this->itemsPerPage();
        $page = max(1, (int) $request->integer('page', 1));

        // ইউনিক ক্যাশ কি
        $cacheKey = 'sitemap_posts_'
            .$year.'_'.$month.'_page_'
            .$page.'_per_'
            .$itemsPerPage.'_'
            .md5(json_encode([
                'frequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
                'includeImages' => $this->includeImages(),
            ]));

        $content = Cache::remember($cacheKey, $this->cacheTime, function () use ($year, $month, $itemsPerPage, $page) {
            $offset = ($page - 1) * $itemsPerPage;

            $query = Post::query()
                ->published()
                ->with('categories:id,slug,name')
                ->select('id', 'name', 'slug', 'updated_at', 'created_at')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderByDesc('updated_at');

            $totalPosts = (clone $query)->count();

            $posts = $query
                ->skip($offset)
                ->take($itemsPerPage)
                ->get();

            if ($posts->isEmpty()) {
                return null;
            }

            return view('frontend.sitemap-posts', [
                'posts' => $posts,
                'currentPage' => $page,
                'totalPages' => max(1, (int) ceil($totalPosts / $itemsPerPage)),
                'itemsPerPage' => $itemsPerPage,
                'year' => $year,
                'month' => $month,
                'changeFrequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
                'includeImages' => $this->includeImages(),
            ])->render();
        });

        if (!$content) {
            abort(404);
        }

        return response($content)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * sitemap-categories.xml (ক্যাটাগরির লিস্ট) দেখান।
     */
    public function categories(): Response
    {
        if (! $this->isSitemapEnabled() || ! $this->shouldIncludePosts()) {
            abort(404);
        }

        $cacheKey = 'sitemap_categories_'
            .md5(json_encode([
                'frequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
            ]));

        $content = Cache::remember($cacheKey, $this->cacheTime, function () {
            $categories = Category::query()
                ->select('id', 'slug', 'updated_at')
                ->orderByDesc('updated_at')
                ->get();

            return view('frontend.sitemap-categories', [
                'categories' => $categories,
                'changeFrequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
            ])->render();
        });

        return response($content)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * sitemap-pages.xml (স্ট্যাটিক পেইজের লিস্ট) দেখান।
     */
    public function pages(): Response
    {
        if (! $this->isSitemapEnabled() || ! $this->shouldIncludePages()) {
            abort(404);
        }

        $cacheKey = 'sitemap_pages_'
            .md5(json_encode([
                'frequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
            ]));

        $content = Cache::remember($cacheKey, $this->cacheTime, function () {
            $pages = [
                ['url' => route('home'), 'lastmod' => now()->subDay()],
            ];

            $pages = Page::query()
                ->published()
                ->orderByDesc('updated_at')
                ->get()
                ->map(function (Page $page) {
                    return [
                        'url' => route('pages.show', $page),
                        'lastmod' => $page->updated_at ?? now(),
                    ];
                })
                ->values()
                ->all();

            array_unshift($pages, ['url' => route('home'), 'lastmod' => now()->subDay()]);

            if (Route::has('polls.index')) {
                $pages[] = ['url' => route('polls.index'), 'lastmod' => now()->subWeek()];
            }

            return view('frontend.sitemap-pages', [
                'pages' => $pages,
                'changeFrequency' => $this->changeFrequency(),
                'priority' => $this->priority(),
            ])->render();
        });

        return response($content)
            ->header('Content-Type', 'application/xml');
    }

    protected function isSitemapEnabled(): bool
    {
        return (bool) setting('sitemap_enabled', true);
    }

    protected function itemsPerPage(): int
    {
        return max(1, (int) setting('sitemap_items_per_page', 1000));
    }

    protected function postTypes(): array
    {
        $types = setting('sitemap_post_types', ['post', 'page']);

        if (is_string($types)) {
            $decoded = json_decode($types, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return is_array($types) ? $types : [];
    }

    protected function changeFrequency(): string
    {
        return (string) setting('sitemap_frequency', 'daily');
    }

    protected function priority(): string
    {
        return (string) setting('sitemap_priority', '0.8');
    }

    protected function includeImages(): bool
    {
        return (bool) setting('sitemap_include_images', true);
    }

    protected function shouldIncludePosts(): bool
    {
        return in_array('post', $this->postTypes(), true);
    }

    protected function shouldIncludePages(): bool
    {
        return in_array('page', $this->postTypes(), true);
    }
}
