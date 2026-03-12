<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->withCount(['posts', 'children'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->boolean('featured_only'), fn ($q) => $q->where('is_featured', true))
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(max(1, min(100, (int) $request->integer('per_page', 20))))
            ->withQueryString();

        return CategoryResource::collection($categories);
    }

    public function show(string $slug)
    {
        $category = Category::query()
            ->withCount(['posts', 'children'])
            ->where('id', $slug)
            ->orWhereHas('slugRecord', fn ($q) => $q->where('key', $slug))
            ->firstOrFail();

        return CategoryResource::make($category);
    }
}
