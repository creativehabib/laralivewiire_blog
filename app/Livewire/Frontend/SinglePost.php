<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class SinglePost extends Component
{
    public string $postParameter;

    public bool $ready = false;

    public ?Post $post = null;

    public Collection $relatedPosts;

    public function mount($post)
    {
        $this->postParameter = (string) $post;
        $this->relatedPosts = collect();
    }

    public function loadPost(): void
    {
        $post = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
                'tags:id,name,slug',
                'author:id,name',
            ])
            ->where('slug', $this->postParameter)
            ->first();

        if (! $post && is_numeric($this->postParameter)) {
            $post = Post::query()
                ->published()
                ->with([
                    'categories:id,name,slug',
                    'tags:id,name,slug',
                    'author:id,name',
                ])
                ->whereKey($this->postParameter)
                ->first();
        }

        abort_if(! $post, 404);

        $this->post = $post;

        $this->relatedPosts = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
                'author:id,name',
            ])
            ->whereHas('categories', function ($query) use ($post) {
                $query->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->whereKeyNot($post->id)
            ->latest('created_at')
            ->take(6)
            ->get();

        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.frontend.single')
            ->layout('components.layouts.frontend.app', [
                'title' => $this->post?->name ?? 'পোস্ট',
            ]);
    }
}
