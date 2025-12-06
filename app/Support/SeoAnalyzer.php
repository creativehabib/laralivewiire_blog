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
     * @return array
     */
    public static function analyze(
        Model $model,
        ?string $focusKeyword = null,
        ?array $overrideMeta = null
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

        // meta কোথা থেকে নেবে?
        // overrideMeta থাকলে → ওটা, না থাকলে model->getMeta('seo_meta')
        if ($overrideMeta !== null) {
            $meta = $overrideMeta;
        } else {
            $meta = method_exists($model, 'getMeta')
                ? ($model->getMeta('seo_meta')[0] ?? [])
                : [];
        }

        // title / desc / slug / content resolve
        $title = $meta['seo_title']
            ?? $model->name
            ?? $model->title
            ?? '';

        $desc  = $meta['seo_description']
            ?? $model->description
            ?? '';

        $slug = $model->slug ?? '';

        $contentHtml = (string) ($model->content ?? '');
        $contentText = trim(strip_tags($contentHtml));

        $focusKeyword = trim((string) $focusKeyword);
        $kwNorm       = mb_strtolower($focusKeyword);

        /*
         |---------------------------------
         | BASE RULES
         |---------------------------------
        */

        // title length 30–65
        $len = mb_strlen($title);
        if ($len >= 30 && $len <= 65) {
            $analysis['score'] += 10;
            $analysis['title_ok'] = true;
        }

        // description length 80–160
        $descLen = mb_strlen($desc);
        if ($descLen >= 80 && $descLen <= 160) {
            $analysis['score'] += 10;
            $analysis['desc_ok'] = true;
        }

        // content words >= 600
        $words = max(1, str_word_count($contentText));
        if ($words >= 600) {
            $analysis['score'] += 15;
            $analysis['content_ok'] = true;
        }

        // at least one img with alt
        if (preg_match_all('/<img\b[^>]*alt="/i', $contentHtml)) {
            $analysis['score'] += 8;
            $analysis['image_ok'] = true;
        }

        // H2 / H3 headings
        if (preg_match('/<(h2|h3)\b/i', $contentHtml)) {
            $analysis['score'] += 8;
            $analysis['head_ok'] = true;
        }

        // clean slug pattern
        if (preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $slug)) {
            $analysis['score'] += 6;
            $analysis['slug_ok'] = true;
        }

        // at least one link
        if (preg_match('/<a\b/i', $contentHtml)) {
            $analysis['score'] += 8;
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
                $analysis['score'] += 8;
                $analysis['kw_in_title'] = true;
            }

            // slug
            if ($contains($slug)) {
                $analysis['score'] += 6;
                $analysis['kw_in_slug'] = true;
            }

            // description
            if ($contains($desc)) {
                $analysis['score'] += 6;
                $analysis['kw_in_desc'] = true;
            }

            // প্রথম 150 শব্দ
            $introWords = implode(' ', array_slice(explode(' ', $contentText), 0, 150));
            if ($contains($introWords)) {
                $analysis['score'] += 8;
                $analysis['kw_in_intro'] = true;
            }

            // H2/H3 heading
            if (preg_match_all('/<(h2|h3)\b[^>]*>(.*?)<\/\1>/is', $contentHtml, $m)) {
                foreach ($m[2] as $headingText) {
                    if ($contains(strip_tags($headingText))) {
                        $analysis['score'] += 6;
                        $analysis['kw_in_head'] = true;
                        break;
                    }
                }
            }

            // image alt text
            if (preg_match_all('/<img\b[^>]*alt="([^"]*)"/i', $contentHtml, $alts)) {
                foreach ($alts[1] as $altText) {
                    if ($contains($altText)) {
                        $analysis['score'] += 4;
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

                // 0.5%–3% range ok
                if ($density >= 0.5 && $density <= 3.0) {
                    $analysis['score'] += 15;
                    $analysis['kw_density_ok'] = true;
                }
            }
        }

        $analysis['score'] = min(100, $analysis['score']);

        return $analysis;
    }
}
