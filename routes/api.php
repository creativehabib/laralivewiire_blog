<?php

use App\Http\Controllers\Api\V1\AppSettingsController;
use App\Http\Controllers\Api\V1\Auth\TokenController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\SiteMapController;
use App\Http\Controllers\Api\V1\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::post('/auth/token', [TokenController::class, 'store'])->name('auth.token.store')->middleware('throttle:30,1');
    Route::delete('/auth/token', [TokenController::class, 'destroy'])->name('auth.token.destroy')->middleware('auth.api');

    Route::middleware('auth.api')->group(function (): void {
        Route::get('/', SiteMapController::class)->name('index');

        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/category-by-posts', [PostController::class, 'categoryByPosts'])->name('posts.category-by-posts');
        Route::get('/posts/last-modify-posts', [PostController::class, 'lastModifyPosts'])->name('posts.last-modify-posts');
        Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

        Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
        Route::get('/tags/{slug}', [TagController::class, 'show'])->name('tags.show');

        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

        Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
        Route::get('/app-settings', AppSettingsController::class)->name('app-settings');
        Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
    });
});
