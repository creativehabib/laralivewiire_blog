<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;

class Homepage extends Component
{
    public $featuredPost;

    public $headlinePosts;

    public $primaryCategory;

    public $secondaryCategory;

    public $latestPosts;

    public $videoPosts;

    public $breakingNews;

    public $popularPosts;

    public $sidebarLatest;

    public bool $isReady = false;

    public function mount(): void
    {
        $this->featuredPost = null;
        $this->headlinePosts = collect();
        $this->primaryCategory = null;
        $this->secondaryCategory = null;
        $this->latestPosts = collect();
        $this->videoPosts = collect();
        $this->breakingNews = collect();
        $this->popularPosts = collect();
        $this->sidebarLatest = collect();
    }

    public function loadHomepage(): void
    {
        $basePostQuery = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
                'author:id,name',
            ]);

        $featuredPost = (clone $basePostQuery)
            ->where('is_featured', true)
            ->latest('created_at')
            ->first();

        if (! $featuredPost) {
            $featuredPost = (clone $basePostQuery)->latest('created_at')->first();
        }

        $headlinePosts = (clone $basePostQuery)
            ->when($featuredPost, fn ($query) => $query->whereKeyNot($featuredPost->id))
            ->latest('created_at')
            ->take(4)
            ->get();

        $categoryBlocks = Category::query()
            ->where('status', 'published')
            ->with(['posts' => function ($query) {
                $query->published()
                    ->with([
                        'categories:id,name,slug',
                        'author:id,name',
                    ])
                    ->latest('created_at')
                    ->take(6);
            }])
            ->orderBy('order')
            ->orderBy('created_at')
            ->take(2)
            ->get();

        $this->primaryCategory = $categoryBlocks->first();
        $this->secondaryCategory = $categoryBlocks->skip(1)->first();

        $this->featuredPost = $featuredPost;
        $this->headlinePosts = $headlinePosts;
        $this->latestPosts = (clone $basePostQuery)
            ->latest('created_at')
            ->take(9)
            ->get();

        $this->videoPosts = (clone $basePostQuery)
            ->where('format_type', 'video')
            ->latest('created_at')
            ->take(6)
            ->get();

        $this->breakingNews = (clone $basePostQuery)
            ->where('is_breaking', true)
            ->latest('created_at')
            ->take(5)
            ->get();

        $this->popularPosts = (clone $basePostQuery)
            ->orderByDesc('views')
            ->take(5)
            ->get();

        $this->sidebarLatest = (clone $basePostQuery)
            ->latest('created_at')
            ->take(5)
            ->get();

        $this->isReady = true;
    }

    public function render()
    {
        return view('livewire.frontend.homepage', [
            'featuredPost' => $this->featuredPost,
            'headlinePosts' => $this->headlinePosts,
            'primaryCategory' => $this->primaryCategory,
            'secondaryCategory' => $this->secondaryCategory,
            'latestPosts' => $this->latestPosts,
            'videoPosts' => $this->videoPosts,
            'breakingNews' => $this->breakingNews,
            'popularPosts' => $this->popularPosts,
            'sidebarLatest' => $this->sidebarLatest,
            'isReady' => $this->isReady,
        ])->layout('components.layouts.frontend.app', ['title' => 'বাংলাদেশী নিউজ পোর্টাল - হোম']);
    }
}
