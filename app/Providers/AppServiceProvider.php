<?php

namespace App\Providers;

use App\Models\Admin\Page;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use App\Observers\MediaFileObserver;
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

        $timezone = setting('timezone', config('app.timezone', 'UTC'));
        if (is_array($timezone)) {
            $timezone = $timezone[0] ?? null;
        }
        if (blank($timezone) || ! is_string($timezone)) {
            $timezone = config('app.timezone', 'UTC');
        }
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        $storageDisk = setting('storage_disk', config('filesystems.default'));

        config(['filesystems.default' => $storageDisk]);

        if ($storageDisk === 's3') {
            $s3Config = config('filesystems.disks.s3');
            $accessKey = setting('s3_access_key_id');
            $secretKey = setting('s3_secret_access_key');
            $region = setting('s3_region');
            $bucket = setting('s3_bucket');
            $url = setting('s3_url');
            $endpoint = setting('s3_endpoint');
            $usePathStyle = setting('s3_use_path_style_endpoint', $s3Config['use_path_style_endpoint'] ?? false);

            if ($accessKey) {
                $s3Config['key'] = $accessKey;
            }

            if ($secretKey) {
                $s3Config['secret'] = $secretKey;
            }

            if ($region) {
                $s3Config['region'] = $region;
            }

            if ($bucket) {
                $s3Config['bucket'] = $bucket;
            }

            if ($url) {
                $s3Config['url'] = $url;
            }

            if ($endpoint) {
                $s3Config['endpoint'] = $endpoint;
            }

            $s3Config['use_path_style_endpoint'] = (bool) $usePathStyle;

            config(['filesystems.disks.s3' => $s3Config]);
        }

        if (class_exists(\Habib\MediaManager\Models\MediaFile::class)) {
            \Habib\MediaManager\Models\MediaFile::observe(MediaFileObserver::class);
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
            abort_if(! $model, 404);
            return $model;
        });
        Route::bind('page', function (string $value) {
            $model = SlugHelper::resolveModel($value, Page::class);
            abort_if(! $model, 404);
            return $model;
        });
        Route::bind('tag', function (string $value) {
            $model = SlugHelper::resolveModel($value, \App\Models\Admin\Tag::class);
            abort_if(! $model, 404);
            return $model;
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
