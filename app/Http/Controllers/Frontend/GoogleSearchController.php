<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\Seo;
use Illuminate\Http\Request;

class GoogleSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $latestPosts = Post::query()
            ->published()
            ->latest('created_at')
            ->limit(6)
            ->get();

        $popularPosts = Post::query()
            ->published()
            ->orderByDesc('views')
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('frontend.google-search', [
            'query' => $query,
            'searchEngineId' => trim((string) setting('google_search_engine_id', '')),
            'latestPosts' => $latestPosts,
            'popularPosts' => $popularPosts,
            'seo' => Seo::forHomepage([
                'title' => $query !== '' ? "Search Results for: {$query}" : 'Search Results',
                'url' => url()->current() . ($query !== '' ? ('?q=' . urlencode($query)) : ''),
            ]),
        ]);
    }
}
