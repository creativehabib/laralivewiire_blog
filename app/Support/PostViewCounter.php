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

        $parts = [
            $request->ip(),
            $request->userAgent(),
            $request->session()->getId(),
        ];

        return sha1(implode('|', $parts));
    }
}
