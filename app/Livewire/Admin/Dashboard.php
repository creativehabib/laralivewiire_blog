<?php

namespace App\Livewire\Admin;

use App\Models\Admin\Comment;
use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];

    public array $visitorSeries = [];

    public array $weeks = [];

    public array $pieCharts = [];

    public array $statusChart = [];

    public Collection $topCategories;

    public Collection $popularTags;

    public Collection $latestMembers;

    public Collection $recentPosts;

    public Collection $recentPages;

    public Collection $mostViewedPosts;

    protected array $colorMap = [
        'bg-blue-500' => '#3b82f6',
        'bg-emerald-500' => '#10b981',
        'bg-indigo-500' => '#6366f1',
        'bg-rose-500' => '#f43f5e',
        'bg-slate-500' => '#64748b',
        'bg-amber-500' => '#f59e0b',
    ];

    public function mount(): void
    {
        $this->prepareStats();
        $this->prepareVisitorSeries();
        $this->prepareStatusChart();
        $this->preparePieCharts();

        $this->topCategories = $this->fetchTopCategories();
        $this->popularTags = $this->fetchPopularTags();
        $this->latestMembers = User::latest()->take(5)->get(['id', 'name', 'created_at']);
        $this->recentPosts = Post::latest()->take(6)->get(['id', 'name', 'created_at', 'views']);
        $this->recentPages = Page::latest()->take(6)->get(['id', 'name', 'slug', 'created_at']);
        $this->mostViewedPosts = Post::orderByDesc('views')->take(5)->get(['id', 'name', 'views']);
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }

    private function prepareStats(): void
    {
        $rangeEnd = Carbon::now();
        $rangeStart = (clone $rangeEnd)->subDays(30);
        $previousStart = (clone $rangeStart)->subDays(30);

        $postCurrent = Post::whereBetween('created_at', [$rangeStart, $rangeEnd])->count();
        $postPrevious = Post::whereBetween('created_at', [$previousStart, $rangeStart])->count();

        $pageCurrent = Page::whereBetween('created_at', [$rangeStart, $rangeEnd])->count();
        $pagePrevious = Page::whereBetween('created_at', [$previousStart, $rangeStart])->count();

        $userCurrent = User::whereBetween('created_at', [$rangeStart, $rangeEnd])->count();
        $userPrevious = User::whereBetween('created_at', [$previousStart, $rangeStart])->count();

        $commentCurrent = Comment::whereBetween('created_at', [$rangeStart, $rangeEnd])->count();
        $commentPrevious = Comment::whereBetween('created_at', [$previousStart, $rangeStart])->count();

        $this->stats = [
            $this->stat(__('New Posts'), $postCurrent, __('Last 30 days'), $postPrevious, 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-200'),
            $this->stat(__('New Pages'), $pageCurrent, __('Last 30 days'), $pagePrevious, 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200'),
            $this->stat(__('New Users'), $userCurrent, __('Last 30 days'), $userPrevious, 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200'),
            $this->stat(__('New Comments'), $commentCurrent, __('Last 30 days'), $commentPrevious, 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200'),
        ];
    }

    private function stat(string $label, int $current, string $subtitle, int $previous, string $badge): array
    {
        $delta = $this->growthPercentage($current, $previous);

        return [
            'label' => $label,
            'value' => number_format($current),
            'subtitle' => $subtitle,
            'delta' => $delta,
            'badge' => $badge,
        ];
    }

    private function growthPercentage(int $current, int $previous): float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function prepareVisitorSeries(): void
    {
        $startOfWeek = Carbon::now()->startOfWeek();

        $this->weeks = [];
        $posts = [];
        $comments = [];

        for ($i = 6; $i >= 0; $i--) {
            $start = (clone $startOfWeek)->subWeeks($i);
            $end = (clone $start)->endOfWeek();

            $this->weeks[] = $start->format('M d');
            $posts[] = Post::whereBetween('created_at', [$start, $end])->count();
            $comments[] = Comment::whereBetween('created_at', [$start, $end])->count();
        }

        $this->visitorSeries = [
            ['name' => __('Posts'), 'data' => $posts],
            ['name' => __('Comments'), 'data' => $comments],
        ];
    }

    private function prepareStatusChart(): void
    {
        $statuses = Post::select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(function ($status, $index) {
                $colors = array_values($this->colorMap);
                $color = $colors[$index % count($colors)];

                return [
                    'name' => $status->status ?? __('Unknown'),
                    'value' => (int) $status->total,
                    'color' => $color,
                ];
            })
            ->values();

        $this->statusChart = [
            'categories' => $statuses->pluck('name')->toArray(),
            'series' => $statuses->pluck('value')->toArray(),
            'colors' => $statuses->pluck('color')->toArray(),
        ];
    }

    private function preparePieCharts(): void
    {
        $colorKeys = array_keys($this->colorMap);

        $topCategories = $this->fetchTopCategories()->map(function ($category, $index) use ($colorKeys) {
            $colorKey = $colorKeys[$index % count($colorKeys)];

            return [
                'name' => $category->name,
                'value' => $category->posts_count,
                'color' => $colorKey,
            ];
        });

        $topTags = $this->fetchPopularTags()->map(function ($tag, $index) use ($colorKeys) {
            $colorKey = $colorKeys[($index + 1) % count($colorKeys)];

            return [
                'name' => $tag->name,
                'value' => $tag->posts_count,
                'color' => $colorKey,
            ];
        });

        $formatBreakdown = Post::selectRaw('COALESCE(format_type, "standard") as label')
            ->selectRaw('count(*) as total')
            ->groupBy('label')
            ->orderByDesc('total')
            ->get()
            ->map(function ($format, $index) use ($colorKeys) {
                $colorKey = $colorKeys[($index + 2) % count($colorKeys)];

                return [
                    'name' => ucfirst($format->label),
                    'value' => (int) $format->total,
                    'color' => $colorKey,
                ];
            });

        $this->pieCharts = collect([
            ['title' => __('Top Categories'), 'data' => $topCategories],
            ['title' => __('Top Tags'), 'data' => $topTags],
            ['title' => __('Post Formats'), 'data' => $formatBreakdown],
        ])->map(function ($chart) {
            return [
                'id' => str()->slug($chart['title']) . '-chart',
                'labels' => $chart['data']->pluck('name')->toArray(),
                'series' => $chart['data']->pluck('value')->toArray(),
                'colors' => $chart['data']->map(fn ($item) => $this->colorMap[$item['color']] ?? '#cbd5e1')->toArray(),
                'data' => $chart['data'],
                'title' => $chart['title'],
            ];
        })->values()->toArray();
    }

    private function fetchTopCategories(): Collection
    {
        return Category::withCount(['posts' => fn ($query) => $query->published()])
            ->orderByDesc('posts_count')
            ->take(7)
            ->get();
    }

    private function fetchPopularTags(): Collection
    {
        return Tag::select('tags.*')
            ->leftJoin('post_tags', 'tags.id', '=', 'post_tags.tag_id')
            ->selectRaw('count(post_tags.post_id) as posts_count')
            ->groupBy('tags.id')
            ->orderByDesc('posts_count')
            ->take(10)
            ->get();
    }
}
