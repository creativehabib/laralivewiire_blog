<?php

namespace App\Providers;

use App\Models\Admin\Page;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use App\Support\CacheSettings;
use Illuminate\Support\Facades\Cache;
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
        $this->registerCacheResetHooks();
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
