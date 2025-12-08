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

    public Collection $trendingPosts;

    public ?Post $previousPost = null;

    public ?Post $nextPost = null;

    public function mount($post)
    {
        if ($post instanceof Post) {
            $this->post = $post;
            $this->postParameter = $post->slug ?: (string) $post->getKey();
        } else {
            $this->postParameter = (string) $post;
        }
        $this->relatedPosts = new Collection();
        $this->trendingPosts = new Collection();
    }

    public function loadPost(): void
    {
        $post = $this->post;

        if (! $post) {
            $post = Post::query()
                ->published()
                ->with([
                    'categories:id,name,slug',
                    'tags:id,name,slug',
                    'author:id,name',
                ])
                ->where('slug', $this->postParameter)
                ->first();
        }

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

        $this->trendingPosts = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
            ])
            ->whereKeyNot($post->id)
            ->orderByDesc('views')
            ->latest('created_at')
            ->take(5)
            ->get();

        $this->previousPost = Post::query()
            ->published()
            ->where('created_at', '<', $post->created_at)
            ->latest('created_at')
            ->first();

        $this->nextPost = Post::query()
            ->published()
            ->where('created_at', '>', $post->created_at)
            ->oldest('created_at')
            ->first();

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
