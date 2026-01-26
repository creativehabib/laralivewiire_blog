@php
    use Illuminate\Support\Str;
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
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Total Visits (This Month)') }}</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($visitVsVisitor['totals']['totalVisits']) }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 px-4 py-3 text-center dark:bg-slate-800/60">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Total Visitors (This Month)') }}</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($visitVsVisitor['totals']['totalVisitors']) }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ __('Visitors Reports') }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Current vs previous year performance') }}</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                        <span class="inline-flex items-center gap-1"><span class="size-3 rounded-full bg-indigo-500"></span>{{ $visitorSeries[0]['name'] ?? __('Current Year') }}</span>
                        <span class="inline-flex items-center gap-1"><span class="size-3 rounded-full bg-amber-400"></span>{{ $visitorSeries[1]['name'] ?? __('Previous Year') }}</span>
                    </div>
                </div>
                <div class="mt-6">
                    <div id="visitorsChart" class="h-72"></div>
                </div>
            </div>

            <div class="grid gap-4">
                @foreach ($pieCharts as $pie)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-200">{{ $pie['title'] }}</p>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ array_sum($pie['series']) }} {{ __('Entries') }}</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div id="{{ $pie['id'] }}" class="h-56"></div>
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

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Activity Logs') }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Recent platform activities') }}</p>
                </div>
                <a href="{{ route('system.activity-logs') }}" class="text-xs font-semibold text-blue-600 hover:underline dark:text-blue-400">
                    {{ __('View All') }}
                </a>
            </div>

            <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-200">
                @forelse ($activityLogs as $log)
                    <div class="flex items-start justify-between gap-3 py-3">
                        <div class="flex items-start gap-3">
                            <span class="flex size-10 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                {{ Str::upper(Str::substr($log->causer?->name ?? __('System'), 0, 1)) }}
                            </span>
                            <div class="space-y-1">
                                <p class="font-semibold">{{ $log->causer?->name ?? __('System') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $log->description }}</p>
                            </div>
                        </div>
                        <div class="text-right text-xs text-slate-500 dark:text-slate-400">
                            <p>{{ $log->created_at->diffForHumans() }}</p>
                            @if (data_get($log->properties, 'ip'))
                                <p class="text-[11px] text-blue-500">{{ data_get($log->properties, 'ip') }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                        {{ __('No activity logs found') }}
                    </div>
                @endforelse
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Post Status') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('Auto-updated') }}</span>
                </div>
                <div id="post-status-chart" class="mt-4 h-72"></div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-slate-600 dark:text-slate-200">
                    @foreach ($statusChart['categories'] as $index => $status)
                        <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 dark:bg-slate-800/60">
                            <span class="size-2.5 rounded-full" style="background-color: {{ $statusChart['colors'][$index] ?? '#94a3b8' }}"></span>
                            <span class="flex-1">{{ $status }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $statusChart['series'][$index] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Popular Tags') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('Top 10') }}</span>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($popularTags as $tag)
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            #{{ Str::slug($tag->name) }}
                            <span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-600 shadow-sm dark:bg-slate-900 dark:text-slate-300">{{ $tag->posts_count }}</span>
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Top Categories') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('View All') }}</span>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-200">
                    @foreach ($topCategories as $category)
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                            <span>{{ $category->name }}</span>
                            <span class="text-slate-500 dark:text-slate-400">{{ $category->posts_count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Latest Members') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('Recent Signups') }}</span>
                </div>
                <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-200">
                    @foreach ($latestMembers as $member)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <span class="size-10 rounded-full bg-gradient-to-br from-emerald-500 to-blue-500 text-center text-base font-semibold text-white">
                                    {{ Str::upper(Str::substr($member->name, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="font-semibold">{{ $member->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Member since') }} {{ $member->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Most Viewed Posts') }}</p>
                    <div class="flex gap-2">
                        <span class="rounded-full bg-blue-500 px-3 py-1 text-xs font-medium text-white">{{ __('Top 5') }}</span>
                    </div>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-200">
                    @foreach ($mostViewedPosts as $post)
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                            <span>{{ $post->name }}</span>
                            <span class="text-slate-500 dark:text-slate-400">{{ number_format($post->views ?? 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Popular Categories') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('Top 5') }}</span>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-200">
                    @foreach ($topCategories->take(5) as $category)
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                            <span>{{ $category->name }}</span>
                            <span class="text-slate-500 dark:text-slate-400">{{ number_format($category->posts_count) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Latest Posts') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('Recent 6') }}</span>
                </div>
                <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-200">
                    @foreach ($recentPosts as $post)
                        <div class="flex items-start justify-between gap-3 py-3">
                            <div>
                                <p class="font-semibold">{{ $post->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $post->created_at?->format('M d, Y') }}</p>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ number_format($post->views ?? 0) }} {{ __('views') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Latest Pages') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('Recent 6') }}</span>
                </div>
                <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-200">
                    @foreach ($recentPages as $page)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-emerald-500"></span>
                                <span class="font-semibold">{{ Str::title($page->name) }}</span>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400">/{{ $page->slug }}</span>
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
            const visitVsVisitor = @json($visitVsVisitor);
            const visitVsVisitorEl = document.getElementById('visitVsVisitorChart');
            if (visitVsVisitorEl) {
                new ApexCharts(visitVsVisitorEl, {
                    chart: {
                        type: 'area',
                        height: 320,
                        toolbar: { show: false },
                        zoom: { enabled: false },
                    },
                    series: visitVsVisitor.series,
                    colors: visitVsVisitor.series.map((series) => series.color),
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
                        categories: visitVsVisitor.categories,
                        labels: {
                            style: { colors: Array(visitVsVisitor.categories.length).fill('#94a3b8') },
                        },
                    },
                    yaxis: {
                        labels: {
                            style: { colors: ['#94a3b8'] },
                        },
                    },
                    legend: { show: false },
                    tooltip: { shared: true },
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
</div>
