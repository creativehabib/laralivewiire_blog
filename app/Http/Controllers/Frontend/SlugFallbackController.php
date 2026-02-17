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
use App\Support\PermalinkManager;
use App\Support\SlugHelper;
use Illuminate\Http\Response;
use Livewire\Livewire;

class SlugFallbackController
{
    public function __invoke(string $slug): Response
    {
        $definitions = [
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

        foreach ($definitions as $definition) {
            $resolvedSlug = $this->extractSlugForExtension($slug, (string) $definition['extension']);

            if ($resolvedSlug === null) {
                continue;
            }

            $model = SlugHelper::resolveModel($resolvedSlug, $definition['model']);

            if (! $model) {
                continue;
            }

            $mounted = Livewire::mount($definition['component'], [
                $definition['parameter'] => $model,
            ]);

            $html = is_string($mounted)
                ? $mounted
                : (method_exists($mounted, 'html') ? $mounted->html() : (string) $mounted);

            return response($html);
        }

        abort(404);
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
