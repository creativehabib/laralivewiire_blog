<div class="text-slate-900 dark:text-slate-100">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
         x-data="{ open: @entangle('sitemap_enabled') }">

        <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button
                        wire:click="$toggle('sitemap_enabled')"
                        type="button"
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
                <a href="{{ $sitemapUrl }}" target="_blank" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 flex items-center gap-1">
                    <i class="fas fa-external-link-alt"></i> {{ $sitemapUrl }}
                </a>
            </div>
        </div>

        <div x-show="open" x-collapse x-cloak>
            <div class="p-6 space-y-6">

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-3">
                        <i class="fas fa-layer-group mr-1 text-slate-400"></i> Post Types to Include
                    </label>
                    <div class="flex flex-wrap gap-4 bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                        @foreach($availableTypes as $type)
                            <label class="flex items-center space-x-3 cursor-pointer group select-none">
                                <input type="checkbox" wire:model.live="sitemap_post_types" value="{{ $type }}" class="h-4 w-4 border-slate-300 rounded focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-300 uppercase">{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                @if(count($sitemap_post_types) > 0)
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-3">
                            <i class="fas fa-sliders-h mr-1 text-slate-400"></i> Configuration per Type
                        </label>
                        <div class="overflow-hidden border border-slate-200 dark:border-slate-700 rounded-lg">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Frequency</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Priority</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($sitemap_post_types as $type)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 capitalize">
                                                {{ $type }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select wire:model="type_settings.{{ $type }}.frequency" class="block w-full px-2 py-1.5 text-sm rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="always">Always</option>
                                                <option value="hourly">Hourly</option>
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="yearly">Yearly</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select wire:model="type_settings.{{ $type }}.priority" class="block w-full px-2 py-1.5 text-sm rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="1.0">1.0 (Highest)</option>
                                                <option value="0.9">0.9</option>
                                                <option value="0.8">0.8</option>
                                                <option value="0.6">0.6</option>
                                                <option value="0.5">0.5 (Normal)</option>
                                                <option value="0.4">0.4</option>
                                                <option value="0.2">0.2</option>
                                                <option value="0.1">0.1 (Lowest)</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-100 dark:border-slate-700 pt-6">
                        <div class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-700">
                        <span class="mr-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300">
                            <i class="fas fa-link text-sm"></i>
                        </span>
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-slate-400">Sitemap URL</span>
                                <a href="{{ $sitemapUrl }}" target="_blank" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 underline">View XML</a>
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
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ number_format($sitemap_items_per_page) }} / page</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-start gap-3 rounded-xl border border-blue-100 dark:border-blue-900/50 bg-blue-50 dark:bg-blue-900/20 px-4 py-3 text-blue-800 dark:text-blue-200">
                        <i class="fas fa-info-circle mt-0.5 text-blue-500"></i>
                        <p class="text-xs leading-relaxed">
                            <strong>Pro Tip:</strong> After enabling your sitemap, submit the URL to <a href="https://search.google.com/search-console" target="_blank" class="underline font-bold">Google Search Console</a> to accelerate indexing.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/30 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200 block">Include Images</span>
                            <span class="text-xs text-slate-500">Include featured images in sitemap</span>
                        </div>
                        <button
                            wire:click="$toggle('sitemap_include_images')"
                            type="button"
                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200"
                            :class="$wire.sitemap_include_images ? 'bg-sky-500' : 'bg-slate-300 dark:bg-slate-600'">
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200" :class="$wire.sitemap_include_images ? 'translate-x-4' : 'translate-x-0'"></span>
                        </button>
                    </div>

                    <div>
                        <label for="sitemapItems" class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-200">
                            <i class="fas fa-list-numeric text-slate-400"></i>
                            Items per page
                        </label>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Maximum recommended: 50,000</span>
                        <div class="mt-2 flex items-center gap-4">
                            <input
                                type="number"
                                id="sitemapItems"
                                wire:model="sitemap_items_per_page"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-700" x-data="{ openInow: false }">
                <button @click="openInow = !openInow" class="w-full px-6 py-4 flex justify-between items-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
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
                                    <input type="text" readonly wire:model="indexnow_key" class="block w-full rounded-l-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 pl-9 text-sm py-2 border focus:ring-indigo-500">
                                </div>
                                <button wire:click="generateIndexNowKey" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 cursor-pointer border border-l-0 border-slate-300 dark:border-slate-600 rounded-r-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition" title="Generate New Key">
                                    <i class="fas fa-redo-alt mr-1"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Key Location</label>
                            <div class="flex rounded-lg shadow-sm">
                                <div class="relative flex-grow flex items-center bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-l-lg px-3 py-2 overflow-hidden">
                                    <i class="fas fa-file-alt text-slate-400 text-xs mr-2 flex-shrink-0"></i>
                                    <span class="text-sm font-mono text-slate-600 dark:text-slate-300 truncate select-all" title="{{ $keyLocation }}">
                                        {{ $keyLocation }}
                                    </span>
                                </div>
                                <a href="{{ url($indexnow_key . '.txt') }}"
                                   target="_blank"
                                   class="px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-l-0 border-slate-300 dark:border-slate-600 rounded-r-lg text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:bg-slate-200 dark:hover:bg-slate-600 hover:text-indigo-700 transition flex items-center gap-2 cursor-pointer group"
                                   title="View File in Browser">
                                    <span>View</span>
                                    <i class="fas fa-external-link-alt group-hover:scale-110 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Instant Indexing Support</h4>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://www.bing.com/webmasters" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-full text-xs font-medium text-slate-600 dark:text-slate-300 hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all shadow-sm hover:shadow-md cursor-pointer group">
                                <i class="fab fa-microsoft text-blue-500 group-hover:scale-110 transition-transform"></i> Bing
                            </a>

                            <a href="https://webmaster.yandex.com/" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-full text-xs font-medium text-slate-600 dark:text-slate-300 hover:border-red-400 hover:text-red-600 dark:hover:text-red-400 transition-all shadow-sm hover:shadow-md cursor-pointer group">
                                <i class="fab fa-yandex text-red-500 group-hover:scale-110 transition-transform"></i> Yandex
                            </a>

                            <a href="https://reporter.seznam.cz/" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-full text-xs font-medium text-slate-600 dark:text-slate-300 hover:border-red-400 hover:text-red-600 dark:hover:text-red-400 transition-all shadow-sm hover:shadow-md cursor-pointer group">
                                <i class="fas fa-s text-red-600 font-bold group-hover:scale-110 transition-transform"></i> Seznam.cz
                            </a>

                            <a href="https://searchadvisor.naver.com/" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-full text-xs font-medium text-slate-600 dark:text-slate-300 hover:border-green-400 hover:text-green-600 dark:hover:text-green-400 transition-all shadow-sm hover:shadow-md cursor-pointer group">
                                <i class="fas fa-n text-green-500 font-bold group-hover:scale-110 transition-transform"></i> Naver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 disabled:cursor-not-allowed cursor-pointer text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition-all active:scale-95 flex items-center">

                <span wire:loading.remove wire:target="save" class="flex items-center"><i class="fas fa-save mr-2"></i> Save Changes</span>

                <span wire:loading wire:target="save" class="flex items-center"><i class="fas fa-circle-notch fa-spin mr-2"></i> Saving...</span>
            </button>
        </div>
    </div>
</div>
