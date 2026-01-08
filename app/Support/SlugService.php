<?php

namespace App\Support;

use App\Models\Slug;

class SlugService
{
    public static function create(string $name, ?string $prefix = '', ?int $ignoreId = null): string
    {
        // ১. সরাসরি বাংলা ক্যারেক্টার ও স্পেস ছাড়া সব রিমুভ করুন
        // স্পেসকে হাইফেন দিয়ে পরিবর্তন
        $slug = mb_strtolower($name, 'UTF-8');
        $slug = preg_replace('/[^\x{0980}-\x{09FF}a-z0-9\s]/u', '', $slug); // বাংলা ও ইংরেজি বাদে সব বাদ
        $slug = preg_replace('/\s+/u', '-', trim($slug)); // স্পেসকে হাইফেন করা
        $slug = preg_replace('/-{2,}/u', '-', $slug); // ডাবল হাইফেন রিমুভ

        $base = $slug ?: (string) time();

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
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }
}
