<div>
    <!-- Page header -->
    <header class="mb-6 border-b border-slate-200 dark:border-slate-700 pb-4">
        <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-slate-100">
            Sitemap Settings
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Configure sitemap visibility, pagination behaviour and IndexNow notifications.
        </p>
    </header>

    <!-- Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow border border-slate-200 dark:border-slate-700">
        <form wire:submit.prevent="save">
            <div class="p-4 md:p-6">
                <!-- Enable sitemap switch -->
                <div class="pb-3 mb-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="relative inline-flex items-center">
                                <input
                                    type="checkbox"
                                    id="enableSitemap"
                                    wire:model.defer="sitemap_enabled"
                                    class="peer sr-only"
                                >
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 peer-checked:bg-indigo-600 transition-colors"></div>
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white dark:bg-slate-100 rounded-full shadow transform peer-checked:translate-x-5 transition-transform"></div>
                            </div>
                            <label for="enableSitemap" class="text-sm font-medium text-slate-800 dark:text-slate-100 cursor-pointer">
                                Enable sitemap?
                            </label>
                        </div>
                    </div>
                    <small class="mt-2 block text-xs text-slate-500 dark:text-slate-400">
                        When enabled, a sitemap.xml file will be automatically generated and accessible at
                        <a href="{{ $sitemapUrl }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 underline">
                            {{ $sitemapUrl }}
                        </a>
                        to help search engines better index your site.
                    </small>
                </div>

                <!-- Info box -->
                <div class="p-4 mb-4 rounded-lg bg-slate-50 dark:bg-slate-800/60">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <div>
                            <h5 class="mb-1 text-sm font-semibold text-slate-800 dark:text-slate-100">How Sitemap Works</h5>
                            <p class="mb-0 text-xs text-slate-500 dark:text-slate-400">
                                Your sitemap is automatically generated and updated whenever content changes. It helps search engines discover and index your website content more efficiently.
                            </p>
                        </div>
                    </div>

                    <hr class="my-4 border-slate-200 dark:border-slate-700">

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="flex items-center">
                            <span class="mr-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 dark:bg-sky-900/40 text-sky-600 dark:text-sky-300">
                                <i class="fas fa-link"></i>
                            </span>
                            <div>
                                <strong class="text-sm text-slate-800 dark:text-slate-100">Sitemap URL</strong><br>
                                <a
                                    href="{{ $sitemapUrl }}"
                                    class="inline-flex items-center mt-1 px-3 py-1 text-xs font-medium border border-indigo-500 text-indigo-600 dark:text-indigo-300 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/40 transition-colors"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    View Sitemap
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300">
                                <i class="fas fa-sync-alt"></i>
                            </span>
                            <div>
                                <strong class="text-sm text-slate-800 dark:text-slate-100">Automatic Generation</strong><br>
                                <small class="text-xs text-slate-500 dark:text-slate-400">Updates automatically when content changes</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                @if (! $sitemap_enabled)
                    <div
                        class="mb-4 flex items-start gap-2 rounded-lg border border-amber-200 dark:border-amber-500/50 bg-amber-50 dark:bg-amber-900/30 px-4 py-3 text-amber-800 dark:text-amber-100"
                        role="alert"
                    >
                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                        <div class="text-xs">
                            The sitemap is currently disabled. Search engines will no longer be able to crawl sitemap.xml while this setting is off.
                        </div>
                    </div>
                @else
                    <div
                        class="mb-4 flex items-start gap-2 rounded-lg border border-emerald-200 dark:border-emerald-500/50 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-emerald-800 dark:text-emerald-100"
                        role="alert"
                    >
                        <i class="fas fa-check-circle mt-0.5"></i>
                        <div class="text-xs">
                            The sitemap updates automatically whenever you create, edit, or delete content on your website.
                        </div>
                    </div>
                @endif

                <!-- Items per page -->
                <div class="mt-4">
                    <label for="sitemapItems" class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Sitemap items per page
                    </label>
                    <input
                        type="number"
                        id="sitemapItems"
                        wire:model.defer="sitemap_items_per_page"
                        min="1"
                        max="50000"
                        class="mt-1 block w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40"
                    >
                    <small class="mt-1 block text-xs text-slate-500 dark:text-slate-400">
                        The number of items to include in each sitemap page. Larger values may improve sitemap generation performance but could cause issues with very large sites. Default: 1000
                    </small>
                    @error('sitemap_items_per_page')
                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- IndexNow checkbox -->
                <div class="mt-4">
                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="enableIndexNow"
                            wire:model.defer="sitemap_enable_index_now"
                            class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                        >
                        <label for="enableIndexNow" class="text-sm font-medium text-slate-800 dark:text-slate-100 cursor-pointer">
                            Enable IndexNow?
                        </label>
                    </div>
                    <small class="mt-1 block text-xs text-slate-500 dark:text-slate-400">
                        Automatically notify search engines (Bing, Yandex, Seznam, Naver) when your content is updated using the modern IndexNow protocol for instant indexing.
                    </small>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end border-t border-slate-200 dark:border-slate-700 px-4 py-3">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900 disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove class="flex items-center">
                        <i class="fas fa-save mr-1"></i> Save settings
                    </span>
                    <span
                        wire:loading
                        class="inline-flex h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"
                        role="status"
                        aria-hidden="true"
                    ></span>
                </button>
            </div>
        </form>
    </div>
</div>
