<?php

namespace App\Livewire\Frontend;

use App\Models\Admin\Tag;
use App\Models\Post;
use App\Support\Seo;
use Illuminate\Support\Collection;
use Livewire\Component;

class TagPage extends Component
{
    public ?Tag $tag = null;

    public bool $ready = false;

    public Collection $posts;

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
        $this->posts = collect();
    }

    public function loadTag(): void
    {
        $this->posts = Post::query()
            ->published()
            ->with([
                'categories:id,name',
                'categories.slugRecord',
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
                'seo' => Seo::forTag($this->tag),
            ]);
    }
}
