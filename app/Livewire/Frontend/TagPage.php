<?php

namespace App\Livewire\Frontend;

use App\Models\Admin\Tag;
use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;

class TagPage extends Component
{
    public string $slug;

    public ?Tag $tag = null;

    public bool $ready = false;

    public Collection $posts;

    public function mount(string $slug)
    {
        $this->slug = $slug;
        $this->posts = collect();
    }

    public function loadTag(): void
    {
        $this->tag = Tag::query()
            ->where('slug', $this->slug)
            ->firstOrFail();

        $this->posts = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
                'author:id,name',
            ])
            ->whereHas('tags', fn ($query) => $query->where('tags.id', $this->tag->id))
            ->latest('created_at')
            ->take(18)
            ->get();

        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.frontend.tag')
            ->layout('components.layouts.frontend.app', [
                'title' => $this->tag?->name ?? 'ট্যাগ',
            ]);
    }
}
