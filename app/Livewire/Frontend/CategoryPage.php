<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use Livewire\WithPagination;
use Livewire\Component;

class CategoryPage extends Component
{
    use WithPagination;

    public Category $category;

    public bool $ready = false;

    public int $perPage = 10;

    public function mount(Category $category)
    {
        $this->category = $category;
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
                    'categories:id,name,slug',
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
            ]);
    }
}
