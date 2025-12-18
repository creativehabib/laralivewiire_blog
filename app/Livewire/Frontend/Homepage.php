<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use App\Support\Seo;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Homepage extends Component
{
    // মাউন্ট করা এবং রেন্ডার করার জন্য প্রপার্টিগুলো
    public $featuredPost, $headlinePosts, $primaryCategory, $secondaryCategory;
    public $latestPosts, $videoPosts, $breakingNews, $popularPosts, $sidebarLatest;
    public bool $isReady = false;

    public function mount(): void
    {
        $this->resetData();
    }

    private function resetData()
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
        // TTL (টাইম টু লিভ) ৫ মিনিট
        $cacheTtl = 300;

        $data = Cache::remember('homepage:v2:data', $cacheTtl, function () {
            // বেস কুয়েরি: 'title' এরর এড়াতে শুধু প্রয়োজনীয় রিলেশন লোড করা হচ্ছে।
            // যদি আপনার title কলামটি translation হয়ে থাকে তবে select ব্যবহার করবেন না।
            $basePostQuery = Post::query()
                ->published()
                ->with([
                    'categories:id,name,slug',
                    'author:id,name',
                ]);

            // ১. ফিচারড পোস্ট (সব কলাম সহ যাতে এরর না দেয়)
            $featuredPost = (clone $basePostQuery)
                ->where('is_featured', true)
                ->latest()
                ->first();

            if (! $featuredPost) {
                $featuredPost = (clone $basePostQuery)->latest()->first();
            }

            // ২. হেডলাইন পোস্ট
            $headlinePosts = (clone $basePostQuery)
                ->when($featuredPost, fn ($query) => $query->whereKeyNot($featuredPost->id))
                ->latest()
                ->take(4)
                ->get();

            // ৩. ক্যাটাগরি এবং ক্যাটাগরি ভিত্তিক পোস্ট
            $categoryBlocks = Category::query()
                ->where('status', 'published')
                ->with(['posts' => function ($query) {
                    $query->published()
                        ->with(['categories:id,name,slug', 'author:id,name'])
                        ->latest()
                        ->take(6);
                }])
                ->orderBy('order')
                ->take(2)
                ->get();

            // ৪. অন্যান্য সেকশনগুলো
            return [
                'featuredPost'    => $featuredPost,
                'headlinePosts'   => $headlinePosts,
                'primaryCategory' => $categoryBlocks->first(),
                'secondaryCategory' => $categoryBlocks->skip(1)->first(),
                'latestPosts'     => (clone $basePostQuery)->latest()->take(9)->get(),
                'videoPosts'      => (clone $basePostQuery)->where('format_type', 'video')->latest()->take(6)->get(),
                'breakingNews'    => (clone $basePostQuery)->where('is_breaking', true)->latest()->take(5)->get(),
                'popularPosts'    => (clone $basePostQuery)->orderByDesc('views')->take(5)->get(),
                'sidebarLatest'   => (clone $basePostQuery)->latest()->take(5)->get(),
            ];
        });

        // ক্যাশ থেকে ডাটা রেন্ডার প্রপার্টিতে সেট করা
        $this->featuredPost = $data['featuredPost'];
        $this->headlinePosts = $data['headlinePosts'];
        $this->primaryCategory = $data['primaryCategory'];
        $this->secondaryCategory = $data['secondaryCategory'];
        $this->latestPosts = $data['latestPosts'];
        $this->videoPosts = $data['videoPosts'];
        $this->breakingNews = $data['breakingNews'];
        $this->popularPosts = $data['popularPosts'];
        $this->sidebarLatest = $data['sidebarLatest'];

        $this->isReady = true;
    }

    public function render()
    {
        return view('livewire.frontend.homepage')
            ->layout('components.layouts.frontend.app', [
                'title' => 'বাংলাদেশী নিউজ পোর্টাল - হোম',
                'seo' => Seo::forHomepage(['title' => 'বাংলাদেশী নিউজ পোর্টাল - হোম']),
            ]);
    }
}
