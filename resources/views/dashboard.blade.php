<x-layouts.app :title="__('Dashboard')">
    @php
        $stats = [
            ['label' => __('New Visitors'), 'value' => '54', 'subtitle' => __('From last Week'), 'delta' => '+19', 'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200'],
            ['label' => __('Page Views'), 'value' => '10', 'subtitle' => __('From last Week'), 'delta' => '+20', 'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-200'],
            ['label' => __('New Signups'), 'value' => '5', 'subtitle' => __('From last Week'), 'delta' => '+5', 'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200'],
            ['label' => __('New Comments'), 'value' => '32', 'subtitle' => __('From last Week'), 'delta' => '+1', 'badge' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200'],
        ];

        $currentYear = now()->year;
        $previousYear = now()->subYear()->year;

        $visitorSeries = [
            ['name' => (string) $currentYear, 'data' => [420, 540, 610, 720, 680, 750, 820]],
            ['name' => (string) $previousYear, 'data' => [360, 460, 510, 580, 600, 640, 690]],
        ];

        $visitVsVisitor = [
            'categories' => range(1, 31),
            'series' => [
                [
                    'name' => __('Current Month Visits'),
                    'data' => [120, 140, 110, 160, 210, 180, 220, 250, 200, 190, 175, 230, 310, 290, 320, 280, 260, 240, 265, 300, 330, 310, 250, 230, 205, 225, 245, 260, 270, 190, 80],
                    'color' => '#7c3aed',
                ],
                [
                    'name' => __('Current Month Visitors'),
                    'data' => [90, 110, 85, 120, 170, 150, 180, 200, 160, 150, 140, 180, 220, 200, 230, 210, 200, 190, 205, 240, 260, 240, 200, 180, 170, 180, 190, 200, 210, 160, 60],
                    'color' => '#fb7185',
                ],
            ],
            'totals' => [
                'uniqueVisitors' => 16603,
                'totalVisits' => 421495,
                'totalVisitors' => 64234,
                'year' => $currentYear,
            ],
        ];

        $weeks = [__('1 Week'), __('2 Week'), __('3 Week'), __('4 Week'), __('5 Week'), __('6 Week'), __('7 Week')];

        $countries = [
            ['name' => 'India', 'value' => 50, 'color' => 'bg-amber-500'],
            ['name' => 'Usa', 'value' => 10, 'color' => 'bg-blue-500'],
            ['name' => 'Japan', 'value' => 10, 'color' => 'bg-emerald-500'],
            ['name' => 'China', 'value' => 15, 'color' => 'bg-indigo-500'],
            ['name' => 'Other', 'value' => 10, 'color' => 'bg-rose-500'],
        ];

        $browsers = [
            ['name' => 'Chrome', 'value' => 50, 'color' => 'bg-blue-500'],
            ['name' => 'Firefox', 'value' => 20, 'color' => 'bg-amber-500'],
            ['name' => 'Safari', 'value' => 10, 'color' => 'bg-emerald-500'],
            ['name' => 'Opera', 'value' => 10, 'color' => 'bg-rose-500'],
            ['name' => 'IE', 'value' => 5, 'color' => 'bg-indigo-500'],
        ];

        $colorMap = [
            'bg-blue-500' => '#3b82f6',
            'bg-emerald-500' => '#10b981',
            'bg-indigo-500' => '#6366f1',
            'bg-rose-500' => '#f43f5e',
            'bg-slate-500' => '#64748b',
            'bg-amber-500' => '#f59e0b',
        ];

        $devices = [
            ['name' => 'Desktop', 'value' => 40, 'color' => 'bg-indigo-500'],
            ['name' => 'Tablet', 'value' => 20, 'color' => 'bg-rose-500'],
            ['name' => 'Mobile', 'value' => 30, 'color' => 'bg-emerald-500'],
        ];

        $statuses = [
            ['name' => __('Published'), 'value' => 100, 'color' => 'bg-emerald-500'],
            ['name' => __('Drafts'), 'value' => 40, 'color' => 'bg-blue-500'],
            ['name' => __('Deleted'), 'value' => 60, 'color' => 'bg-rose-500'],
            ['name' => __('Featured'), 'value' => 60, 'color' => 'bg-amber-500'],
            ['name' => __('Banners'), 'value' => 40, 'color' => 'bg-slate-500'],
        ];

        $pieCharts = collect([
            ['title' => __('Top Countries'), 'data' => $countries],
            ['title' => __('Top Browser'), 'data' => $browsers],
            ['title' => __('Top Device'), 'data' => $devices],
        ])->map(function ($chart) use ($colorMap) {
            return [
                'id' => Str::slug($chart['title'], '-') . '-chart',
                'labels' => array_column($chart['data'], 'name'),
                'series' => array_column($chart['data'], 'value'),
                'colors' => array_map(fn($item) => $colorMap[$item['color']] ?? '#cbd5e1', $chart['data']),
            ];
        })->values();

        $statusChart = [
            'categories' => array_column($statuses, 'name'),
            'series' => array_column($statuses, 'value'),
            'colors' => array_map(fn($item) => $colorMap[$item['color']] ?? '#cbd5e1', $statuses),
        ];

        $categories = [
            ['name' => 'Laravel', 'value' => 100],
            ['name' => 'Angular Js', 'value' => 100],
            ['name' => 'React Js', 'value' => 100],
            ['name' => 'Vue Js', 'value' => 100],
            ['name' => 'Redux', 'value' => 100],
            ['name' => 'Livewire', 'value' => 100],
            ['name' => 'Html & Css', 'value' => 100],
        ];

        $members = [
            ['name' => 'Alex Glover', 'joined' => '2024-05-11 07:10 AM'],
            ['name' => 'Edna Mason', 'joined' => '2024-05-11 07:10 AM'],
            ['name' => 'Lucy Griffith', 'joined' => '2024-05-12 09:25 AM'],
            ['name' => 'Darrin Carpenter', 'joined' => '2024-05-13 12:40 PM'],
            ['name' => 'Leah Dawson', 'joined' => '2024-05-14 08:15 AM'],
        ];

        $recentPosts = [
            ['title' => 'Google Search position => 12:49am', 'date' => '05-14-2024', 'time' => '11:10 AM'],
            ['title' => 'New Post: The Challenge Program => 9:04am', 'date' => '05-14-2024', 'time' => '11:00 AM'],
            ['title' => 'New Post: Tips for Game => 10:11am', 'date' => '05-14-2024', 'time' => '10:50 AM'],
            ['title' => 'New Game: Hard => 1:30pm', 'date' => '05-14-2024', 'time' => '09:49 AM'],
            ['title' => 'SEO Updated: Things I love => 2:45pm', 'date' => '05-14-2024', 'time' => '08:00 AM'],
            ['title' => 'New Post: Hello All => 7:20am', 'date' => '05-14-2024', 'time' => '06:11 AM'],
        ];

        $pages = [
            ['title' => 'home', 'slug' => '/home'],
            ['title' => 'features', 'slug' => '/features'],
            ['title' => 'api', 'slug' => '/api'],
            ['title' => 'about us', 'slug' => '/about'],
            ['title' => 'privacy & policy', 'slug' => '/policy'],
            ['title' => 'documentation', 'slug' => '/documentation'],
            ['title' => 'pricing', 'slug' => '/pricing'],
        ];
    @endphp

    <div class="space-y-6 py-4">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-300">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-4xl font-bold text-slate-900 dark:text-white">{{ $stat['value'] }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $stat['badge'] }}">
                            {{ $stat['delta'] }}%
                        </span>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $stat['subtitle'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Visit Vs Visitor') }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Monthly comparison overview') }}</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                    @foreach ($visitVsVisitor['series'] as $series)
                        <span class="inline-flex items-center gap-2">
                            <span class="size-3 rounded-full" style="background-color: {{ $series['color'] }}"></span>
                            {{ $series['name'] }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="mt-6">
                <div id="visitVsVisitorChart" class="h-80"></div>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-slate-50 px-4 py-3 text-center dark:bg-slate-800/60">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Total Unique Visitors (:year)', ['year' => $visitVsVisitor['totals']['year']]) }}</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-800 dark:text-white">{{ number_format($visitVsVisitor['totals']['uniqueVisitors']) }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 px-4 py-3 text-center dark:bg-slate-800/60">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Total Visits') }}</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($visitVsVisitor['totals']['totalVisits']) }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 px-4 py-3 text-center dark:bg-slate-800/60">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Total Visitors') }}</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($visitVsVisitor['totals']['totalVisitors']) }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ __('Visitors Reports') }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __(now()->translatedFormat('F Y')) }} vs {{ $previousYear }}</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                        <span class="inline-flex items-center gap-1"><span class="size-3 rounded-full bg-indigo-500"></span>{{ $visitorSeries[0]['name'] ?? $currentYear }}</span>
                        <span class="inline-flex items-center gap-1"><span class="size-3 rounded-full bg-amber-400"></span>{{ $visitorSeries[1]['name'] ?? $previousYear }}</span>
                    </div>
                </div>
                <div class="mt-6">
                    <div id="visitorsChart" class="h-72"></div>
                </div>
            </div>

            <div class="grid gap-4">
                @foreach ([['title' => __('Top Countries'), 'data' => $countries], ['title' => __('Top Browser'), 'data' => $browsers], ['title' => __('Top Device'), 'data' => $devices]] as $pie)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-200">{{ $pie['title'] }}</p>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ array_sum(array_column($pie['data'], 'value')) }} {{ __('Entries') }}</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div id="{{ Str::slug($pie['title'], '-') }}-chart" class="h-56"></div>
                            <div class="space-y-2">
                                @foreach ($pie['data'] as $segment)
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="size-3 rounded-full {{ $segment['color'] }}"></span>
                                            <span class="text-slate-600 dark:text-slate-200">{{ $segment['name'] }}</span>
                                        </div>
                                        <span class="text-slate-500 dark:text-slate-400">{{ $segment['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Post Status') }}</p>
                    <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('Refresh') }}</button>
                </div>
                <div id="post-status-chart" class="mt-4 h-72"></div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-slate-600 dark:text-slate-200">
                    @foreach ($statuses as $status)
                        <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 dark:bg-slate-800/60">
                            <span class="size-2.5 rounded-full {{ $status['color'] }}"></span>
                            <span class="flex-1">{{ $status['name'] }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $status['value'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Popular Tags') }}</p>
                    <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('Explore') }}</button>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($categories as $category)
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            #{{ Str::slug($category['name']) }}
                            <span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-600 shadow-sm dark:bg-slate-900 dark:text-slate-300">{{ $category['value'] }}</span>
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Top Categories') }}</p>
                    <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('View All') }}</button>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-200">
                    @foreach ($categories as $category)
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                            <span>{{ $category['name'] }}</span>
                            <span class="text-slate-500 dark:text-slate-400">{{ $category['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Latest Members') }}</p>
                    <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('View All') }}</button>
                </div>
                <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-200">
                    @foreach ($members as $member)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <span class="size-10 rounded-full bg-gradient-to-br from-emerald-500 to-blue-500 text-center text-base font-semibold text-white">
                                    {{ strtoupper(substr($member['name'], 0, 1)) }}
                                </span>
                                <div>
                                    <p class="font-semibold">{{ $member['name'] }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Member since') }} {{ $member['joined'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Most Viewed Post') }}</p>
                    <div class="flex gap-2">
                        <button class="rounded-full bg-blue-500 px-3 py-1 text-xs font-medium text-white">{{ __('Today') }}</button>
                        <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('This Week') }}</button>
                    </div>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-200">
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Barbarian in Flight for Mystery Mission') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">1500</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Could U Spend ₿ 96K On A Single?') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">952</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Twitter Just Gave Elon Musk The Keys') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">720</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Why I’m skeptical about web 3 (but still invested)') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">520</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Scientists Just Found Water Vapor') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">301</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Popular Categories') }}</p>
                    <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('View All') }}</button>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-200">
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('New Generation Mobile Gadgets') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">04.9</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Laravel') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">01.1</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Programming In python') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">02.9</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('Traveling New Place') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">02.5</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                        <span>{{ __('How create new account in Whatsapp') }}</span>
                        <span class="text-slate-500 dark:text-slate-400">03.2</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Latest Posts') }}</p>
                    <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('Add New') }}</button>
                </div>
                <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-200">
                    @foreach ($recentPosts as $post)
                        <div class="flex items-start justify-between gap-3 py-3">
                            <div>
                                <p class="font-semibold">{{ $post['title'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $post['date'] }}</p>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $post['time'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Latest Pages') }}</p>
                    <button class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200">{{ __('Add New') }}</button>
                </div>
                <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-200">
                    @foreach ($pages as $page)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-emerald-500"></span>
                                <span class="font-semibold">{{ Str::title($page['title']) }}</span>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $page['slug'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const visitVsVisitorData = @json($visitVsVisitor);
                const visitVsVisitorEl = document.getElementById('visitVsVisitorChart');
                if (visitVsVisitorEl) {
                    new ApexCharts(visitVsVisitorEl, {
                        chart: {
                            type: 'area',
                            height: 360,
                            toolbar: { show: false },
                            zoom: { enabled: false },
                        },
                        series: visitVsVisitorData.series.map(({ name, data }) => ({ name, data })),
                        colors: visitVsVisitorData.series.map(({ color }) => color),
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        markers: {
                            size: 4,
                            strokeWidth: 2,
                            strokeColors: '#ffffff',
                            hover: { size: 6 },
                        },
                        dataLabels: { enabled: false },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 0.7,
                                opacityFrom: 0.4,
                                opacityTo: 0.08,
                                stops: [0, 85, 100],
                            },
                        },
                        grid: { borderColor: 'rgba(148, 163, 184, 0.25)' },
                        xaxis: {
                            categories: visitVsVisitorData.categories,
                            tickAmount: 7,
                            labels: {
                                style: { colors: Array(visitVsVisitorData.categories.length).fill('#94a3b8') },
                            },
                            axisBorder: { show: false },
                        },
                        yaxis: {
                            labels: {
                                style: { colors: Array(6).fill('#94a3b8') },
                            },
                        },
                        legend: { show: false },
                        tooltip: {
                            shared: true,
                            intersect: false,
                        },
                    }).render();
                }

                const visitorChartEl = document.querySelector('#visitorsChart');
                if (visitorChartEl) {
                    new ApexCharts(visitorChartEl, {
                        chart: {
                            type: 'area',
                            height: 320,
                            toolbar: { show: false },
                            zoom: { enabled: false },
                        },
                        series: @json($visitorSeries),
                        colors: ['#6366f1', '#f59e0b'],
                        dataLabels: { enabled: false },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 0.7,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [0, 90, 100],
                            },
                        },
                        grid: {
                            borderColor: 'rgba(148, 163, 184, 0.35)',
                        },
                        xaxis: {
                            categories: @json($weeks),
                            labels: {
                                style: { colors: Array(@json(count($weeks))).fill('#94a3b8') },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: { colors: ['#94a3b8'] },
                            },
                        },
                        legend: { show: true },
                        tooltip: { shared: true },
                    }).render();
                }

                const pieCharts = @json($pieCharts);
                pieCharts.forEach((chart) => {
                    const chartEl = document.getElementById(chart.id);
                    if (!chartEl) return;

                    new ApexCharts(chartEl, {
                        chart: {
                            type: 'donut',
                            height: 220,
                        },
                        labels: chart.labels,
                        series: chart.series,
                        colors: chart.colors,
                        legend: { show: false },
                        dataLabels: { enabled: false },
                        stroke: { width: 0 },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '60%',
                                    labels: {
                                        show: true,
                                        name: { show: true },
                                        value: {
                                            formatter: (value) => `${parseInt(value).toLocaleString()}`,
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            formatter: () => chart.series.reduce((a, b) => a + b, 0),
                                        },
                                    },
                                },
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: (value) => `${value.toLocaleString()}`,
                            },
                        },
                    }).render();
                });

                const statusData = @json($statusChart);
                const statusEl = document.getElementById('post-status-chart');
                if (statusEl) {
                    new ApexCharts(statusEl, {
                        chart: {
                            type: 'bar',
                            height: 320,
                            toolbar: { show: false },
                        },
                        series: [
                            {
                                name: '{{ __('Posts') }}',
                                data: statusData.series,
                            },
                        ],
                        colors: statusData.colors,
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                borderRadius: 8,
                                distributed: true,
                            },
                        },
                        dataLabels: { enabled: false },
                        grid: {
                            borderColor: 'rgba(148, 163, 184, 0.25)',
                        },
                        xaxis: {
                            categories: statusData.categories,
                            labels: {
                                style: { colors: Array(statusData.categories.length).fill('#94a3b8') },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: { colors: Array(statusData.categories.length).fill('#94a3b8') },
                            },
                        },
                    }).render();
                }
            });
        </script>
    @endpush
</x-layouts.app>
