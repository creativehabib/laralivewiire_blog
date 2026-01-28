<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SettingManager
{
    protected static array $runtime = [];

    public static function get(string $key, $default = null)
    {
        if (array_key_exists($key, self::$runtime)) {
            return self::$runtime[$key];
        }

        if (! is_installed()) {
            return $default;
        }

        if (! self::settingsTableAvailable()) {
            return $default;
        }

        $settings = Cache::rememberForever('settings.autoload', function () {
            return Setting::where('autoload', true)->pluck('value', 'key')->toArray();
        });

        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        $value = self::decode($settings[$key]);
        self::$runtime[$key] = $value;

        return $value;
    }

    public static function set(string $key, $value, string $group = 'general'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => self::encode($value),
                'group' => $group,
                'autoload' => true,
            ]
        );

        Cache::forget('settings.autoload');
        self::$runtime[$key] = $value;
    }

    public static function group(string $group): array
    {
        if (! is_installed()) {
            return [];
        }

        if (! self::settingsTableAvailable()) {
            return [];
        }

        return Setting::where('group', $group)
            ->pluck('value', 'key')
            ->map(fn ($v) => self::decode($v))
            ->toArray();
    }

    protected static function encode($value): ?string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }
        return $value;
    }

    protected static function decode(?string $value)
    {
        if ($value === null) return null;

        $json = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $json : $value;
    }

    private static function settingsTableAvailable(): bool
    {
        try {
            return Schema::hasTable('settings');
        } catch (Throwable $exception) {
            return false;
        }
    }

}
