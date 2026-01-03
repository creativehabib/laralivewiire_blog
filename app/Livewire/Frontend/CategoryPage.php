<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use App\Support\PermalinkManager;
use App\Support\SlugHelper;
use App\Support\Seo;
use Livewire\WithPagination;
use Livewire\Component;

class CategoryPage extends Component
{
    use WithPagination;

    public Category $category;

    public bool $ready = false;

    public int $perPage = 10;

    public function mount(Category|string $category)
    {
        if ($category instanceof Category) {
            $this->category = $category;
            return;
        }

        $slug = (string) $category;
        $resolved = SlugHelper::resolveModel($slug, Category::class);

        if ($resolved instanceof Category) {
            $this->category = $resolved;
            return;
        }

        if (! PermalinkManager::categoryPrefixEnabled()
            && PermalinkManager::routeDefinition()['template'] === '%postname%') {
            $post = SlugHelper::resolveModel($slug, Post::class);

            if ($post && in_array($post->status, ['published', 'publish'], true)) {
                return redirect()->route('posts.show', ['post' => $slug]);
            }
        }

        abort(404);
    }

    public function loadCategory(): void
    {
        $this->ready = true;
        $this->resetPage();
    }

    public function render()
    {
        $featurePosts = collect();
        $latestPosts = collect();

        if ($this->ready) {
            $baseQuery = Post::query()
                ->published()
                ->with([
                    'categories:id,name',
                    'categories.slugRecord',
                    'author:id,name',
                ])
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $this->category->id))
                ->latest('created_at');

            $featurePosts = (clone $baseQuery)->take(4)->get();

            $latestPosts = (clone $baseQuery)
                ->skip(4)
                ->paginate($this->perPage);
        }

        return view('livewire.frontend.category', compact('featurePosts', 'latestPosts'))
            ->layout('components.layouts.frontend.app', [
                'title' => $this->category->name,
                'seo' => Seo::forCategory($this->category),
            ]);
    }
}
