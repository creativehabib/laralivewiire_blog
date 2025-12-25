<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Support\Str;

class PermalinkManager
{
    public const STRUCTURE_PLAIN = 'plain';
    public const STRUCTURE_DAY_AND_NAME = 'day_and_name';
    public const STRUCTURE_MONTH_AND_NAME = 'month_and_name';
    public const STRUCTURE_NUMERIC = 'numeric';
    public const STRUCTURE_POST_NAME = 'post_name';
    public const STRUCTURE_CUSTOM = 'custom';

    public const DEFAULT_STRUCTURE = self::STRUCTURE_POST_NAME;

    protected const STRUCTURE_TEMPLATES = [
        self::STRUCTURE_PLAIN => 'posts/%post_id%',
        self::STRUCTURE_DAY_AND_NAME => '%year%/%monthnum%/%day%/%postname%',
        self::STRUCTURE_MONTH_AND_NAME => '%year%/%monthnum%/%postname%',
        self::STRUCTURE_NUMERIC => 'archives/%post_id%',
        self::STRUCTURE_POST_NAME => '%postname%',
    ];

    protected const TOKEN_META = [
        '%postname%' => [
            'parameter' => 'post',
            'type' => 'slug',
            'pattern' => '[A-Za-z0-9\\-_]+',
            'sample' => 'sample-post',
        ],
        '%post_id%' => [
            'parameter' => 'post',
            'type' => 'id',
            'pattern' => '\\d+',
            'sample' => '123',
        ],
        '%year%' => [
            'parameter' => 'year',
            'type' => 'date',
            'pattern' => '\\d{4}',
            'sample' => '2024',
        ],
        '%monthnum%' => [
            'parameter' => 'month',
            'type' => 'date',
            'pattern' => '\\d{2}',
            'sample' => '05',
        ],
        '%day%' => [
            'parameter' => 'day',
            'type' => 'date',
            'pattern' => '\\d{2}',
            'sample' => '27',
        ],
        '%category%' => [
            'parameter' => 'category',
            'type' => 'taxonomy',
            'pattern' => '[A-Za-z0-9\\-_]+',
            'sample' => 'sample-category',
        ],
    ];

    public static function availableStructures(): array
    {
        return [
            self::STRUCTURE_PLAIN => [
                'label' => 'Plain',
                'template' => self::STRUCTURE_TEMPLATES[self::STRUCTURE_PLAIN],
            ],
            self::STRUCTURE_DAY_AND_NAME => [
                'label' => 'Day and name',
                'template' => self::STRUCTURE_TEMPLATES[self::STRUCTURE_DAY_AND_NAME],
            ],
            self::STRUCTURE_MONTH_AND_NAME => [
                'label' => 'Month and name',
                'template' => self::STRUCTURE_TEMPLATES[self::STRUCTURE_MONTH_AND_NAME],
            ],
            self::STRUCTURE_NUMERIC => [
                'label' => 'Numeric',
                'template' => self::STRUCTURE_TEMPLATES[self::STRUCTURE_NUMERIC],
            ],
            self::STRUCTURE_POST_NAME => [
                'label' => 'Post name',
                'template' => self::STRUCTURE_TEMPLATES[self::STRUCTURE_POST_NAME],
            ],
        ];
    }

    public static function allowedTokens(): array
    {
        return array_keys(self::TOKEN_META);
    }

    public static function currentStructure(): array
    {

        return self::validatedStructure(
            setting('permalink_structure') ?: null,
            setting('custom_permalink_structure') ?: null,
        );
    }

    public static function sanitizeCustomStructure(?string $value): string
    {
        return self::normalize($value, false);
    }

    public static function extractTokens(string $template): array
    {
        preg_match_all('/%[^%]+%/', $template, $matches);

        return array_values(array_unique($matches[0] ?? []));
    }

    public static function routeDefinition(): array
    {
        [$structure, $custom] = self::currentStructure();

        $template = self::normalizedTemplate($structure, $custom);
        $compiled = self::compileTemplate($template);

        return [
            'uri' => $compiled['uri'],
            'constraints' => $compiled['constraints'],
            'template' => $template,
            'structure' => $structure,
        ];
    }

    public static function routeParametersFor(Post $post): array
    {
        [$structure, $custom] = self::currentStructure();
        $template = self::normalizedTemplate($structure, $custom);

        $params = [];

        if (Str::contains($template, '%post_id%')) {
            $params['post'] = $post->getKey();
        } else {
            $params['post'] = $post->slug;
        }

        if (Str::contains($template, '%year%')) {
            $params['year'] = optional($post->created_at)->format('Y') ?? now()->format('Y');
        }

        if (Str::contains($template, '%monthnum%')) {
            $params['month'] = optional($post->created_at)->format('m') ?? now()->format('m');
        }

        if (Str::contains($template, '%day%')) {
            $params['day'] = optional($post->created_at)->format('d') ?? now()->format('d');
        }

        if (Str::contains($template, '%category%')) {

            // 1) যদি post->category (belongsTo) থাকে
            if (method_exists($post, 'category') && $post->relationLoaded('category')) {
                $params['category'] = optional($post->category)->slug ?: 'uncategorized';
            }

            // 2) যদি post->categories (belongsToMany) থাকে
            if (!isset($params['category'])) {
                // relation loaded থাকলে দ্রুত
                if ($post->relationLoaded('categories')) {
                    $cat = $post->categories->first();
                } else {
                    $cat = $post->categories()->with('slugRecord')->first();
                }

                $params['category'] = $cat?->slug ?: 'uncategorized';
            }
        }

        return $params;
    }

    public static function urlFor(Post $post, bool $absolute = true): string
    {
        $params = self::routeParametersFor($post);

        return route('posts.show', $params, $absolute);
    }

    public static function previewSample(?string $structure = null, ?string $custom = null): string
    {
        $template = self::normalizedTemplate($structure, $custom);
        $sample = self::replaceTokens($template, collect(self::TOKEN_META)->mapWithKeys(function ($meta, $token) {
            return [$token => $meta['sample']];
        })->all());

        return self::formatUrl($sample);
    }

    public static function normalizedTemplate(?string $structure = null, ?string $custom = null): string
    {
        $template = self::templateFor($structure, $custom);

        return self::normalize($template);
    }

    public static function templateFor(?string $structure = null, ?string $custom = null): string
    {
        [$structure, $custom] = self::validatedStructure($structure, $custom);

        if ($structure === self::STRUCTURE_CUSTOM) {
            return $custom ?: self::STRUCTURE_TEMPLATES[self::DEFAULT_STRUCTURE];
        }

        return self::STRUCTURE_TEMPLATES[$structure] ?? self::STRUCTURE_TEMPLATES[self::DEFAULT_STRUCTURE];
    }

    public static function replaceTokens(string $template, array $replacements): string
    {
        $result = $template;

        foreach ($replacements as $token => $value) {
            $result = str_replace($token, $value, $result);
        }

        return $result;
    }

    public static function formatUrl(string $path, bool $absolute = true): string
    {
        $path = trim($path);
        $path = trim($path, '/');
        $path = preg_replace('#//+#', '/', $path);

        $formatted = $path === '' ? '' : $path;

        if (! $absolute) {
            return '/' . ltrim($formatted, '/');
        }

        return url($formatted === '' ? '/' : $formatted);
    }

    protected static function compileTemplate(string $template): array
    {
        $uri = $template;
        $constraints = [];

        foreach (self::TOKEN_META as $token => $meta) {
            if (! Str::contains($uri, $token)) {
                continue;
            }

            $parameter = $meta['parameter'];

            if ($parameter === 'post' && $meta['type'] === 'slug') {
                $uri = str_replace($token, '{post}', $uri);
                $constraints['post'] = $meta['pattern'];
            } elseif ($parameter === 'post' && $meta['type'] === 'id') {
                $uri = str_replace($token, '{post}', $uri);
                $constraints['post'] = $meta['pattern'];
            } else {
                $uri = str_replace($token, '{' . $parameter . '}', $uri);
                if (! empty($meta['pattern'])) {
                    $constraints[$parameter] = $meta['pattern'];
                }
            }
        }

        $uri = trim($uri, '/');

        if ($uri === '') {
            $uri = '{post:slug}';
        }

        return [
            'uri' => $uri,
            'constraints' => $constraints,
        ];
    }

    protected static function normalize(?string $template, bool $fallbackToDefault = true): string
    {
        $template = $template ?? '';
        $template = trim($template);

        if ($template === '') {
            return $fallbackToDefault ? self::STRUCTURE_TEMPLATES[self::DEFAULT_STRUCTURE] : '';
        }

        if (preg_match('#^https?://#i', $template)) {
            $parsed = parse_url($template);
            $template = $parsed['path'] ?? '';
        }

        $template = trim($template);
        $template = trim($template, '/');

        if ($template === '') {
            return $fallbackToDefault ? self::STRUCTURE_TEMPLATES[self::DEFAULT_STRUCTURE] : '';
        }

        return $template;
    }

    public static function validatedStructure(?string $structure = null, ?string $custom = null): array
    {
        $structure = $structure ?: self::DEFAULT_STRUCTURE;
        $custom = $custom !== null ? self::sanitizeCustomStructure($custom) : null;

        if ($structure === self::STRUCTURE_CUSTOM) {
            $tokens = self::extractTokens($custom ?? '');
            $unknown = collect($tokens)->diff(self::allowedTokens());
            $containsPostIdentifier = in_array('%postname%', $tokens, true) || in_array('%post_id%', $tokens, true);
            $hasConflictingPostTokens = in_array('%postname%', $tokens, true) && in_array('%post_id%', $tokens, true);

            if ($custom === '' || $unknown->isNotEmpty() || ! $containsPostIdentifier || $hasConflictingPostTokens) {
                return [self::DEFAULT_STRUCTURE, null];
            }

            return [$structure, $custom];
        }

        if (! array_key_exists($structure, self::STRUCTURE_TEMPLATES)) {
            return [self::DEFAULT_STRUCTURE, null];
        }

        return [$structure, null];
    }

    /**
     * Generic preview builder
     *
     * @param string $type 'post' | 'category' | 'tag' | 'page'
     * @param string|null $slug
     * @return string
     */
    public static function preview(string $type, ?string $slug = null): string
    {
        $slug = $slug ?: 'your-slug';

        switch ($type) {
            case 'category':
                return self::categoryPreview($slug);

            case 'tag':
                return self::tagPreview($slug);

            case 'page':
                return self::pagePreview($slug);

            case 'post':
                return self::postPreview($slug);

            default:
                // fallback: শুধু /slug
                return self::formatUrl($slug);
        }
    }

    public static function postPreview(string $slug = 'your-slug', bool $absolute = true): string
    {
        // বর্তমানে সিলেক্ট করা permalink structure + custom
        [$structure, $custom] = self::currentStructure();
        $template = self::normalizedTemplate($structure, $custom);

        // টোকেনগুলোর জন্য ডিফল্ট ভ্যালু
        $replacements = [
            '%postname%'  => $slug,
            '%post_id%'   => '123',
            '%year%'      => now()->format('Y'),
            '%monthnum%'  => now()->format('m'),
            '%day%'       => now()->format('d'),
            '%category%'  => 'sample-category',
        ];

        $path = self::replaceTokens($template, $replacements);

        return self::formatUrl($path, $absolute);
    }
    /**
     * Category preview URL builder
     */
    public static function categoryPreview(string $slug = 'your-slug', bool $absolute = true): string
    {
        $path = self::categoryPrefix() . '/' . ltrim($slug, '/');

        // আগেই থাকা helper দিয়ে proper URL বানাচ্ছি
        return self::formatUrl($path, $absolute);
    }

    /**
     * tag preview URL builder
     */
    public static function tagPreview(string $slug = 'your-tag', bool $absolute = true): string
    {
        $path = self::tagPrefix() . '/' . ltrim($slug, '/');

        return self::formatUrl($path, $absolute);
    }

    public static function categoryPrefixEnabled(): bool
    {
        return self::categoryPrefix() !== '';
    }

    public static function categoryPrefix(): string
    {
        $prefix = setting('category_slug_prefix', '');
        $prefix = $prefix === null ? '' : trim((string) $prefix, '/');

        return $prefix;
    }

    public static function pagePrefixEnabled(): bool
    {
        return self::pagePrefix() !== '';
    }

    public static function pagePrefix(): string
    {
        $prefix = setting('page_slug_prefix', '');
        $prefix = $prefix === null ? '' : trim((string) $prefix, '/');

        return $prefix;
    }

    public static function pagePreview(string $slug = 'your-page', bool $absolute = true): string
    {
        $path = self::pagePrefix() . '/' . ltrim($slug, '/');

        return self::formatUrl($path, $absolute);
    }

    public static function tagPrefixEnabled(): bool
    {
        return self::tagPrefix() !== '';
    }

    public static function tagPrefix(): string
    {
        $prefix = setting('tag_slug_prefix', '');
        $prefix = $prefix === null ? '' : trim((string) $prefix, '/');

        return $prefix;
    }

    public static function postPrefix(): string
    {
        $prefix = setting('permalink_structure', '');
        $prefix = $prefix === null ? '' : trim((string) $prefix, '/');

        return $prefix;
    }


}
