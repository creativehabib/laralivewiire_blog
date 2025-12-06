<?php

namespace App\Models;

use App\Models\Admin\Tag;
use App\Models\Concerns\HasMetaBoxes; // যদি meta ব্যবহার করো
use App\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    use HasMetaBoxes;
    use HasSeoMeta;

    protected $fillable = [
        'name',
        'description',
        'content',
        'slug',
        'status',
        'author_id',
        'author_type',
        'is_featured',
        'image',
        'views',
        'allow_comments',
        'is_breaking',
        'format_type',
        'seo_score',
    ];

    // Categories (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_categories');
    }

    // Tags (many-to-many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * গুগল-friendly on-page checklist থেকে 0–100 SEO score
     */
    public function analyzeSeo(
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

        // 🔹 meta কোথা থেকে নেবে?
        //  - যদি overrideMeta দেয়া থাকে → সেটাই ব্যবহার করবে (live preview)
        //  - নাহলে DB থেকে getMeta() দিয়ে নেবে (saved post)
        if ($overrideMeta !== null) {
            $meta = $overrideMeta;
        } else {
            $meta = method_exists($this, 'getMeta')
                ? ($this->getMeta('seo_meta')[0] ?? [])
                : [];
        }

        $title = $meta['seo_title']       ?? $this->name        ?? '';
        $desc  = $meta['seo_description'] ?? $this->description ?? '';
        $slug  = $this->slug ?? '';
        $contentHtml = (string) $this->content;
        $contentText = trim(strip_tags($contentHtml));

        $focusKeyword = trim((string) $focusKeyword);
        $kwNorm       = mb_strtolower($focusKeyword);

        // ----- BASE RULES -----
        $len = mb_strlen($title);
        if ($len >= 30 && $len <= 65) {
            $analysis['score'] += 10;
            $analysis['title_ok'] = true;
        }

        $descLen = mb_strlen($desc);
        if ($descLen >= 80 && $descLen <= 160) {
            $analysis['score'] += 10;
            $analysis['desc_ok'] = true;
        }

        $words = max(1, str_word_count($contentText));
        if ($words >= 600) {
            $analysis['score'] += 15;
            $analysis['content_ok'] = true;
        }

        if (preg_match_all('/<img\b[^>]*alt="/i', $contentHtml)) {
            $analysis['score'] += 8;
            $analysis['image_ok'] = true;
        }

        if (preg_match('/<(h2|h3)\b/i', $contentHtml)) {
            $analysis['score'] += 8;
            $analysis['head_ok'] = true;
        }

        if (preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $slug)) {
            $analysis['score'] += 6;
            $analysis['slug_ok'] = true;
        }

        if (preg_match('/<a\b/i', $contentHtml)) {
            $analysis['score'] += 8;
            $analysis['links_ok'] = true;
        }

        // ----- KEYWORD RULES -----
        if ($kwNorm !== '') {
            $contains = function (?string $haystack) use ($kwNorm) {
                return $haystack && str_contains(mb_strtolower($haystack), $kwNorm);
            };

            if ($contains($title)) {
                $analysis['score'] += 8;
                $analysis['kw_in_title'] = true;
            }

            if ($contains($slug)) {
                $analysis['score'] += 6;
                $analysis['kw_in_slug'] = true;
            }

            if ($contains($desc)) {
                $analysis['score'] += 6;
                $analysis['kw_in_desc'] = true;
            }

            $introWords = implode(' ', array_slice(explode(' ', $contentText), 0, 150));
            if ($contains($introWords)) {
                $analysis['score'] += 8;
                $analysis['kw_in_intro'] = true;
            }

            if (preg_match_all('/<(h2|h3)\b[^>]*>(.*?)<\/\1>/is', $contentHtml, $m)) {
                foreach ($m[2] as $headingText) {
                    if ($contains(strip_tags($headingText))) {
                        $analysis['score'] += 6;
                        $analysis['kw_in_head'] = true;
                        break;
                    }
                }
            }

            if (preg_match_all('/<img\b[^>]*alt="([^"]*)"/i', $contentHtml, $alts)) {
                foreach ($alts[1] as $altText) {
                    if ($contains($altText)) {
                        $analysis['score'] += 4;
                        $analysis['kw_in_alt'] = true;
                        break;
                    }
                }
            }

            $contentLower = mb_strtolower($contentText);
            $kwCount  = 0;

            if ($words > 0) {
                $kwCount = substr_count($contentLower, $kwNorm);
                $density = $kwCount > 0 ? ($kwCount / $words) * 100 : 0;
                $analysis['kw_density'] = round($density, 2);

                if ($density >= 0.5 && $density <= 3.0) {
                    $analysis['score'] += 15;
                    $analysis['kw_density_ok'] = true;
                }
            }
        }

        $analysis['score'] = min(100, $analysis['score']);

        return $analysis;
    }

    public function getSeoScoreAttribute()
    {
        return $this->analyzeSeo()['score'];
    }
}
