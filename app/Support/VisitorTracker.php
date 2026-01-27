<?php

namespace App\Support;

use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VisitorTracker
{
    /**
     * Record a visit once per day for the same visitor fingerprint.
     */
    public static function record(Request $request): void
    {
        if (! file_exists(storage_path('installed'))) {
            return;
        }

        if (! $request->isMethod('GET')) {
            return;
        }

        if (str_starts_with($request->path(), 'admin')) {
            return;
        }

        $fingerprint = static::fingerprint($request);
        $cacheKey = sprintf('visitor:seen:%s', $fingerprint);

        if (! Cache::add($cacheKey, true, now()->addDay())) {
            return;
        }

        $userAgent = $request->userAgent();

        VisitorLog::create([
            'ip_address' => $request->ip(),
            'country' => static::resolveCountry($request),
            'browser' => static::resolveBrowser($userAgent),
            'device' => static::resolveDevice($userAgent),
            'user_agent' => $userAgent,
            'visited_at' => now(),
        ]);
    }

    protected static function fingerprint(Request $request): string
    {
        $parts = [
            $request->ip(),
            $request->userAgent(),
            Carbon::now()->format('Y-m-d'),
        ];

        return sha1(implode('|', $parts));
    }

    protected static function resolveCountry(Request $request): string
    {
        return $request->header('CF-IPCountry')
            ?? $request->header('X-Country-Code')
            ?? 'Unknown';
    }

    protected static function resolveBrowser(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        $map = [
            'Edg' => 'Edge',
            'Opera' => 'Opera',
            'OPR' => 'Opera',
            'Firefox' => 'Firefox',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
        ];

        foreach ($map as $needle => $label) {
            if (Str::contains($userAgent, $needle)) {
                return $label;
            }
        }

        return 'Other';
    }

    protected static function resolveDevice(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        $ua = Str::lower($userAgent);

        if (Str::contains($ua, ['ipad', 'tablet'])) {
            return 'Tablet';
        }

        if (Str::contains($ua, ['mobi', 'iphone', 'android'])) {
            return 'Mobile';
        }

        return 'Desktop';
    }
}
