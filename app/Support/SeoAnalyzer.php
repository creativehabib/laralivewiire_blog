<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class SeoAnalyzer
{
    /**
     * Yoast-style SEO analysis
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $focusKeyword
     * @param  array|null   $overrideMeta  // live preview এর জন্য
     * @param  array        $options       // rule config override (lengths, density, etc.)
     * @return array
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
            title: $meta['seo_title']
                ?? $model->name
                ?? $model->title
                ?? '',
            description: $meta['seo_description']
                ?? $model->description
                ?? '',
            slug: $model->slug ?? '',
            contentHtml: (string) ($model->content ?? ''),
            focusKeyword: $focusKeyword,
            options: $options
        );
    }

    /**
     * Analyze raw content instead of a model for reusability.
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
            'score'         => 0,
            'title_ok'      => false,
            'desc_ok'       => false,
            'content_ok'    => false,
            'image_ok'      => false,
            'head_ok'       => false,
            'slug_ok'       => false,
            'links_ok'      => false,

            'kw_in_title'   => false,
            'kw_in_slug'    => false,
            'kw_in_desc'    => false,
            'kw_in_intro'   => false,
            'kw_in_head'    => false,
            'kw_in_alt'     => false,
            'kw_density_ok' => false,
            'kw_density'    => 0,
            'focus_keyword' => $focusKeyword,
        ];

        $config = array_replace_recursive(static::defaultConfig(), $options);

        $desc        = $description;
        $contentText = trim(strip_tags($contentHtml));
        $focusKeyword = trim((string) $focusKeyword);
        $kwNorm       = mb_strtolower($focusKeyword);

        /*
         |---------------------------------
         | BASE RULES
         |---------------------------------
        */

        // title length
        $len = mb_strlen($title);
        $titleRange = $config['lengths']['title'];
        if ($len >= $titleRange['min'] && $len <= $titleRange['max']) {
            $analysis['score'] += $config['weights']['title'];
            $analysis['title_ok'] = true;
        }

        // description length
        $descLen  = mb_strlen($desc);
        $descRange = $config['lengths']['description'];
        if ($descLen >= $descRange['min'] && $descLen <= $descRange['max']) {
            $analysis['score'] += $config['weights']['description'];
            $analysis['desc_ok'] = true;
        }

        // content words
        $words = max(1, str_word_count($contentText));
        if ($words >= $config['content']['min_words']) {
            $analysis['score'] += $config['weights']['content'];
            $analysis['content_ok'] = true;
        }

        // at least one img with alt
        if (preg_match_all('/<img\b[^>]*alt="/i', $contentHtml)) {
            $analysis['score'] += $config['weights']['image'];
            $analysis['image_ok'] = true;
        }

        // H2 / H3 headings
        if (preg_match('/<(h2|h3)\b/i', $contentHtml)) {
            $analysis['score'] += $config['weights']['headings'];
            $analysis['head_ok'] = true;
        }

        // clean slug pattern
        if (preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $slug)) {
            $analysis['score'] += $config['weights']['slug'];
            $analysis['slug_ok'] = true;
        }

        // at least one link
        if (preg_match('/<a\b/i', $contentHtml)) {
            $analysis['score'] += $config['weights']['links'];
            $analysis['links_ok'] = true;
        }

        /*
         |---------------------------------
         | KEYWORD RULES
         |---------------------------------
        */
        if ($kwNorm !== '') {
            $contains = function (?string $haystack) use ($kwNorm) {
                return $haystack && str_contains(mb_strtolower($haystack), $kwNorm);
            };

            // title
            if ($contains($title)) {
                $analysis['score'] += $config['weights']['kw_in_title'];
                $analysis['kw_in_title'] = true;
            }

            // slug
            if ($contains($slug)) {
                $analysis['score'] += $config['weights']['kw_in_slug'];
                $analysis['kw_in_slug'] = true;
            }

            // description
            if ($contains($desc)) {
                $analysis['score'] += $config['weights']['kw_in_desc'];
                $analysis['kw_in_desc'] = true;
            }

            // প্রথম 150 শব্দ
            $introWords = implode(' ', array_slice(explode(' ', $contentText), 0, $config['content']['intro_words']));
            if ($contains($introWords)) {
                $analysis['score'] += $config['weights']['kw_in_intro'];
                $analysis['kw_in_intro'] = true;
            }

            // H2/H3 heading
            if (preg_match_all('/<(h2|h3)\b[^>]*>(.*?)<\/\1>/is', $contentHtml, $m)) {
                foreach ($m[2] as $headingText) {
                    if ($contains(strip_tags($headingText))) {
                        $analysis['score'] += $config['weights']['kw_in_head'];
                        $analysis['kw_in_head'] = true;
                        break;
                    }
                }
            }

            // image alt text
            if (preg_match_all('/<img\b[^>]*alt="([^"]*)"/i', $contentHtml, $alts)) {
                foreach ($alts[1] as $altText) {
                    if ($contains($altText)) {
                        $analysis['score'] += $config['weights']['kw_in_alt'];
                        $analysis['kw_in_alt'] = true;
                        break;
                    }
                }
            }

            // keyword density
            $contentLower = mb_strtolower($contentText);
            if ($words > 0) {
                $kwCount = substr_count($contentLower, $kwNorm);
                $density = $kwCount > 0 ? ($kwCount / $words) * 100 : 0;
                $analysis['kw_density'] = round($density, 2);

                $densityRange = $config['density'];
                if ($density >= $densityRange['min'] && $density <= $densityRange['max']) {
                    $analysis['score'] += $config['weights']['kw_density'];
                    $analysis['kw_density_ok'] = true;
                }
            }
        }

        $analysis['score'] = min(100, $analysis['score']);

        return $analysis;
    }

    /**
     * Default rule configuration that mimics Yoast-style scoring.
     */
    protected static function defaultConfig(): array
    {
        return [
            'lengths' => [
                'title'       => ['min' => 30, 'max' => 65],
                'description' => ['min' => 80, 'max' => 160],
            ],
            'content' => [
                'min_words'   => 600,
                'intro_words' => 150,
            ],
            'density' => [
                'min' => 0.5,
                'max' => 3.0,
            ],
            'weights' => [
                'title'        => 10,
                'description'  => 10,
                'content'      => 15,
                'image'        => 8,
                'headings'     => 8,
                'slug'         => 6,
                'links'        => 8,
                'kw_in_title'  => 8,
                'kw_in_slug'   => 6,
                'kw_in_desc'   => 6,
                'kw_in_intro'  => 8,
                'kw_in_head'   => 6,
                'kw_in_alt'    => 4,
                'kw_density'   => 15,
            ],
        ];
    }
}
