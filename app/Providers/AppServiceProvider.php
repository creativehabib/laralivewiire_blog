<?php

namespace App\Providers;

use App\Models\Admin\Page;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use App\Support\CacheSettings;
use App\Support\SlugHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL; // ১. URL ফাসাদটি যোগ করা হয়েছে
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ২. প্রোডাকশন এনভায়রনমেন্টে HTTPS ফোর্স করার কোড
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $this->registerSlugBindings();
        $this->registerCacheResetHooks();
    }

    protected function registerSlugBindings(): void
    {
        Route::bind('post', function (string $value) {
            $model = SlugHelper::resolveModel($value, Post::class);
            abort_if(! $model, 404);
            return $model;
        });
        Route::bind('category', function (string $value) {
            $model = SlugHelper::resolveModel($value, Category::class);
            return $model ?? $value;
        });
        Route::bind('page', function (string $value) {
            $model = SlugHelper::resolveModel($value, Page::class);
            return $model ?? $value;
        });
        Route::bind('tag', function (string $value) {
            $model = SlugHelper::resolveModel($value, \App\Models\Admin\Tag::class);
            return $model ?? $value;
        });
    }

    protected function registerCacheResetHooks(): void
    {
        $models = [
            Post::class,
            Category::class,
            Page::class,
            Menu::class,
        ];
        foreach ($models as $model) {
            $model::saved(fn () => $this->flushCacheOnChange());
            $model::deleted(fn () => $this->flushCacheOnChange());

            if(method_exists($model, 'restored')) {
                $model::restored(fn () => $this->flushCacheOnChange());
            }
        }
    }

    protected function flushCacheOnChange(): void
    {
        if( ! CacheSettings::resetOnContentChange()){
            return;
        }
        Cache::flush();
    }
}
