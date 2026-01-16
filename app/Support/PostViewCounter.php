<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostViewCounter
{
    /**
     * Record a unique view for the given post using a visitor fingerprint.
     */
    public static function record(Post $post): bool
    {
        if (! static::shouldCount()) {
            return false;
        }

        $fingerprint = static::fingerprint();
        $cacheKey = sprintf('post:viewed:%s:%s', $post->getKey(), $fingerprint);

        if (! Cache::add($cacheKey, true, now()->addHours(6))) {
            return false;
        }

        $post->withoutTimestamps(function () use ($post): void {
            $post->increment('views');
        });

        Cache::forget('homepage:blocks');

        return true;
    }

    protected static function fingerprint(): string
    {
        $request = request();

        $sessionId = $request->hasSession() ? $request->session()->getId() : null;

        $parts = [
            optional($request->user())->getAuthIdentifier(),
            $request->ip(),
            $request->userAgent(),
            $sessionId,
        ];

        return sha1(implode('|', $parts));
    }

    protected static function shouldCount(): bool
    {
        $request = request();

        $userAgent = (string) $request->userAgent();

        if ($userAgent === '') {
            return false;
        }

        return ! preg_match('/bot|crawler|spider|crawling|slurp|bingpreview|facebookexternalhit/i', $userAgent);
    }
}
