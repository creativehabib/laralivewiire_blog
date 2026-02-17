<?php

namespace App\Http\Controllers\Frontend;

use App\Livewire\Frontend\CategoryPage;
use App\Livewire\Frontend\PageShow;
use App\Livewire\Frontend\SinglePost;
use App\Livewire\Frontend\TagPage;
use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\Slug;
use App\Support\PermalinkManager;
use App\Support\Seo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Illuminate\Support\HtmlString;
use Livewire\Livewire;

class SlugFallbackController
{
    public function __invoke(string $slug): Response
    {
        $definitions = $this->definitions();
        $resolved = $this->resolveTarget($slug, $definitions);

        if ($resolved) {
            $modelClass = $resolved['model'];
            $model = $modelClass::find($resolved['id']);

            if ($model) {
                $definition = collect($definitions)
                    ->first(fn (array $item) => $item['model'] === $modelClass);

                if (! $definition) {
                    abort(404);
                }
                return response()->view('frontend.slug-fallback', [
                    'livewireComponent' => $definition['component'],
                    'livewireParams' => [$definition['parameter'] => $model],
                    'title' => $this->resolveTitle($model),
                    'seo' => $this->resolveSeo($model),
                ]);
            }
        }

        abort(404);
    }

    protected function definitions(): array
    {
        return [
            [
                'model' => Page::class,
                'component' => PageShow::class,
                'parameter' => 'page',
                'extension' => PermalinkManager::pageExtension(),
            ],
            [
                'model' => Category::class,
                'component' => CategoryPage::class,
                'parameter' => 'category',
                'extension' => PermalinkManager::categoryExtension(),
            ],
            [
                'model' => Tag::class,
                'component' => TagPage::class,
                'parameter' => 'tag',
                'extension' => PermalinkManager::tagExtension(),
            ],
            [
                'model' => Post::class,
                'component' => SinglePost::class,
                'parameter' => 'post',
                'extension' => PermalinkManager::postExtension(),
            ],
        ];
    }

    protected function resolveTarget(string $rawSlug, array $definitions): ?array
    {
        $cacheKey = 'slug_fallback:' . sha1($rawSlug . '|' . json_encode($definitions));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($rawSlug, $definitions) {
            $candidates = [];
            foreach ($definitions as $definition) {
                $resolvedSlug = $this->extractSlugForExtension($rawSlug, (string) $definition['extension']);
                if ($resolvedSlug === null) {
                    continue;
                }

                $candidates[] = [
                    'model' => $definition['model'],
                    'slug' => $resolvedSlug,
                ];
            }

            if ($candidates === []) {
                return null;
            }

            $rows = Slug::query()
                ->select(['id', 'key', 'reference_type', 'reference_id'])
                ->whereIn('key', array_values(array_unique(array_column($candidates, 'slug'))))
                ->whereIn('reference_type', array_values(array_unique(array_column($candidates, 'model'))))
                ->get();

            foreach ($definitions as $definition) {
                $matchedCandidate = collect($candidates)
                    ->first(fn (array $candidate) => $candidate['model'] === $definition['model']);

                if (! $matchedCandidate) {
                    continue;
                }

                $row = $rows->first(function (Slug $item) use ($matchedCandidate) {
                    return $item->reference_type === $matchedCandidate['model']
                        && $item->key === $matchedCandidate['slug'];
                });

                if ($row) {
                    return [
                        'model' => $row->reference_type,
                        'id' => (int) $row->reference_id,
                    ];
                }
            }

            return null;
        });
    }


    protected function resolveTitle(object $model): string
    {
        return $model->name ?? 'Post';
    }

    protected function resolveSeo(object $model): array
    {
        return match (true) {
            $model instanceof Page => Seo::forPage($model),
            $model instanceof Category => Seo::forCategory($model),
            $model instanceof Tag => Seo::forTag($model),
            $model instanceof Post => Seo::forPost($model),
            default => Seo::fromArray([]),
        };
    }

    protected function extractSlugForExtension(string $rawSlug, string $extension): ?string
    {
        if ($extension === '') {
            return $rawSlug;
        }

        if (! str_ends_with($rawSlug, $extension)) {
            return null;
        }

        $slug = substr($rawSlug, 0, -strlen($extension));

        return $slug === '' ? null : $slug;
    }
}
