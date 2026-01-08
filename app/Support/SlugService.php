<?php

namespace App\Support;

use App\Models\Slug;

class SlugService
{
    public static function create(string $name, ?string $separator = '-', ?int $ignoreId = null): string
    {
        // separator default + normalize
        $sep = $separator ?: '-';
        $sep = trim($sep);

        // Security/consistency: only allow 1-char safe separators typically used in URLs
        // (আপনি চাইলে এই লাইনটা শিথিল করতে পারেন)
        if ($sep === '' || mb_strlen($sep, 'UTF-8') > 1) {
            $sep = '-';
        }

        // lowercase
        $slug = mb_strtolower($name, 'UTF-8');

        // keep Bangla + English + digits, others -> space
        $slug = preg_replace('/[^\x{0980}-\x{09FF}a-z0-9]+/u', ' ', $slug);

        // trim and spaces -> separator (unicode-safe)
        $slug = trim($slug);
        $slug = preg_replace('/\s+/u', $sep, $slug);

        // collapse repeated separators and trim separators from ends
        $slug = preg_replace('/' . preg_quote($sep, '/') . '+/u', $sep, $slug);
        $slug = trim($slug, $sep);

        $base = $slug ?: (string) time();

        $candidate = $base;
        $counter = 1;

        while (self::exists($candidate, $ignoreId)) {
            $candidate = $base . $sep . $counter;
            $counter++;
        }

        return $candidate;
    }

    protected static function exists(string $slug, ?int $ignoreId = null): bool
    {
        return Slug::query()
            ->where('key', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }
}
