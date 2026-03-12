<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query()
            ->with(['author:id,name', 'categories', 'tags'])
            ->withCount('comments');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->string('search'));
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = (string) $request->string('category');
            $query->whereHas('categories', function ($q) use ($category): void {
                $q->where('categories.id', $category)
                    ->orWhereHas('slugRecord', fn ($slugQ) => $slugQ->where('key', $category));
            });
        }

        if ($request->filled('tag')) {
            $tag = (string) $request->string('tag');
            $query->whereHas('tags', function ($q) use ($tag): void {
                $q->where('tags.id', $tag)
                    ->orWhereHas('slugRecord', fn ($slugQ) => $slugQ->where('key', $tag));
            });
        }

        $posts = $query
            ->latest('id')
            ->paginate(max(1, min(100, (int) $request->integer('per_page', 15))))
            ->withQueryString();

        return PostResource::collection($posts);
    }

    public function show(string $slug)
    {
        $post = Post::query()
            ->with(['author:id,name', 'categories', 'tags'])
            ->withCount('comments')
            ->where('id', $slug)
            ->orWhereHas('slugRecord', fn ($q) => $q->where('key', $slug))
            ->firstOrFail();

        return PostResource::make($post);
    }
}
