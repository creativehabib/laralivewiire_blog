<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TagResource;
use App\Models\Admin\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::query()
            ->withCount('posts')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.trim((string) $request->string('search')).'%'))
            ->latest('id')
            ->paginate(max(1, min(100, (int) $request->integer('per_page', 20))))
            ->withQueryString();

        return TagResource::collection($tags);
    }

    public function show(string $slug)
    {
        $tag = Tag::query()
            ->withCount('posts')
            ->where('id', $slug)
            ->orWhereHas('slugRecord', fn ($q) => $q->where('key', $slug))
            ->firstOrFail();

        return TagResource::make($tag);
    }
}
