<?php

namespace App\Support;

class CommentConfig
{
    public static function get(): array
    {
        $mode = (string) setting('comment_system', 'default');
        $facebookEnabled = (bool) setting('comment_facebook_enabled', false);
        $facebookAppId = trim((string) (setting('comment_facebook_app_id') ?? ''));

        $facebookReady = $facebookEnabled && $facebookAppId !== '';

        $provider = match ($mode) {
            'facebook' => $facebookReady ? 'facebook' : 'local',
            'both' => $facebookReady ? 'both' : 'local',
            default => 'local',
        };

        return [
            'mode' => $mode,
            'provider' => $provider,
            'facebook' => [
                'enabled' => $facebookReady,
                'app_id' => $facebookReady ? $facebookAppId : null,
            ],
        ];
    }

    public static function facebookSdkUrl(?array $config = null, ?string $locale = null): ?string
    {
        $config ??= self::get();

        if (! data_get($config, 'facebook.enabled')) {
            return null;
        }

        $appId = data_get($config, 'facebook.app_id');

        if (! $appId) {
            return null;
        }

        $locale = $locale ?: config('app.locale', 'en_US');
        $locale = str_replace('-', '_', $locale);

        if (! str_contains($locale, '_')) {
            $locale = $locale === 'en'
                ? 'en_US'
                : $locale . '_' . strtoupper($locale);
        }

        return "https://connect.facebook.net/{$locale}/sdk.js#xfbml=1&version=v18.0&appId=" . urlencode($appId);
    }
}
