<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
        $settings = general_settings();

        if (! ($settings?->sitemap_enabled ?? true)) {
            abort(404);
        }

        // Caching শুরু
        $content = Cache::remember('sitemap_index', $this->cacheTime, function () use ($settings) {
            $itemsPerPage = max(1, (int) ($settings?->sitemap_items_per_page ?? 1000));

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

            // ক্যাটাগরি লাস্ট আপডেট চেক
            $categoryLastUpdated = Category::max('updated_at');

            // view render করে স্ট্রিং রিটার্ন করা হচ্ছে
            return view('frontend.sitemap-index', [
                'postGroups' => $postGroups,
                'categoryLastUpdated' => $categoryLastUpdated,
                'itemsPerPage' => $itemsPerPage,
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
        $settings = general_settings();

        if (! ($settings?->sitemap_enabled ?? true)) {
            abort(404);
        }

        $itemsPerPage = max(1, (int) ($settings?->sitemap_items_per_page ?? 1000));
        $page = max(1, (int) $request->integer('page', 1));

        // ইউনিক ক্যাশ কি
        $cacheKey = "sitemap_posts_{$year}_{$month}_page_{$page}";

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
        $settings = general_settings();

        if (! ($settings?->sitemap_enabled ?? true)) {
            abort(404);
        }

        $content = Cache::remember('sitemap_categories', $this->cacheTime, function () {
            $categories = Category::query()
                ->select('id', 'slug', 'updated_at')
                ->orderByDesc('updated_at')
                ->get();

            return view('frontend.sitemap-categories', [
                'categories' => $categories,
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
        $settings = general_settings();

        if (! ($settings?->sitemap_enabled ?? true)) {
            abort(404);
        }

        $content = Cache::remember('sitemap_pages', $this->cacheTime, function () {
            $pages = [
                ['url' => route('home'), 'lastmod' => now()->subDay()],
            ];

            // আপনার দেওয়া Route চেক লজিক
            if (Route::has('polls.index')) {
                $pages[] = ['url' => route('polls.index'), 'lastmod' => now()->subWeek()];
            }

            return view('frontend.sitemap-pages', [
                'pages' => $pages,
            ])->render();
        });

        return response($content)
            ->header('Content-Type', 'application/xml');
    }
}
