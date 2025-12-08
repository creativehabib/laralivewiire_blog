<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;

class CategoryPage extends Component
{
    public Category $category;

    public bool $ready = false;

    public Collection $featurePosts;

    public Collection $latestPosts;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->featurePosts = collect();
        $this->latestPosts = collect();
    }

    public function loadCategory(): void
    {
        $baseQuery = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
                'author:id,name',
            ])
            ->whereHas('categories', fn ($query) => $query->where('categories.id', $this->category->id))
            ->latest('created_at');

        $this->featurePosts = (clone $baseQuery)->take(4)->get();
        $this->latestPosts = (clone $baseQuery)->skip(4)->take(12)->get();

        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.frontend.category')
            ->layout('components.layouts.frontend.app', [
                'title' => $this->category->name,
            ]);
    }
}
