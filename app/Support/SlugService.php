<?php

namespace App\Support;

use App\Models\Slug;

class SlugService
{
    /**
     * ইউনিক এবং বাংলা সাপোর্টসহ স্লাগ তৈরি।
     * 'Compilation failed' এরর স্থায়ীভাবে দূর করার জন্য কোডটি রি-রাইট করা হয়েছে।
     */
    public static function create(string $name, ?string $separator = '-', ?int $ignoreId = null): string
    {
        // সেপারেটর ডিফল্ট হিসেবে হাইফেন সেট করা
        $sep = $separator ?: '-';

        // ১. বড় হাতের অক্ষর ছোট করা (UTF-8 সাপোর্টসহ)
        $slug = mb_strtolower($name, 'UTF-8');

        // ২. বাংলা ক্যারেক্টার (\x{0980}-\x{09FF}), ইংরেজি (a-z) এবং সংখ্যা (0-9) বাদে বাকি সব সিম্বলকে
        // একটি নির্দিষ্ট অস্থায়ী চিহ্ন (যেমন স্পেস) দিয়ে রিপ্লেস করা।
        $slug = preg_replace('/[^\x{0980}-\x{09FF}a-z0-9]+/u', ' ', $slug);

        // ৩. এবার সব স্পেসকে আপনার দেওয়া সেপারেটর (হাইফেন) দিয়ে রিপ্লেস করা
        $slug = trim($slug);
        $slug = preg_replace('/\s+/', $sep, $slug);

        // ৪. যদি স্লাগ খালি থাকে (যেমন শুধু বিশেষ চিহ্ন ছিল), তবে টাইমস্ট্যাম্প ব্যবহার
        $base = $slug ?: (string) time();

        $candidate = $base;
        $counter = 1;

        // ৫. ডাটাবেসে ইউনিকনেস চেক
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
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists();
    }
}
