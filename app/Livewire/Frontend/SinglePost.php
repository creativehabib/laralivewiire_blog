<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use App\Support\SlugHelper;
use App\Support\Seo;
use App\Support\PostViewCounter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class SinglePost extends Component
{
    public bool $ready = false;

    public ?Post $post = null;

    public string $postParameter = '';

    public Collection $relatedPosts;
    public Collection $trendingPosts;

    public ?Post $previousPost = null;
    public ?Post $nextPost = null;

    /**
     * Mount works for ALL permalink structures
     * (%postname%, %category%/%postname%, numeric, etc.)
     */
    public function mount(?Post $post = null, ...$params): void
    {
        if ($post instanceof Post) {
            $this->post = $post;
            $this->postParameter = $post->slug ?: (string) $post->getKey();
            $this->relatedPosts  = new Collection();
            $this->trendingPosts = new Collection();

            return;
        }

        // Route থেকে সব parameter নাও (year/month/category/post ইত্যাদি)
        $routeParams = request()->route()?->parameters() ?? [];

        // ✅ Botble-style resolve (last segment = post)
        $post = $this->resolvePostParameter($routeParams);

        $this->post = $post;
        $this->postParameter = $post->slug ?: (string) $post->getKey();

        $this->relatedPosts  = new Collection();
        $this->trendingPosts = new Collection();
    }

    /**
     * 🔥 Core resolver (Controller-style)
     */
    protected function resolvePostParameter(array $parameters): Model
    {
        $values = array_values($parameters);

        // সবসময় LAST segment পোস্ট ধরা হচ্ছে
        $postParameter = array_pop($values);

        if (is_null($postParameter) || $postParameter === '') {
            abort(404);
        }

        if ($postParameter instanceof Post) {
            return $postParameter;
        }

        $postParameter = (string) $postParameter;

        $post = SlugHelper::resolveModel($postParameter, Post::class);

        if ($post) {
            return in_array($post->status, ['published', 'publish'], true) ? $post : abort(404);
        }

        return abort(404);
    }

    /**
     * Heavy queries defer করা
     */
    public function loadPost(): void
    {
        $post = $this->post;

        abort_if(! $post, 404);

        if (PostViewCounter::record($post)) {
            $post->views = ($post->views ?? 0) + 1;
        }

        $post->loadMissing([
            'categories:id,name',
            'categories.slugRecord',
            'tags:id,name',
            'tags.slugRecord',
            'author:id,name',
        ]);

        // Related posts (same category)
        $this->relatedPosts = Post::query()
            ->published()
            ->with(['categories:id,name', 'categories.slugRecord', 'author:id,name'])
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->whereKeyNot($post->id)
            ->latest('created_at')
            ->take(6)
            ->get();

        // Trending posts
        $this->trendingPosts = Post::query()
            ->published()
            ->with(['categories:id,name', 'categories.slugRecord'])
            ->whereKeyNot($post->id)
            ->orderByDesc('views')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Previous / Next
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

        $this->dispatch('post-content-loaded');
    }

    public function render()
    {
        return theme_view('livewire.frontend.single', [
            'post'          => $this->post,
            'relatedPosts'  => $this->relatedPosts,
            'trendingPosts' => $this->trendingPosts,
            'previousPost'  => $this->previousPost,
            'nextPost'      => $this->nextPost,
        ])->layout(theme_layout('app'), [
            'title' => $this->post?->name ?? 'Post',
            'seo' => Seo::forPost($this->post),
        ]);
    }
}
