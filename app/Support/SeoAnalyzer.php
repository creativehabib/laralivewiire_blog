<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class SeoAnalyzer
{
    /**
     * Yoast-style SEO analysis for Bengali and English.
     */
    public static function analyze(
        Model $model,
        ?string $focusKeyword = null,
        ?array $overrideMeta = null,
        array $options = []
    ): array {
        $meta = $overrideMeta ?? (method_exists($model, 'getMeta')
            ? ($model->getMeta('seo_meta')[0] ?? [])
            : []);

        $focusKeyword ??= $meta['focus_keyword'] ?? null;

        return static::analyzeContent(
            title: $meta['seo_title'] ?? $model->name ?? $model->title ?? '',
            description: $meta['seo_description'] ?? $model->description ?? '',
            slug: $model->slug ?? '',
            contentHtml: (string) ($model->content ?? ''),
            focusKeyword: $focusKeyword,
            options: $options
        );
    }

    /**
     * Analyze raw content with multi-byte (UTF-8) support.
     */
    public static function analyzeContent(
        string $title,
        string $description,
        string $slug,
        string $contentHtml,
        ?string $focusKeyword = null,
        array $options = []
    ): array {
        $analysis = [
            'score'              => 0,
            'title_ok'           => false,
            'title_sentiment_ok' => false,
            'title_power_ok'     => false,
            'desc_ok'            => false,
            'content_ok'         => false,
            'image_ok'           => false,
            'head_ok'            => false,
            'slug_ok'            => false,
            'links_ok'           => false,
            'kw_in_title'        => false,
            'kw_in_slug'         => false,
            'kw_in_desc'         => false,
            'kw_in_intro'        => false,
            'kw_in_head'         => false,
            'kw_in_alt'          => false,
            'kw_density_ok'      => false,
            'kw_density'         => 0,
            'focus_keyword'      => $focusKeyword,
        ];

        $config = array_replace_recursive(static::defaultConfig(), $options);

        $desc         = $description;
        $contentText  = trim(strip_tags($contentHtml));
        $focusKeyword = trim((string) $focusKeyword);
        $kwNorm       = mb_strtolower($focusKeyword, 'UTF-8');
        $titleLower   = mb_strtolower($title, 'UTF-8');

        // কিউওয়ার্ডকে স্লাগ ফরম্যাটে রূপান্তর (যেমন: 'NCTB Books' -> 'nctb-books')
        $kwSlugMatch  = preg_replace('/\s+/u', '-', $kwNorm);

        /*
         |--------------------------------------------------------------------------
         | BASE RULES (UTF-8 Compatible)
         |--------------------------------------------------------------------------
        */

        // Title length
        $len = mb_strlen($title, 'UTF-8');
        $titleRange = $config['lengths']['title'];
        if ($len >= $titleRange['min'] && $len <= $titleRange['max']) {
            $analysis['score'] += $config['weights']['title'];
            $analysis['title_ok'] = true;
        }

        // Sentiment word in title
        $sentimentWords = array_map(fn($w) => mb_strtolower($w, 'UTF-8'), array_merge(
            $config['sentiment_words']['positive'],
            $config['sentiment_words']['negative']
        ));
        foreach ($sentimentWords as $word) {
            if ($word !== '' && mb_strpos($titleLower, $word) !== false) {
                $analysis['score'] += $config['weights']['title_sentiment'];
                $analysis['title_sentiment_ok'] = true;
                break;
            }
        }

        // Power word in title
        foreach ($config['power_words'] as $word) {
            if ($word !== '' && mb_strpos($titleLower, mb_strtolower($word, 'UTF-8')) !== false) {
                $analysis['score'] += $config['weights']['title_power'];
                $analysis['title_power_ok'] = true;
                break;
            }
        }

        // Description length
        $descLen = mb_strlen($desc, 'UTF-8');
        $descRange = $config['lengths']['description'];
        if ($descLen >= $descRange['min'] && $descLen <= $descRange['max']) {
            $analysis['score'] += $config['weights']['description'];
            $analysis['desc_ok'] = true;
        }

        // Word count (Bengali supported)
        $words = max(1, count(preg_split('/\s+/u', $contentText, -1, PREG_SPLIT_NO_EMPTY)));
        if ($words >= $config['content']['min_words']) {
            $analysis['score'] += $config['weights']['content'];
            $analysis['content_ok'] = true;
        }

        // 1. Image Presence check
        if (preg_match('/<img\b[^>]*>/i', $contentHtml)) {
            $analysis['score'] += $config['weights']['image'];
            $analysis['image_ok'] = true;
        }

        // 2. Keyword in Image Alt check (NCTB Books সাপোর্টসহ)
        if ($kwNorm !== '' && preg_match_all('/<img\b[^>]*alt="([^"]*)"/i', $contentHtml, $alts)) {
            foreach ($alts[1] as $altText) {
                $altLower = mb_strtolower($altText, 'UTF-8');
                if ($altText !== '' && (mb_strpos($altLower, $kwNorm) !== false || mb_strpos($altLower, $kwSlugMatch) !== false)) {
                    $analysis['score'] += $config['weights']['kw_in_alt'];
                    $analysis['kw_in_alt'] = true;
                    break;
                }
            }
        }

        // Headings check
        if (preg_match('/<(h2|h3)\b/i', $contentHtml)) {
            $analysis['score'] += $config['weights']['headings'];
            $analysis['head_ok'] = true;
        }

        // Slug pattern (Unicode/Bengali support)
        if (preg_match('/^[\x{0980}-\x{09FF}a-z0-9]+(-[\x{0980}-\x{09FF}a-z0-9]+)*$/u', $slug)) {
            $analysis['score'] += $config['weights']['slug'];
            $analysis['slug_ok'] = true;
        } else {
            $cleanSlug = str_replace('-', '', $slug);
            if (preg_match('/^[\x{0980}-\x{09FF}a-z0-9]+$/u', $cleanSlug)) {
                $analysis['score'] += $config['weights']['slug'];
                $analysis['slug_ok'] = true;
            }
        }

        // Links check
        if (preg_match('/<a\b/i', $contentHtml)) {
            $analysis['score'] += $config['weights']['links'];
            $analysis['links_ok'] = true;
        }

        /*
         |--------------------------------------------------------------------------
         | KEYWORD RULES
         |--------------------------------------------------------------------------
        */
        if ($kwNorm !== '') {
            $contains = function (?string $haystack, $needle) {
                return $haystack && mb_strpos(mb_strtolower($haystack, 'UTF-8'), $needle) !== false;
            };

            // Title
            if ($contains($title, $kwNorm)) {
                $analysis['score'] += $config['weights']['kw_in_title'];
                $analysis['kw_in_title'] = true;
            }

            // Slug
            if ($contains($slug, $kwNorm) || ($kwSlugMatch !== '' && $contains($slug, $kwSlugMatch))) {
                $analysis['score'] += $config['weights']['kw_in_slug'];
                $analysis['kw_in_slug'] = true;
            }

            // Description
            if ($contains($desc, $kwNorm)) {
                $analysis['score'] += $config['weights']['kw_in_desc'];
                $analysis['kw_in_desc'] = true;
            }

            // Keyword in Intro
            $introArray = preg_split('/\s+/u', $contentText, $config['content']['intro_words'] + 1, PREG_SPLIT_NO_EMPTY);
            $introWords = implode(' ', array_slice($introArray, 0, $config['content']['intro_words']));
            if ($contains($introWords, $kwNorm)) {
                $analysis['score'] += $config['weights']['kw_in_intro'];
                $analysis['kw_in_intro'] = true;
            }

            // Keyword in Headings
            if (preg_match_all('/<(h2|h3)\b[^>]*>(.*?)<\/\1>/is', $contentHtml, $m)) {
                foreach ($m[2] as $headingText) {
                    if ($contains(strip_tags($headingText), $kwNorm)) {
                        $analysis['score'] += $config['weights']['kw_in_head'];
                        $analysis['kw_in_head'] = true;
                        break;
                    }
                }
            }

            // Keyword density
            $contentLower = mb_strtolower($contentText, 'UTF-8');
            $kwCount = mb_substr_count($contentLower, $kwNorm);
            $density = ($words > 0) ? ($kwCount / $words) * 100 : 0;
            $analysis['kw_density'] = round($density, 2);

            if ($density >= $config['density']['min'] && $density <= $config['density']['max']) {
                $analysis['score'] += $config['weights']['kw_density'];
                $analysis['kw_density_ok'] = true;
            }
        }

        $analysis['score'] = min(100, $analysis['score']);
        return $analysis;
    }

    protected static function defaultConfig(): array
    {
        return [
            'lengths' => [
                'title'       => ['min' => 30, 'max' => 70],
                'description' => ['min' => 80, 'max' => 160],
            ],
            'content' => [
                'min_words'   => 300,
                'intro_words' => 100,
            ],
            'density' => [
                'min' => 0.5,
                'max' => 3.5,
            ],
            'sentiment_words' => [
                'positive' => [
                    'সেরা', 'উপকারী', 'কার্যকর', 'সফল', 'প্রয়োজনীয়', 'শক্তিশালী', 'অসাধারণ', 'সহজ',
                    'best', 'ultimate', 'effective', 'proven', 'success', 'essential', 'powerful',
                ],
                'negative' => [
                    'ভুল', 'বর্জন', 'সতর্ক', 'সমস্যা', 'বিপদ', 'ব্যর্থ', 'ক্ষতিকর',
                    'mistake', 'avoid', 'warning', 'problem', 'danger', 'fail',
                ],
            ],
            'power_words' => [
                'ফ্রি', 'একচেটিয়া', 'গোপন', 'তাত্ক্ষণিক', 'সহজ', 'নিশ্চিত', 'অবিশ্বাস্য', 'দক্ষ', 'প্রিমিয়াম',
                'free', 'exclusive', 'secret', 'instant', 'simple', 'guaranteed', 'unbeatable',
            ],
            'weights' => [
                'title' => 10, 'title_sentiment' => 4, 'title_power' => 4, 'description' => 10,
                'content' => 15, 'image' => 8, 'headings' => 8, 'slug' => 6, 'links' => 8,
                'kw_in_title' => 8, 'kw_in_slug' => 6, 'kw_in_desc' => 6, 'kw_in_intro' => 8,
                'kw_in_head' => 6, 'kw_in_alt' => 4, 'kw_density' => 15,
            ],
        ];
    }
}
