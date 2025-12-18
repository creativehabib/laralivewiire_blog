<div class="antialiased text-slate-900 dark:text-slate-100">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden" x-data="{ open: true }">
        <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button
                        @click="open = !open"
                        :class="open ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-slate-600'"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                            <span
                                :class="open ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                            </span>
                    </button>
                    <div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                            <i class="fas fa-sitemap text-indigo-500"></i>
                            Enable Sitemap?
                        </span>
                    </div>
                </div>

                <a href="{{ $sitemapUrl }}" target="_blank" rel="noopener" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 flex items-center gap-1">
                    <i class="fas fa-external-link-alt"></i>
                    {{ $sitemapUrl }}
                </a>
            </div>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 max-w-2xl">
                Automatically generate a sitemap.xml to help search engines index your content more efficiently.
            </p>
        </div>

        <div x-show="open" x-collapse x-cloak>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200">
                        <i class="fas fa-layer-group mr-1 text-slate-400"></i> Post Types to Include
                    </label>
                    <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700 rounded-lg p-2 max-h-56 overflow-y-auto">
                        <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-white dark:hover:bg-slate-700 transition cursor-pointer group">
                            <input type="checkbox" checked class="h-4 w-4 text-indigo-600 border-slate-300 dark:border-slate-600 rounded focus:ring-indigo-500">
                            <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Posts</span>
                        </label>
                        <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-white dark:hover:bg-slate-700 transition cursor-pointer group">
                            <input type="checkbox" checked class="h-4 w-4 text-indigo-600 border-slate-300 dark:border-slate-600 rounded focus:ring-indigo-500">
                            <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Pages</span>
                        </label>
                        <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-white dark:hover:bg-slate-700 transition cursor-pointer group">
                            <input type="checkbox" class="h-4 w-4 text-indigo-600 border-slate-300 dark:border-slate-600 rounded focus:ring-indigo-500">
                            <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Media Attachments</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Update Frequency</label>
                        <select class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                            <option>Always</option>
                            <option>Hourly</option>
                            <option selected>Daily</option>
                            <option>Weekly</option>
                            <option>Monthly</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Priority (0.0 - 1.0)</label>
                        <select class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border">
                            <option>1.0</option>
                            <option>0.9</option>
                            <option selected>0.8</option>
                            <option>0.5</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-1 flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/30 rounded-lg border border-slate-100 dark:border-slate-700/50 mt-1">
                        <div>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 block">Include Images</span>
                            <span class="text-[10px] uppercase tracking-wider text-slate-400">Media indexing</span>
                        </div>
                        <button x-data="{ on: true }" @click="on = !on" :class="on ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200">
                            <span :class="on ? 'translate-x-4' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-6 pb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-100 dark:border-slate-700 pt-6">
                    <div class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-700">
                        <span class="mr-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300">
                            <i class="fas fa-link text-sm"></i>
                        </span>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Sitemap URL</span>
                            <a href="{{ $sitemapUrl }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 underline">View XML</a>
                        </div>
                    </div>

                    <div class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-700">
                        <span class="mr-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300">
                            <i class="fas fa-bolt text-sm"></i>
                        </span>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Status</span>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Live & Syncing</span>
                        </div>
                    </div>

                    <div class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-700">
                        <span class="mr-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300">
                            <i class="fas fa-file-invoice text-sm"></i>
                        </span>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Capacity</span>
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">1,000 / page</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-start gap-3 rounded-xl border border-blue-100 dark:border-blue-900/50 bg-blue-50 dark:bg-blue-900/20 px-4 py-3 text-blue-800 dark:text-blue-200">
                    <i class="fas fa-info-circle mt-0.5 text-blue-500"></i>
                    <p class="text-xs leading-relaxed">
                        <strong>Pro Tip:</strong> After enabling your sitemap, submit the URL to <a href="#" class="underline font-bold">Google Search Console</a> to accelerate indexing.
                    </p>
                </div>

                <div class="mt-6">
                    <label for="sitemapItems" class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-200">
                        <i class="fas fa-list-numeric text-slate-400"></i>
                        Items per page
                    </label>
                    <div class="mt-2 flex items-center gap-4">
                        <input
                            type="number"
                            id="sitemapItems"
                            class="block w-32 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
                            value="1000"
                        >
                        <span class="text-xs text-slate-500 dark:text-slate-400">Maximum recommended: 50,000</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-700" x-data="{ openInow: false }">
                <button @click="openInow = !openInow" class="w-full px-6 py-4 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i :class="openInow ? 'fa-chevron-down' : 'fa-chevron-right'" class="fas text-slate-400 text-xs transition-transform"></i>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                             <i class="fas fa-paper-plane text-sky-500"></i>
                             IndexNow Integration
                        </span>
                    </div>
                    <span :class="openInow ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500'" class="text-[10px] px-2 py-0.5 rounded font-bold uppercase">
                        Instant Ping
                    </span>
                </button>

                <div x-show="openInow" x-collapse x-cloak class="px-6 pb-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">API Key</label>
                            <div class="flex rounded-lg shadow-sm">
                                <div class="relative flex-grow">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-slate-400 text-xs"></i>
                                    </div>
                                    <input type="text" class="block w-full rounded-l-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 pl-9 text-sm py-2 border focus:ring-indigo-500" value="abc-123-xyz-secure-key">
                                </div>
                                <button class="px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-l-0 border-slate-300 dark:border-slate-600 rounded-r-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                                    <i class="fas fa-redo-alt mr-1"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Verification Status</label>
                            <div class="flex items-center gap-2 p-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 rounded-lg">
                                <i class="fas fa-check-circle text-emerald-500"></i>
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Key hosted at root successfully</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Instant Indexing Support</h4>
                        <div class="flex flex-wrap gap-3">
                            <span class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-full text-xs font-medium">
                                <i class="fab fa-microsoft text-blue-500"></i> Bing
                            </span>
                            <span class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-full text-xs font-medium">
                                <i class="fab fa-yandex text-red-500"></i> Yandex
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                <i class="fas fa-clock"></i>
                Last generated: 2 mins ago
            </span>
            <button class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md shadow-indigo-200 dark:shadow-none transition-all active:scale-95">
                <i class="fas fa-save mr-2"></i> Save Settings
            </button>
        </div>
    </div>
</div>
