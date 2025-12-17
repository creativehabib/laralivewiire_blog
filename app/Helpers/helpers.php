<?php

use App\Models\Admin\Page;
use App\Models\GeneralSetting;
use App\Models\Post;
use App\Models\Setting;
use App\Support\PermalinkManager;
use App\Support\SettingManager;
use App\Support\BanglaCalendar;
use App\Support\BanglaFormatter;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;

if (!function_exists('settings')) {
    function settings($key = null) {
        if ($key) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : null;
        }
        return Setting::pluck('value', 'key')->all();
    }
}

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

if (! function_exists('post_permalink')) {
    function post_permalink(Post $post, bool $absolute = true): string
    {
        return PermalinkManager::urlFor($post, $absolute);
    }
}

if (! function_exists('page_permalink')) {
    function page_permalink(Page $page, bool $absolute = true): string
    {
        return route('pages.show', ['page' => $page->slug], $absolute);
    }
}

if (!function_exists('general_settings')) {
    function general_settings($key = null)
    {
        // 1 day cache duration
        $duration = 60 * 60 * 24;

        $settings = Cache::remember('general_settings', $duration, function () {
            return GeneralSetting::first();
        });

        if ($key) {
            return $settings ? $settings->{$key} : null;
        }

        return $settings;
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
