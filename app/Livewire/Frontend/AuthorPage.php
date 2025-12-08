<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class AuthorPage extends Component
{
    public User $author;

    public bool $ready = false;

    public Collection $posts;

    public Collection $trendingPosts;

    public Collection $moreNews;

    public int $totalPostCount = 0;

    public function mount(User $author)
    {
        $this->author = $author;
        $this->posts = collect();
        $this->trendingPosts = collect();
        $this->moreNews = collect();
    }

    public function loadAuthor(): void
    {
        $basePostQuery = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
                'author:id,name',
            ]);

        $this->posts = (clone $basePostQuery)
            ->where('author_id', $this->author->id)
            ->latest('created_at')
            ->take(18)
            ->get();

        $this->totalPostCount = (clone $basePostQuery)
            ->where('author_id', $this->author->id)
            ->count();

        $this->trendingPosts = (clone $basePostQuery)
            ->orderByDesc('views')
            ->latest('created_at')
            ->take(5)
            ->get();

        $this->moreNews = (clone $basePostQuery)
            ->latest('created_at')
            ->take(4)
            ->get();

        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.frontend.author')
            ->layout('components.layouts.frontend.app', [
                'title' => $this->author->name,
            ]);
    }
}
