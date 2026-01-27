<?php

use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Support\PermalinkManager;
use App\Support\SettingManager;
use App\Support\BanglaCalendar;
use App\Support\BanglaFormatter;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('frontend_bangla_gregorian_date')) {
    function frontend_bangla_gregorian_date(?CarbonInterface $dateTime = null): string
    {
        $date = ($dateTime ? Carbon::parse($dateTime) : Carbon::now())
            ->setTimezone(setting('timezone', config('app.timezone', 'Asia/Dhaka')));

        return BanglaFormatter::digits(
            $date
                ->copy()
                ->locale('bn')
                ->translatedFormat('l, d F Y')
        );
    }
}

if (! function_exists('frontend_bangla_calendar_date')) {
    function frontend_bangla_calendar_date(?CarbonInterface $dateTime = null): string
    {
        $date = ($dateTime ? Carbon::parse($dateTime) : Carbon::now())
            ->setTimezone(setting('timezone', config('app.timezone', 'Asia/Dhaka')));

        return BanglaCalendar::format($date);
    }
}

if (! function_exists('frontend_bangla_date')) {
    function frontend_bangla_date(?CarbonInterface $dateTime = null): string
    {
        $gregorianDate = frontend_bangla_gregorian_date($dateTime);

        $format = setting('date_display_format', 'gregorian_and_bangla');

        if ($format === 'gregorian_only') {
            return $gregorianDate;
        }

        $banglaCalendarDate = frontend_bangla_calendar_date($dateTime);

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

if (! function_exists('the_date')) {
    function the_date(
        $modelOrFormat = '',
        string $format = '',
        ?string $source = null
    ): string
    {
        $model = null;

        if (is_object($modelOrFormat) || is_array($modelOrFormat)) {
            $model = $modelOrFormat;
        }

        $dateValue = null;

        if (is_object($model)) {
            if ($source && isset($model->{$source})) {
                $dateValue = $model->{$source};
            } elseif (isset($model->published_at)) {
                $dateValue = $model->published_at;
            } elseif (isset($model->created_at)) {
                $dateValue = $model->created_at;
            } elseif (isset($model->updated_at)) {
                $dateValue = $model->updated_at;
            }
        } elseif (is_array($model)) {
            if ($source && array_key_exists($source, $model)) {
                $dateValue = $model[$source];
            } else {
                $dateValue = $model['published_at']
                    ?? $model['created_at']
                    ?? $model['updated_at']
                    ?? null;
            }
        }

        if (! $dateValue) {
            return '';
        }

        $date = Carbon::parse($dateValue)
            ->setTimezone(setting('timezone', config('app.timezone', 'Asia/Dhaka')));

        if ($model) {
            $format = $format !== '' ? $format : '';
        } else {
            $format = $modelOrFormat !== '' ? (string) $modelOrFormat : '';
        }

        $format = $format !== '' ? (string) $format : 'F j, Y';

        if (in_array($format, ['diff', 'diffForHumans', 'human'], true)) {
            $formattedDate = $date->diffForHumans();
        } else {
            $formattedDate = $date->translatedFormat($format);
        }
        return $formattedDate;
    }
}

if (! function_exists('post_permalink')) {
    function post_permalink(Post $post, bool $absolute = true): string
    {
        return PermalinkManager::urlFor($post, $absolute);
    }
}

if (! function_exists('the_view_count')) {
    function the_view_count($modelOrValue = null, string $suffix = ''): string
    {
        $views = 0;

        if (is_numeric($modelOrValue)) {
            $views = (int) $modelOrValue;
        } elseif (is_object($modelOrValue) && isset($modelOrValue->views)) {
            $views = (int) $modelOrValue->views;
        } elseif (is_array($modelOrValue) && array_key_exists('views', $modelOrValue)) {
            $views = (int) $modelOrValue['views'];
        }

        $formatted = number_format(max(0, $views));

        if ($suffix === '') {
            return $formatted;
        }

        return trim($formatted.' '.$suffix);
    }
}

if (! function_exists('the_thumbnail')) {
    function the_thumbnail($model = null, ?int $width = null, ?int $height = null, string $field = 'image'): string
    {
        // ১. ডিফল্ট প্লেসহোল্ডার (কনফিগ থেকে নেওয়া ভালো, না থাকলে হার্ডকোড)
        $placeholder = setting('default_placeholder_image') ?? 'https://placehold.co/800x450?text=No+Image';

        // ২. যদি ইনপুট একদম ফাঁকা হয়
        if (blank($model)) {
            return $placeholder;
        }

        // ৩. যদি সরাসরি মডেলের মেথড থাকে (Post বা অন্য মডেল)
        // 'instanceof Post' চেক করার দরকার নেই, মেথড থাকলেই কল হবে। এতে কোড রিইউজেবল হয়।
        if (is_object($model) && method_exists($model, 'getImageUrl')) {
            return $model->getImageUrl($width, $height);
        }

        // ৪. যদি ইনপুট স্ট্রিং হয় (সরাসরি পাথ বা URL)
        if (is_string($model)) {
            $path = $model;
        } else {
            // ৫. অবজেক্ট বা অ্যারে থেকে ডেটা বের করা (data_get লারাভেলের পাওয়ারফুল হেল্পার)
            $path = data_get($model, $field);
        }

        // ৬. পাথ যদি না পাওয়া যায়
        if (blank($path)) {
            return $placeholder;
        }

        // ৭. URL জেনারেশন লজিক
        if (Str::startsWith((string) $path, ['http://', 'https://', '//'])) {
            $url = (string) $path;
        } else {
            // পারফরম্যান্স টিপস: Storage::exists() চেক বাদ দেওয়া হয়েছে।
            // কারণ প্রতিটি ইমেজ লোডে ডিস্ক চেক করলে সাইট স্লো হয়ে যায়।
            // সরাসরি URL জেনারেট করা অনেক ফাস্ট।
            $url = Storage::disk('public')->url(ltrim((string) $path, '/'));
        }

        // ৮. ইমেজ অপ্টিমাইজেশন (যদি ফাংশনটি থাকে)
        if (function_exists('image_optimize_url')) {
            return image_optimize_url($url, $width, $height);
        }

        return $url;
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

if (! function_exists('get_the_category_list')) {
    function get_the_category_list(
        $categories = null,
        string $separator = ', ',
        bool $showCount = false,
        string $class = '',
        bool $navigate = true,
        bool $asList = false,
        string $listClass = '',
        string $itemClass = ''
    ): string {
        if ($categories instanceof Category) {
            $categories = collect([$categories]);
        } elseif ($categories === null) {
            $query = Category::query()->orderBy('name');

            if ($showCount) {
                $query->withCount(['posts' => fn ($query) => $query->published()]);
            }

            $categories = $query->get();
        }

        if (! $categories || ! is_iterable($categories)) {
            return '';
        }

        if ($showCount && $categories instanceof \Illuminate\Database\Eloquent\Collection) {
            $categories->loadCount(['posts' => fn ($query) => $query->published()]);
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
        $itemAttributeString = $itemClass !== '' ? ' class="'.e($itemClass).'"' : '';

        foreach ($categories as $category) {
            if (! $category instanceof Category) {
                continue;
            }

            $slug = $category->slug ?? $category->slugRecord?->slug;

            if (! $slug) {
                continue;
            }

            $label = $category->name;

            if ($showCount) {
                $count = (int) ($category->posts_count ?? 0);
                $label .= ' ('.number_format(max(0, $count)).')';
            }

            $categoryLinks[] = sprintf(
                '<a href="%s"%s>%s</a>',
                e(route('categories.show', ['category' => $slug])),
                $attributeString,
                e($label)
            );
        }

        if ($asList) {
            $listAttributeString = $listClass !== '' ? ' class="'.e($listClass).'"' : '';
            $items = array_map(
                fn ($link) => '<li'.$itemAttributeString.'>'.$link.'</li>',
                $categoryLinks
            );

            return sprintf('<ul%s>%s</ul>', $listAttributeString, implode('', $items));
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

if (! function_exists('is_installed')) {
    function is_installed(): bool
    {
        return file_exists(storage_path('installed'));
    }
}

if(! function_exists('set_setting')) {
    function set_setting(string $key, $value, string $group = 'general'): void
    {
        SettingManager::set($key, $value, $group);
    }
}
