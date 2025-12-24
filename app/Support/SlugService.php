<?php

namespace App\Support;

use App\Models\Slug;
use Illuminate\Support\Str;

class SlugService
{
    public static function create(string $name, ?string $prefix = '', ?int $ignoreId = null): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = (string) time();
        }

        $candidate = $base;
        $counter = 1;

        while (self::exists($candidate, $ignoreId)) {
            $candidate = $base . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }

    protected static function exists(string $slug, ?int $ignoreId = null): bool
    {
        return Slug::query()
            ->where('key', $slug)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists();
    }
}
