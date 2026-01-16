<?php

use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use App\Support\PermalinkManager;
use App\Support\SettingManager;
use App\Support\BanglaCalendar;
use App\Support\BanglaFormatter;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('frontend_bangla_date')) {
    function frontend_bangla_date(?CarbonInterface $dateTime = null): string
    {
        $date = ($dateTime ? Carbon::parse($dateTime) : Carbon::now())
            ->setTimezone(setting('timezone', config('app.timezone', 'Asia/Dhaka')));

        $gregorianDate = BanglaFormatter::digits(
            $date
                ->copy()
                ->locale('bn')
                ->translatedFormat('l, d F Y')
        );

        $format = setting('date_display_format', 'gregorian_and_bangla');

        if ($format === 'gregorian_only') {
            return $gregorianDate;
        }

        $banglaCalendarDate = BanglaCalendar::format($date);

        return sprintf('%s, %s', $gregorianDate, $banglaCalendarDate);
    }
}

if (! function_exists('frontend_bangla_day')) {
    function frontend_bangla_day(?CarbonInterface $dateTime = null): string
    {
        $date = ($dateTime ? Carbon::parse($dateTime) : Carbon::now())
            ->setTimezone(setting('timezone', config('app.timezone', 'Asia/Dhaka')));

        return BanglaFormatter::digits($date->format('d'));
    }
}

if (! function_exists('post_permalink')) {
    function post_permalink(Post $post, bool $absolute = true): string
    {
        return PermalinkManager::urlFor($post, $absolute);
    }
}

if (! function_exists('the_thumbnail')) {
    function the_thumbnail($model = null, ?int $width = null, ?int $height = null, string $field = 'image'): string
    {
        $placeholder = 'https://placehold.co/800x450?text=News+Image';

        if ($model === null) {
            return $placeholder;
        }

        if ($model instanceof Post) {
            return $model->getImageUrl($width, $height);
        }

        if (is_string($model)) {
            return image_optimize_url($model, $width, $height);
        }

        if (is_object($model) && method_exists($model, 'getImageUrl')) {
            return image_optimize_url((string) $model->getImageUrl($width, $height), $width, $height);
        }

        $path = null;

        if (is_object($model) && isset($model->{$field})) {
            $path = $model->{$field};
        } elseif (is_array($model) && array_key_exists($field, $model)) {
            $path = $model[$field];
        }

        if (! $path) {
            return $placeholder;
        }

        if (Str::startsWith((string) $path, ['http://', 'https://'])) {
            $url = (string) $path;
        } elseif (Storage::disk('public')->exists((string) $path)) {
            $url = Storage::disk('public')->url((string) $path);
        } else {
            $url = asset('storage/'.ltrim((string) $path, '/'));
        }

        return image_optimize_url($url, $width, $height);
    }
}

if (! function_exists('the_author')) {
    function the_author($model = null, string $class = '', bool $navigate = true): string
    {
        if ($model instanceof User) {
            $author = $model;
        } elseif (is_object($model) && isset($model->author)) {
            $author = $model->author;
        } elseif (is_array($model) && array_key_exists('author', $model)) {
            $author = $model['author'];
        } else {
            $author = null;
        }

        if (! $author || ! $author->name) {
            return '';
        }

        $attributes = [];

        if ($class !== '') {
            $attributes[] = 'class="'.e($class).'"';
        }

        if ($navigate) {
            $attributes[] = 'wire:navigate';
        }

        $attributeString = $attributes ? ' '.implode(' ', $attributes) : '';

        return sprintf(
            '<a href="%s"%s>%s</a>',
            e(route('authors.show', $author)),
            $attributeString,
            e($author->name)
        );
    }
}

if (! function_exists('the_category')) {
    function the_category($model = null, string $separator = ', ', string $class = '', bool $navigate = true): string
    {
        $categories = null;

        if ($model instanceof Post) {
            $categories = $model->relationLoaded('categories')
                ? $model->categories
                : $model->categories()->get();
        } elseif (is_object($model) && isset($model->categories)) {
            $categories = $model->categories;
        } elseif (is_array($model) && array_key_exists('categories', $model)) {
            $categories = $model['categories'];
        }

        if (! $categories) {
            return '';
        }

        $categoryLinks = [];
        $attributes = [];

        if ($class !== '') {
            $attributes[] = 'class="'.e($class).'"';
        }

        if ($navigate) {
            $attributes[] = 'wire:navigate';
        }

        $attributeString = $attributes ? ' '.implode(' ', $attributes) : '';

        foreach ($categories as $category) {
            if (! $category instanceof Category) {
                continue;
            }

            $slug = $category->slug ?? $category->slugRecord?->slug;

            if (! $slug) {
                continue;
            }

            $categoryLinks[] = sprintf(
                '<a href="%s"%s>%s</a>',
                e(route('categories.show', ['category' => $slug])),
                $attributeString,
                e($category->name)
            );
        }

        return implode($separator, $categoryLinks);
    }
}

if (! function_exists('image_optimize_url')) {
    function image_optimize_url(string $url, ?int $width = null, ?int $height = null): string
    {
        if (! setting('image_optimize_enabled', false)) {
            return $url;
        }

        $params = [];
        $defaultQuery = trim((string) setting('image_optimize_query', ''));

        if ($defaultQuery !== '') {
            $defaultQuery = ltrim($defaultQuery, '?');
            if ($defaultQuery !== '') {
                $params[] = $defaultQuery;
            }
        }

        if ($width) {
            $params[] = 'w='.$width;
        }

        if ($height) {
            $params[] = 'h='.$height;
        }

        if ($params === []) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.implode('&', $params);
    }
}

if (! function_exists('page_permalink')) {
    function page_permalink(Page $page, bool $absolute = true): string
    {
        return route('pages.show', ['page' => $page->slug], $absolute);
    }
}
if (! function_exists('tag_permalink')) {
    function tag_permalink(Tag $tag, bool $absolute = true): string
    {
        return route('tags.show', ['tag' => $tag->slug], $absolute);
    }
}
if (! function_exists('preview_url')) {
    function preview_url(string $type, ?string $slug = null): string
    {
        return PermalinkManager::preview($type, $slug);
    }
}

if (! function_exists('setting')) {
    function setting(?string $key = null, $default = null)
    {
        if($key === null) {
            return SettingManager::class;
        }
        return SettingManager::get($key, $default);
    }
}

if(! function_exists('set_setting')) {
    function set_setting(string $key, $value, string $group = 'general'): void
    {
        SettingManager::set($key, $value, $group);
    }
}
