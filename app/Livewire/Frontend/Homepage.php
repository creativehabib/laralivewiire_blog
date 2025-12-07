<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;

class Homepage extends Component
{
    public function render()
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

        $primaryCategory = $categoryBlocks->first();
        $secondaryCategory = $categoryBlocks->skip(1)->first();

        $latestPosts = (clone $basePostQuery)
            ->latest('created_at')
            ->take(9)
            ->get();

        $videoPosts = (clone $basePostQuery)
            ->where('format_type', 'video')
            ->latest('created_at')
            ->take(6)
            ->get();

        $breakingNews = (clone $basePostQuery)
            ->where('is_breaking', true)
            ->latest('created_at')
            ->take(5)
            ->get();

        $popularPosts = (clone $basePostQuery)
            ->orderByDesc('views')
            ->take(5)
            ->get();

        $sidebarLatest = (clone $basePostQuery)
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('livewire.frontend.homepage', [
            'featuredPost' => $featuredPost,
            'headlinePosts' => $headlinePosts,
            'primaryCategory' => $primaryCategory,
            'secondaryCategory' => $secondaryCategory,
            'latestPosts' => $latestPosts,
            'videoPosts' => $videoPosts,
            'breakingNews' => $breakingNews,
            'popularPosts' => $popularPosts,
            'sidebarLatest' => $sidebarLatest,
        ])->layout('components.layouts.frontend.app', ['title' => 'বাংলাদেশী নিউজ পোর্টাল - হোম']);
    }
}
