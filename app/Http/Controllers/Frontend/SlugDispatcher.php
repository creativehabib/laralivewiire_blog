<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
use App\Support\SlugHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SlugDispatcher extends Controller
{
    public function __invoke(Request $request)
    {
        $slug = $this->extractSlug($request);

        $record = Slug::query()
            ->with('reference')
            ->where('key', $slug)
            ->first();

        $reference = $record?->reference;

        if (! $reference) {
            $reference = $this->resolveFallbackBySlug($slug);
            abort_if(! $reference, 404);
            $this->ensureSlugRecord($reference, $slug);
        }

        return match (true) {
            $reference instanceof Category => $this->handleCategory($reference),
            $reference instanceof Tag => $this->handleTag($reference),
            $reference instanceof Page => $this->handlePage($reference),
            $reference instanceof Post => $this->handlePost($reference),
            default => abort(404),
        };
    }

    protected function extractSlug(Request $request): string
    {
        $params = $request->route()?->parameters() ?? [];
        $value = array_values($params)[0] ?? '';

        return is_string($value) ? $value : (string) $value;
    }

    protected function resolveFallbackBySlug(string $slug): ?Model
    {
        $lookups = [];

        if (! PermalinkManager::categoryPrefixEnabled()) {
            $lookups[] = Category::class;
        }

        if (! PermalinkManager::tagPrefixEnabled()) {
            $lookups[] = Tag::class;
        }

        if (! PermalinkManager::pagePrefixEnabled()) {
            $lookups[] = Page::class;
        }

        foreach ($lookups as $modelClass) {
            $query = $modelClass::query();

            if (method_exists($modelClass, 'slugRecord')) {
                $query->whereHas('slugRecord', function ($slugQuery) use ($slug): void {
                    $slugQuery->where('key', $slug);
                });
            } elseif (Schema::hasColumn((new $modelClass)->getTable(), 'slug')) {
                $query->where('slug', $slug);
            } else {
                continue;
            }

            $model = $query->first();

            if ($model) {
                return $model;
            }
        }

        return null;
    }

    protected function ensureSlugRecord(Model $model, string $slug): void
    {
        if (! method_exists($model, 'slugRecord')) {
            return;
        }

        if (! $model->slugRecord()->exists()) {
            SlugHelper::createOrUpdate($model, $slug);
        }
    }

    protected function handleCategory(Category $category)
    {
        if (PermalinkManager::categoryPrefixEnabled()) {
            return redirect()->route('categories.show', ['category' => $category->slug]);
        }

        return $this->renderComponent(CategoryPage::class, ['category' => $category]);
    }

    protected function handleTag(Tag $tag)
    {
        if (PermalinkManager::tagPrefixEnabled()) {
            return redirect()->route('tags.show', ['tag' => $tag->slug]);
        }

        return $this->renderComponent(TagPage::class, ['tag' => $tag]);
    }

    protected function handlePage(Page $page)
    {
        if (PermalinkManager::pagePrefixEnabled()) {
            return redirect()->route('pages.show', ['page' => $page->slug]);
        }

        return $this->renderComponent(PageShow::class, ['page' => $page]);
    }

    protected function handlePost(Post $post)
    {
        [$structure, $custom] = PermalinkManager::currentStructure();
        $template = PermalinkManager::normalizedTemplate($structure, $custom);

        if ($template === '%postname%') {
            return $this->renderComponent(SinglePost::class, ['post' => $post]);
        }

        return redirect()->route('posts.show', PermalinkManager::routeParametersFor($post));
    }

    protected function renderComponent(string $component, array $params = [])
    {
        return app()->call($component, $params);
    }
}
