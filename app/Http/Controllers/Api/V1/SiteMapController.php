<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class SiteMapController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'name' => config('app.name'),
            'version' => 'v1',
            'endpoints' => [
                'posts' => route('api.v1.posts.index'),
                'category_by_posts' => route('api.v1.posts.category-by-posts'),
                'last_modify_posts' => route('api.v1.posts.last-modify-posts'),
                'categories' => route('api.v1.categories.index'),
                'tags' => route('api.v1.tags.index'),
                'pages' => route('api.v1.pages.index'),
                'comments' => route('api.v1.comments.index'),
                'app_settings' => route('api.v1.app-settings'),
            ],
        ]);
    }
}
