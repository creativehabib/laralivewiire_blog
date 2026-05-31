@php
    use Illuminate\Support\Str;
    $dashboardWidgets = [
        'stats' => __('Stats Overview'),
        'visit-vs-visitor' => __('Visit Vs Visitor'),
        'visitors-reports' => __('Visitors Reports'),
        'top-countries' => __('Top Countries'),
        'top-browser' => __('Top Browser'),
        'top-device' => __('Top Device'),
        'activity-logs' => __('Activity Logs'),
        'post-status' => __('Post Status'),
        'popular-tags' => __('Popular Tags'),
        'top-categories' => __('Top Categories'),
        'latest-members' => __('Latest Members'),
        'most-viewed-posts' => __('Most Viewed Posts'),
        'popular-categories' => __('Popular Categories'),
        'latest-posts' => __('Latest Posts'),
        'latest-pages' => __('Latest Pages'),
    ];
@endphp

<div class="space-y-6 py-4">
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-controls>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Dashboard Options') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Drag dashboard widgets to reorder them and choose which widgets should be visible.') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" data-dashboard-options-toggle>
                        {{ __('Screen Options') }}
                    </button>
                    <button type="button" class="rounded-full bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-700" data-dashboard-reset>
                        {{ __('Reset Layout') }}
                    </button>
                </div>
            </div>

            <div class="mt-4 hidden rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60" data-dashboard-options-panel>
                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Show on screen') }}</p>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($dashboardWidgets as $widgetId => $widgetLabel)
                        <label class="flex cursor-pointer items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-slate-200 dark:ring-slate-700">
                            <input type="checkbox" class="size-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" data-dashboard-visibility-toggle value="{{ $widgetId }}" checked>
                            <span>{{ $widgetLabel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3" data-dashboard-widgets>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 xl:col-span-3" data-dashboard-widget="stats" draggable="true">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Stats Overview') }}</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-200">{{ __('Last 30 days') }}</span>
                </div>
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 xl:col-span-3" data-dashboard-widget="visit-vs-visitor" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 xl:col-span-3" data-dashboard-widget="visitors-reports" draggable="true">
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
            </section>

            @foreach ($pieCharts as $pie)
                @php($pieWidgetId = ['top-countries', 'top-browser', 'top-device'][$loop->index] ?? 'traffic-chart-' . $loop->index)
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="{{ $pieWidgetId }}" draggable="true">
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
                </section>
            @endforeach

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 xl:col-span-3" data-dashboard-widget="activity-logs" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="post-status" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="popular-tags" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="top-categories" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="latest-members" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="most-viewed-posts" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="popular-categories" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 xl:col-span-2" data-dashboard-widget="latest-posts" draggable="true">
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
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-dashboard-widget="latest-pages" draggable="true">
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
            </section>
        </div>
    </div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        (() => {
            const dashboardChartPayload = {
                visitVsVisitor: @json($visitVsVisitor),
                visitorSeries: @json($visitorSeries),
                weeks: @json($weeks),
                pieCharts: @json($pieCharts),
                statusChart: @json($statusChart),
                postsLabel: @js(__('Posts')),
                dragTitle: @js(__('Drag to reorder')),
            };

            const renderDashboardChart = (element, options) => {
                if (! element || typeof ApexCharts === 'undefined') return;

                if (element.__dashboardChart) {
                    element.__dashboardChart.destroy();
                }

                element.innerHTML = '';
                element.__dashboardChart = new ApexCharts(element, options);
                element.__dashboardChart.render();
            };

            const initDashboardCharts = () => {
                if (typeof ApexCharts === 'undefined') {
                    window.setTimeout(initDashboardCharts, 100);
                    return;
                }

                const visitVsVisitor = dashboardChartPayload.visitVsVisitor;
                const visitVsVisitorEl = document.getElementById('visitVsVisitorChart');
                if (visitVsVisitorEl) {
                    renderDashboardChart(visitVsVisitorEl, {
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
                    });
                }

                const visitorChartEl = document.querySelector('#visitorsChart');
                if (visitorChartEl) {
                    renderDashboardChart(visitorChartEl, {
                        chart: {
                            type: 'area',
                            height: 320,
                            toolbar: { show: false },
                            zoom: { enabled: false },
                        },
                        series: dashboardChartPayload.visitorSeries,
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
                            categories: dashboardChartPayload.weeks,
                            labels: {
                                style: { colors: Array(dashboardChartPayload.weeks.length).fill('#94a3b8') },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: { colors: ['#94a3b8'] },
                            },
                        },
                        legend: { show: true },
                        tooltip: { shared: true },
                    });
                }

                dashboardChartPayload.pieCharts.forEach((chart) => {
                    const chartEl = document.getElementById(chart.id);
                    if (! chartEl) return;

                    renderDashboardChart(chartEl, {
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
                    });
                });

                const statusData = dashboardChartPayload.statusChart;
                const statusEl = document.getElementById('post-status-chart');
                if (statusEl) {
                    renderDashboardChart(statusEl, {
                        chart: {
                            type: 'bar',
                            height: 320,
                            toolbar: { show: false },
                        },
                        series: [
                            {
                                name: dashboardChartPayload.postsLabel,
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
                    });
                }
            };

            const initDashboardLayoutOptions = () => {
                const storageKey = 'admin-dashboard-widget-preferences';
                const widgetContainer = document.querySelector('[data-dashboard-widgets]');
                const widgets = Array.from(document.querySelectorAll('[data-dashboard-widget]'));
                const toggles = Array.from(document.querySelectorAll('[data-dashboard-visibility-toggle]'));
                const optionsToggle = document.querySelector('[data-dashboard-options-toggle]');
                const optionsPanel = document.querySelector('[data-dashboard-options-panel]');
                const resetButton = document.querySelector('[data-dashboard-reset]');

                if (! widgetContainer || widgets.length === 0) return;

                const defaultOrder = widgets.map((widget) => widget.dataset.dashboardWidget);

                const readPreferences = () => {
                    const defaults = { order: defaultOrder, hidden: [] };
                    let saved = null;

                    try {
                        saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
                    } catch (error) {
                        localStorage.removeItem(storageKey);
                    }

                    if (! saved || ! Array.isArray(saved.order) || ! Array.isArray(saved.hidden)) {
                        return defaults;
                    }

                    return {
                        order: [
                            ...saved.order.filter((id) => defaultOrder.includes(id)),
                            ...defaultOrder.filter((id) => ! saved.order.includes(id)),
                        ],
                        hidden: saved.hidden.filter((id) => defaultOrder.includes(id)),
                    };
                };

                const savePreferences = (preferences) => {
                    localStorage.setItem(storageKey, JSON.stringify(preferences));
                };

                const applyPreferences = (preferences = readPreferences()) => {
                    preferences.order.forEach((id) => {
                        const widget = widgets.find((item) => item.dataset.dashboardWidget === id);
                        if (widget) widgetContainer.appendChild(widget);
                    });

                    widgets.forEach((widget) => {
                        const isHidden = preferences.hidden.includes(widget.dataset.dashboardWidget);
                        widget.classList.toggle('hidden', isHidden);
                    });

                    toggles.forEach((toggle) => {
                        toggle.checked = ! preferences.hidden.includes(toggle.value);
                    });

                    window.dispatchEvent(new Event('resize'));
                };

                const getCurrentPreferences = () => ({
                    order: Array.from(widgetContainer.querySelectorAll('[data-dashboard-widget]')).map((widget) => widget.dataset.dashboardWidget),
                    hidden: toggles.filter((toggle) => ! toggle.checked).map((toggle) => toggle.value),
                });

                if (widgetContainer.dataset.dashboardLayoutReady !== 'true') {
                    optionsToggle?.addEventListener('click', () => {
                        optionsPanel?.classList.toggle('hidden');
                        optionsToggle.setAttribute('aria-expanded', String(! optionsPanel?.classList.contains('hidden')));
                    });

                    toggles.forEach((toggle) => {
                        toggle.addEventListener('change', () => {
                            const preferences = getCurrentPreferences();
                            applyPreferences(preferences);
                            savePreferences(preferences);
                        });
                    });

                    resetButton?.addEventListener('click', () => {
                        localStorage.removeItem(storageKey);
                        applyPreferences({ order: defaultOrder, hidden: [] });
                    });

                    let draggedWidget = null;

                    widgets.forEach((widget) => {
                        widget.classList.add('cursor-move', 'transition', 'duration-150');
                        widget.setAttribute('title', dashboardChartPayload.dragTitle);

                        widget.addEventListener('dragstart', (event) => {
                            draggedWidget = widget;
                            widget.classList.add('opacity-50', 'ring-2', 'ring-blue-400');
                            event.dataTransfer.effectAllowed = 'move';
                            event.dataTransfer.setData('text/plain', widget.dataset.dashboardWidget);
                        });

                        widget.addEventListener('dragend', () => {
                            widget.classList.remove('opacity-50', 'ring-2', 'ring-blue-400');
                            draggedWidget = null;
                            const preferences = getCurrentPreferences();
                            savePreferences(preferences);
                        });
                    });

                    widgetContainer.addEventListener('dragover', (event) => {
                        event.preventDefault();
                        if (! draggedWidget) return;

                        const afterElement = Array.from(widgetContainer.querySelectorAll('[data-dashboard-widget]:not(.opacity-50):not(.hidden)'))
                            .find((child) => {
                                const box = child.getBoundingClientRect();
                                const isSameRow = event.clientY >= box.top && event.clientY <= box.bottom;
                                const isBeforeColumnMidpoint = event.clientX < box.left + (box.width / 2);
                                const isBeforeRowMidpoint = event.clientY < box.top + (box.height / 2);

                                return (isSameRow && isBeforeColumnMidpoint) || (! isSameRow && isBeforeRowMidpoint);
                            });

                        if (afterElement) {
                            widgetContainer.insertBefore(draggedWidget, afterElement);
                        } else {
                            widgetContainer.appendChild(draggedWidget);
                        }
                    });

                    widgetContainer.addEventListener('drop', (event) => {
                        event.preventDefault();
                        const preferences = getCurrentPreferences();
                        savePreferences(preferences);
                    });

                    widgetContainer.dataset.dashboardLayoutReady = 'true';
                }

                applyPreferences();
            };

            window.initAdminDashboard = () => {
                if (! document.querySelector('[data-dashboard-widgets]')) return;

                initDashboardLayoutOptions();
                initDashboardCharts();
            };

            const bootDashboard = () => window.initAdminDashboard?.();

            if (! window.__adminDashboardLivewireListenersRegistered) {
                window.__adminDashboardLivewireListenersRegistered = true;
                document.addEventListener('livewire:navigated', bootDashboard);
                document.addEventListener('livewire:initialized', bootDashboard);
            }

            if (! window.__adminDashboardLivewireHookRegistered && window.Livewire?.hook) {
                window.__adminDashboardLivewireHookRegistered = true;
                window.Livewire.hook('message.processed', bootDashboard);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', bootDashboard, { once: true });
            } else {
                bootDashboard();
            }
        })();
    </script>
@endpush
</div>
