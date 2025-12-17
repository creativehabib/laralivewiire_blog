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

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" x-data="{ open: true }">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-100 text-indigo-600 p-2 rounded-md">
                    <i class="fas fa-sitemap text-lg"></i>া
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Sitemap Settings</h3>
                    <p class="text-xs text-gray-500">Generate XML sitemaps for your content.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-gray-700">Enable Sitemap</span>
                <button
                    @click="open = !open"
                    :class="open ? 'bg-indigo-600' : 'bg-gray-300'"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                        <span
                            :class="open ? 'translate-x-5' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                        </span>
                </button>
            </div>
        </div>

        <div x-show="open" x-collapse x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Post Types to Include</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-3 max-h-48 overflow-y-auto">
                        <label class="flex items-center space-x-3 mb-2 p-2 rounded hover:bg-white transition cursor-pointer">
                            <input type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Posts</span>
                        </label>
                        <label class="flex items-center space-x-3 mb-2 p-2 rounded hover:bg-white transition cursor-pointer">
                            <input type="checkbox" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Pages</span>
                        </label>
                        <label class="flex items-center space-x-3 mb-2 p-2 rounded hover:bg-white transition cursor-pointer">
                            <input type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Products</span>
                        </label>
                        <label class="flex items-center space-x-3 p-2 rounded hover:bg-white transition cursor-pointer">
                            <input type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Media Attachments</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Select which content types should be included in the sitemap.</p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Update Frequency</label>
                        <select class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                            <option>Always</option>
                            <option>Hourly</option>
                            <option selected>Daily</option>
                            <option>Weekly</option>
                            <option>Monthly</option>
                            <option>Yearly</option>
                            <option>Never</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Priority (0.0 - 1.0)</label>
                        <select class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border bg-white">
                            <option>1.0</option>
                            <option>0.9</option>
                            <option selected>0.8</option>
                            <option>0.7</option>
                            <option>0.5</option>
                            <option>0.3</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between border-t pt-4 border-gray-100">
                        <div>
                            <span class="text-sm font-medium text-gray-700 block">Include Images</span>
                            <span class="text-xs text-gray-500">Add image references to sitemap</span>
                        </div>
                        <button x-data="{ on: false }" @click="on = !on" :class="on ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                            <span :class="on ? 'translate-x-4' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" x-data="{ open: false }">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-orange-100 text-orange-600 p-2 rounded-md">
                            <i class="fas fa-bolt text-lg px-1"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">IndexNow Settings</h3>
                            <p class="text-xs text-gray-500">Instant indexing for Bing, Yandex & others.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700">Enable IndexNow</span>
                        <button
                            @click="open = !open"
                            :class="open ? 'bg-indigo-600' : 'bg-gray-300'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                        <span
                            :class="open ? 'translate-x-5' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                        </span>
                        </button>
                    </div>
                </div>

                <div x-show="open" x-collapse x-cloak>
                    <div class="p-6 space-y-6">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">API Key <span class="text-red-500">*</span></label>
                            <div class="flex rounded-md shadow-sm">
                                <div class="relative flex-grow focus-within:z-10">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="text" class="form-input block w-full rounded-none rounded-l-md border-gray-300 pl-10 py-2 border focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Ex: 48db33..." value="abc-123-xyz-secure-key">
                                </div>
                                <button type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 bg-gray-50">
                                    <i class="fas fa-sync text-gray-500"></i>
                                    Generate
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">This key is used to authenticate your ownership of the domain.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Key Location</label>
                            <div class="flex rounded-md shadow-sm">
                                <input type="text" class="form-input block w-full rounded-none rounded-l-md border-gray-300 bg-gray-100 py-2 px-3 border text-gray-500 sm:text-sm" value="https://yourwebsite.com/abc-123-xyz-secure-key.txt" readonly>
                                <button type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-4 py-2 text-sm font-semibold text-indigo-600 ring-1 ring-inset ring-gray-300 hover:bg-indigo-50 bg-white">
                                    <i class="fas fa-check-circle"></i>
                                    Verify
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Ensure this file is accessible publicly at the root of your domain.</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-md p-4">
                            <h4 class="text-sm font-bold text-blue-800 mb-2">Supported Search Engines</h4>
                            <div class="flex gap-4">
                                <span class="inline-flex items-center rounded-md bg-white px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">Bing</span>
                                <span class="inline-flex items-center rounded-md bg-white px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">Yandex</span>
                                <span class="inline-flex items-center rounded-md bg-white px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">Seznam.cz</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-right">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition">
                    Save Changes
                </button>
            </div>
        </div>
    </div>


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
