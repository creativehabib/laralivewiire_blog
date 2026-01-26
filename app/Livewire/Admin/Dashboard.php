<?php

namespace App\Livewire\Admin;

use App\Models\Admin\Comment;
use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Dashboard extends Component
{
    public array $stats = [];

    public array $visitorSeries = [];

    public array $weeks = [];

    public array $pieCharts = [];

    public array $statusChart = [];

    public array $visitVsVisitor = [];

    public array $siteAnalytics = [];

    public Collection $topCategories;

    public Collection $popularTags;

    public Collection $latestMembers;

    public Collection $recentPosts;

    public Collection $recentPages;

    public Collection $mostViewedPosts;

    public Collection $activityLogs;

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
        $this->prepareVisitVsVisitor();
        $this->prepareSiteAnalytics();
        $this->prepareStatusChart();
        $this->preparePieCharts();

        $this->topCategories = $this->fetchTopCategories();
        $this->popularTags = $this->fetchPopularTags();
        $this->latestMembers = User::latest()->take(5)->get(['id', 'name', 'created_at']);
        $this->recentPosts = Post::latest()->take(6)->get(['id', 'name', 'created_at', 'views']);
        $this->recentPages = Page::latest()
            ->with('slugRecord')
            ->take(6)
            ->get(['id', 'name', 'created_at']);
        $this->mostViewedPosts = Post::orderByDesc('views')->take(5)->get(['id', 'name', 'views']);
        $this->activityLogs = Activity::with('causer')->latest()->take(6)->get(['id', 'description', 'properties', 'created_at', 'causer_id', 'causer_type']);
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('components.layouts.app', [
            'title' => __('Dashboard'),
        ]);
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
        $currentYear = $startOfWeek->year;
        $previousYear = (clone $startOfWeek)->subYear()->year;

        $weeks = collect(range(6, 0))->map(function ($index) use ($startOfWeek) {
            $weekStart = (clone $startOfWeek)->subWeeks($index);
            $weekEnd = (clone $weekStart)->endOfWeek();

            return [
                'label' => $weekStart->format('M d'),
                'current' => VisitorLog::whereBetween('visited_at', [$weekStart, $weekEnd])->count(),
                'previous' => VisitorLog::whereBetween('visited_at', [$weekStart->copy()->subYear(), $weekEnd->copy()->subYear()])->count(),
            ];
        });

        $this->weeks = $weeks->pluck('label')->toArray();

        $this->visitorSeries = [
            ['name' => (string) $currentYear, 'data' => $weeks->pluck('current')->toArray()],
            ['name' => (string) $previousYear, 'data' => $weeks->pluck('previous')->toArray()],
        ];
    }

    private function prepareVisitVsVisitor(): void
    {
        $now = Carbon::now();
        $startOfMonth = (clone $now)->startOfMonth();
        $endOfMonth = (clone $now)->endOfMonth();

        $dailyMetrics = VisitorLog::whereBetween('visited_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(visited_at) as visit_date')
            ->selectRaw('COUNT(*) as total_visits')
            ->selectRaw('COUNT(DISTINCT ip_address) as total_visitors')
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get()
            ->keyBy('visit_date');

        $daysInMonth = (int) $now->daysInMonth;
        $categories = range(1, $daysInMonth);
        $visitsSeries = [];
        $visitorsSeries = [];

        foreach ($categories as $day) {
            $date = $startOfMonth->copy()->addDays($day - 1)->toDateString();
            $visitsSeries[] = (int) ($dailyMetrics[$date]->total_visits ?? 0);
            $visitorsSeries[] = (int) ($dailyMetrics[$date]->total_visitors ?? 0);
        }

        $monthlyVisits = array_sum($visitsSeries);
        $monthlyVisitors = array_sum($visitorsSeries);

        $this->visitVsVisitor = [
            'categories' => $categories,
            'series' => [
                [
                    'name' => __('Current Month Visits'),
                    'data' => $visitsSeries,
                    'color' => '#7c3aed',
                ],
                [
                    'name' => __('Current Month Visitors'),
                    'data' => $visitorsSeries,
                    'color' => '#fb7185',
                ],
            ],
            'totals' => [
                'uniqueVisitors' => VisitorLog::whereYear('visited_at', $now->year)->distinct('ip_address')->count('ip_address'),
                'totalVisits' => $monthlyVisits,
                'totalVisitors' => $monthlyVisitors,
                'year' => $now->year,
            ],
        ];
    }

    private function prepareSiteAnalytics(): void
    {
        $hours = collect(range(1, 23))->map(fn ($hour) => $hour . 'h')->toArray();
        $regionData = VisitorLog::select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get();

        $mapValues = [];
        foreach ($regionData as $region) {
            $code = str($region->country)->upper()->value();
            $mapValues[$code] = (int) $region->total;
        }

        $this->siteAnalytics = [
            'hours' => $hours,
            'series' => [
                [
                    'name' => __('Sessions'),
                    'data' => [480, 460, 540, 470, 410, 420, 360, 330, 420, 480, 490, 480, 690, 660, 600, 530, 610, 420, 500, 470, 230, 140, 30],
                    'color' => '#60a5fa',
                ],
                [
                    'name' => __('Visitors'),
                    'data' => [180, 175, 190, 170, 160, 165, 150, 140, 160, 175, 180, 180, 210, 200, 185, 175, 200, 170, 175, 180, 120, 60, 10],
                    'color' => '#fb7185',
                ],
            ],
            'map' => [
                'values' => $mapValues,
                'max' => $regionData->max('total') ?? 0,
            ],
            'topRegions' => $regionData->take(5)->map(fn ($region) => [
                'name' => $region->country,
                'value' => (int) $region->total,
            ])->values()->toArray(),
            'summary' => [
                [
                    'label' => __('Sessions'),
                    'value' => '3,772',
                    'icon' => 'eye',
                    'color' => 'bg-rose-500',
                ],
                [
                    'label' => __('Visitors'),
                    'value' => '3,647',
                    'icon' => 'users',
                    'color' => 'bg-emerald-500',
                ],
                [
                    'label' => __('Pageviews'),
                    'value' => '6,233',
                    'icon' => 'layers',
                    'color' => 'bg-sky-500',
                ],
                [
                    'label' => __('Bounce Rate'),
                    'value' => '84%',
                    'icon' => 'bolt',
                    'color' => 'bg-amber-500',
                ],
            ],
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

        $countries = VisitorLog::select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($item, $index) use ($colorKeys) {
                $item['color'] = $colorKeys[$index % count($colorKeys)];
                $item['name'] = $item->country ?: __('Unknown');
                $item['value'] = (int) $item->total;

                return $item;
            });

        $browsers = VisitorLog::select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($item, $index) use ($colorKeys) {
                $item['color'] = $colorKeys[($index + 1) % count($colorKeys)];
                $item['name'] = $item->browser ?: __('Unknown');
                $item['value'] = (int) $item->total;

                return $item;
            });

        $devices = VisitorLog::select('device', DB::raw('count(*) as total'))
            ->groupBy('device')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($item, $index) use ($colorKeys) {
                $item['color'] = $colorKeys[($index + 2) % count($colorKeys)];
                $item['name'] = $item->device ?: __('Unknown');
                $item['value'] = (int) $item->total;

                return $item;
            });

        $this->pieCharts = collect([
            ['title' => __('Top Countries'), 'data' => $countries],
            ['title' => __('Top Browser'), 'data' => $browsers],
            ['title' => __('Top Device'), 'data' => $devices],
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
        return Tag::withCount(['posts' => fn ($query) => $query->published()])
            ->orderByDesc('posts_count')
            ->take(10)
            ->get();
    }
}
