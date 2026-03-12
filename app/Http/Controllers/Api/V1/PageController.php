<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PageResource;
use App\Models\Admin\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::query()
            ->withCount('comments')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('id')
            ->paginate(max(1, min(100, (int) $request->integer('per_page', 20))))
            ->withQueryString();

        return PageResource::collection($pages);
    }

    public function show(string $slug)
    {
        $page = Page::query()
            ->withCount('comments')
            ->where('id', $slug)
            ->orWhereHas('slugRecord', fn ($q) => $q->where('key', $slug))
            ->firstOrFail();

        return PageResource::make($page);
    }
}
