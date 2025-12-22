<x-layouts.app :title="__('Dashboard')">
    @php
        $stats = [
            ['label' => __('New Visitors'), 'value' => '54', 'subtitle' => __('From last Week'), 'delta' => '+19', 'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200'],
            ['label' => __('Page Views'), 'value' => '10', 'subtitle' => __('From last Week'), 'delta' => '+20', 'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-200'],
            ['label' => __('New Signups'), 'value' => '5', 'subtitle' => __('From last Week'), 'delta' => '+5', 'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200'],
            ['label' => __('New Comments'), 'value' => '32', 'subtitle' => __('From last Week'), 'delta' => '+1', 'badge' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200'],
        ];

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
            'bg-amber-500' => '#f59e0b',
            'bg-blue-500' => '#3b82f6',
            'bg-emerald-500' => '#10b981',
            'bg-indigo-500' => '#6366f1',
            'bg-rose-500' => '#f43f5e',
            'bg-slate-500' => '#64748b',
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

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ __('Visitors Reports') }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('June 2024 vs 2023') }}</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                        <span class="inline-flex items-center gap-1"><span class="size-3 rounded-full bg-indigo-500"></span>2024</span>
                        <span class="inline-flex items-center gap-1"><span class="size-3 rounded-full bg-amber-400"></span>2023</span>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="h-64 rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <svg viewBox="0 0 600 240" class="h-full w-full" role="img" aria-label="Visitor line chart placeholder">
                            <defs>
                                <linearGradient id="line2024" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="rgb(99 102 241)" stop-opacity="0.55" />
                                    <stop offset="100%" stop-color="rgb(99 102 241)" stop-opacity="0" />
                                </linearGradient>
                                <linearGradient id="line2023" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="rgb(251 191 36)" stop-opacity="0.5" />
                                    <stop offset="100%" stop-color="rgb(251 191 36)" stop-opacity="0" />
                                </linearGradient>
                            </defs>
                            <rect x="40" y="10" width="520" height="180" rx="18" class="fill-white dark:fill-slate-900 stroke-slate-200 dark:stroke-slate-700" />
                            <polyline points="60,150 140,120 220,140 300,90 380,110 460,70 540,105" fill="none" stroke="rgb(59 130 246)" stroke-width="4" stroke-linecap="round" />
                            <path d="M60 150 L140 120 L220 140 L300 90 L380 110 L460 70 L540 105 L540 190 L60 190 Z" fill="url(#line2024)" opacity="0.8" />
                            <polyline points="60,170 140,130 220,150 300,110 380,130 460,90 540,130" fill="none" stroke="rgb(251 191 36)" stroke-width="4" stroke-linecap="round" stroke-dasharray="6 8" />
                            <path d="M60 170 L140 130 L220 150 L300 110 L380 130 L460 90 L540 130 L540 190 L60 190 Z" fill="url(#line2023)" opacity="0.8" />
                            <g fill="rgb(99 102 241)" stroke="white" stroke-width="2">
                                <circle cx="140" cy="120" r="6" />
                                <circle cx="300" cy="90" r="6" />
                                <circle cx="460" cy="70" r="6" />
                            </g>
                            <g fill="rgb(251 191 36)" stroke="white" stroke-width="2">
                                <circle cx="180" cy="140" r="6" />
                                <circle cx="360" cy="120" r="6" />
                                <circle cx="500" cy="110" r="6" />
                            </g>
                            <g class="text-[11px] fill-slate-400">
                                <text x="80" y="210">{{ __('1 Week') }}</text>
                                <text x="160" y="210">{{ __('2 Week') }}</text>
                                <text x="240" y="210">{{ __('3 Week') }}</text>
                                <text x="320" y="210">{{ __('4 Week') }}</text>
                                <text x="400" y="210">{{ __('5 Week') }}</text>
                                <text x="480" y="210">{{ __('6 Week') }}</text>
                                <text x="560" y="210">{{ __('7 Week') }}</text>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid gap-4">
                @foreach ([['title' => __('Top Countries'), 'data' => $countries], ['title' => __('Top Browser'), 'data' => $browsers], ['title' => __('Top Device'), 'data' => $devices]] as $pie)
                    @php
                        $total = array_reduce($pie['data'], fn($carry, $item) => $carry + $item['value'], 0);
                        $start = 0;
                        $segments = [];

                        foreach ($pie['data'] as $segment) {
                            $end = $start + ($segment['value'] / $total) * 360;
                            $color = $colorMap[$segment['color']] ?? '#cbd5e1';
                            $segments[] = "$color {$start}deg {$end}deg";
                            $start = $end;
                        }

                        $gradient = implode(', ', $segments);
                    @endphp
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-200">{{ $pie['title'] }}</p>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $total }} {{ __('Entries') }}</span>
                        </div>
                        <div class="mt-4 flex items-center gap-4">
                            <div class="relative size-28 shrink-0">
                                <div class="size-full rounded-full bg-slate-100 dark:bg-slate-800"></div>
                                <div class="absolute inset-0 rounded-full" style="background: conic-gradient({{ $gradient }});"></div>
                                <div class="absolute inset-3 rounded-full bg-white dark:bg-slate-900"></div>
                                <div class="absolute inset-0 flex items-center justify-center text-lg font-semibold text-slate-700 dark:text-white">{{ $total }}</div>
                            </div>
                            <div class="flex-1 space-y-2">
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
                <div class="mt-4 space-y-4">
                    @foreach ($statuses as $status)
                        <div>
                            <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-200">
                                <span>{{ $status['name'] }}</span>
                                <span>{{ $status['value'] }}%</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-100 dark:bg-slate-800">
                                <div class="h-full rounded-full {{ $status['color'] }}" style="width: {{ $status['value'] }}%"></div>
                            </div>
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
</x-layouts.app>
