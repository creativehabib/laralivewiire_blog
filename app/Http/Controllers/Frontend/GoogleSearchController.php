<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\Seo;
use Illuminate\Http\Request;

class GoogleSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        return view('frontend.google-search', [
            'query' => $query,
            'searchEngineId' => trim((string) setting('google_search_engine_id', '')),
            'seo' => Seo::forHomepage([
                'title' => $query !== '' ? "Search Results for: {$query}" : 'Search Results',
                'url' => url()->current() . ($query !== '' ? ('?q=' . urlencode($query)) : ''),
            ]),
        ]);
    }
}
