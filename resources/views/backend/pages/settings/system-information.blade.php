<x-layouts.app :title="__('System Information')">
    <div class="h-full w-full rounded-xl">
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl
                   border border-slate-200 dark:border-slate-700
                   bg-white dark:bg-slate-900
                   p-4 sm:p-6"
        >
            <header class="mb-6 flex flex-wrap items-start justify-between gap-3">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                        <span class="text-[11px] font-medium uppercase tracking-[0.12em] text-slate-600 dark:text-slate-300">
                            {{ __('System Information') }}
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-100">
                        {{ __('System Information') }}
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 max-w-2xl">
                        {{ __('Review the application, server, database, and PHP configuration details for troubleshooting and audits.') }}
                    </p>
                </div>
            </header>

            @php
                $sections = [
                    [
                        'title' => __('System Environment'),
                        'description' => __('Application configuration and locale details.'),
                        'data' => $systemEnvironment,
                    ],
                    [
                        'title' => __('Server Environment'),
                        'description' => __('Hosting server and runtime details.'),
                        'data' => $serverEnvironment,
                    ],
                    [
                        'title' => __('Database Information'),
                        'description' => __('Active database connection settings.'),
                        'data' => $databaseInformation,
                    ],
                    [
                        'title' => __('PHP Configuration'),
                        'description' => __('Key PHP limits and version details.'),
                        'data' => $phpConfiguration,
                    ],
                ];
            @endphp

            <div class="grid gap-4 lg:grid-cols-2">
                @foreach ($sections as $section)
                    <section class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/70 shadow-sm">
                        <div class="border-b border-slate-200 dark:border-slate-800 px-4 py-3">
                            <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ $section['title'] }}
                            </h2>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                {{ $section['description'] }}
                            </p>
                        </div>

                        <dl class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @foreach ($section['data'] as $label => $value)
                                <div class="flex flex-col gap-1 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        {{ $label }}
                                    </dt>
                                    <dd class="text-sm font-medium text-slate-900 dark:text-slate-100 sm:text-right">
                                        {{ $value ?: __('N/A') }}
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </section>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
