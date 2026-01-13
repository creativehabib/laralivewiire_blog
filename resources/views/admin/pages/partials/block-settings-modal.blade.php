<div x-show="showBlockSettingsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4">
    <div class="w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
            <div>
                <h3 class="text-base font-semibold">Edit Block</h3>
                <p class="text-xs text-white/70" x-text="findActiveBlock()?.name ?? ''"></p>
            </div>
            <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold cursor-pointer hover:bg-sky-500" @click="showBlockSettingsModal = false">
                Done
            </button>
        </div>
        <div class="flex bg-sky-600 text-xs font-semibold text-white">
            <button type="button" class="relative px-5 py-3 cursor-pointer" :class="blockTab === 'general' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'general'">
                General
                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'general'"></span>
            </button>
            <button type="button" class="relative px-5 py-3 cursor-pointer" :class="blockTab === 'styling' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'styling'">
                Styling Settings
                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'styling'"></span>
            </button>
            <button type="button" class="relative px-5 py-3 cursor-pointer" :class="blockTab === 'advanced' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'advanced'">
                Advanced Settings
                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'advanced'"></span>
            </button>
        </div>
        <div class="max-h-[70vh] overflow-y-auto bg-slate-50 p-6 dark:bg-slate-800">
            <div class="space-y-5" x-show="blockTab === 'general'" x-cloak>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Custom Title (optional)</label>
                    <input type="text"
                           class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                           :value="findActiveBlock()?.settings?.title ?? ''"
                           @input="updateActiveBlockField('title', $event.target.value)"
                           placeholder="Block Title">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Icon (optional)</label>
                    <div class="flex items-center gap-3 max-w-md">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-indigo-500 text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10">
                            <i class="fa-solid fa-star"></i>
                        </span>
                        <input type="text"
                               class="flex-1 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                               :value="findActiveBlock()?.settings?.icon ?? ''"
                               @input="updateActiveBlockField('icon', $event.target.value)"
                               placeholder="fa-solid fa-star">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Title URL (optional)</label>
                    <input type="url"
                           class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                           :value="findActiveBlock()?.settings?.url ?? ''"
                           @input="updateActiveBlockField('url', $event.target.value)"
                           placeholder="https://">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Categories</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach ($categories as $category)
                            <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                <input type="checkbox"
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                       :checked="(findActiveBlock()?.settings?.categories ?? []).includes({{ $category['id'] }})"
                                       @change="toggleActiveBlockCategory({{ $category['id'] }})">
                                <span>{{ $category['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Tags</label>
                    <div>
                        <input type="text"
                               class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                               :value="findActiveBlock()?.settings?.tags ?? ''"
                               @input="updateActiveBlockField('tags', $event.target.value)"
                               placeholder="Enter tag names separated by commas.">
                        <p class="mt-2 text-xs text-slate-500">Enter a tag name, or names separated by comma.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Trending Posts</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.trending ?? false"
                                   @change="updateActiveBlockField('trending', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                        <span class="text-xs text-slate-500">Only show posts marked as trending</span>
                    </label>
                </div>
            </div>

            <div class="space-y-5" x-show="blockTab === 'styling'" x-cloak>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Show the content only?</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.contentOnly ?? false"
                                   @change="updateActiveBlockField('contentOnly', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                        <span class="text-xs text-slate-500">Without background, padding nor borders.</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Dark Mode</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.darkMode ?? false"
                                   @change="updateActiveBlockField('darkMode', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Primary Color</label>
                    <input type="color"
                           class="h-10 w-14 rounded border border-slate-300 bg-white"
                           :value="findActiveBlock()?.settings?.primaryColor ?? ''"
                           @input="updateActiveBlockField('primaryColor', $event.target.value)">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Background Color</label>
                    <input type="color"
                           class="h-10 w-14 rounded border border-slate-300 bg-white"
                           :value="findActiveBlock()?.settings?.backgroundColor ?? ''"
                           @input="updateActiveBlockField('backgroundColor', $event.target.value)">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Secondary Color</label>
                    <input type="color"
                           class="h-10 w-14 rounded border border-slate-300 bg-white"
                           :value="findActiveBlock()?.settings?.secondaryColor ?? ''"
                           @input="updateActiveBlockField('secondaryColor', $event.target.value)">
                </div>
            </div>

            <div class="space-y-5" x-show="blockTab === 'advanced'" x-cloak>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Ajax Filters</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.ajaxFilters ?? false"
                                   @change="updateActiveBlockField('ajaxFilters', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                        <span class="text-xs text-slate-500">Will not appear if the numeric pagination is active.</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">More Button</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.moreButton ?? false"
                                   @change="updateActiveBlockField('moreButton', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                        <span class="text-xs text-slate-500">Will not appear if the Block URL is empty.</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Posts Title Length</label>
                    <input type="number" min="0"
                           class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                           :value="findActiveBlock()?.settings?.titleLength ?? ''"
                           @input="updateActiveBlockField('titleLength', $event.target.value)">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Posts Excerpt</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.showExcerpt ?? true"
                                   @change="updateActiveBlockField('showExcerpt', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Posts Excerpt Length</label>
                    <input type="number" min="0"
                           class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                           :value="findActiveBlock()?.settings?.excerptLength ?? ''"
                           @input="updateActiveBlockField('excerptLength', $event.target.value)">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Read More Button</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.readMoreButton ?? false"
                                   @change="updateActiveBlockField('readMoreButton', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Hide thumbnail for the First post</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.hideFirstThumbnail ?? false"
                                   @change="updateActiveBlockField('hideFirstThumbnail', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Hide small thumbnails</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.hideSmallThumbnails ?? false"
                                   @change="updateActiveBlockField('hideSmallThumbnails', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Post Meta</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.postMeta ?? true"
                                   @change="updateActiveBlockField('postMeta', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Media Icon</label>
                    <label class="inline-flex items-center gap-3">
                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                            <input type="checkbox" class="peer sr-only"
                                   :checked="findActiveBlock()?.settings?.mediaIcon ?? false"
                                   @change="updateActiveBlockField('mediaIcon', $event.target.checked)">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Exclude Posts</label>
                    <div>
                        <input type="text"
                               class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                               :value="findActiveBlock()?.settings?.exclude ?? ''"
                               @input="updateActiveBlockField('exclude', $event.target.value)"
                               placeholder="Enter a post ID, or IDs separated by comma.">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Sort by</label>
                    <select class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            :value="findActiveBlock()?.settings?.sort ?? 'recent'"
                            @change="updateActiveBlockField('sort', $event.target.value)">
                        <option value="recent">Recent Posts</option>
                        <option value="random">Random Posts</option>
                        <option value="featured">Featured Posts</option>
                        <option value="last_modified">Last Modified Posts</option>
                        <option value="most_commented">Most Commented posts</option>
                        <option value="alphabetical">Alphabetically</option>
                        <option value="most_viewed">Most Viewed posts</option>
                        <option value="most_viewed_7_days">Most Viewed for 7 days</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Order</label>
                    <select class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            :value="findActiveBlock()?.settings?.order ?? 'desc'"
                            @change="updateActiveBlockField('order', $event.target.value)">
                        <option value="desc">Descending</option>
                        <option value="asc">Ascending</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Number of posts to show</label>
                    <input type="number" min="1"
                           class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                           :value="findActiveBlock()?.settings?.count ?? 5"
                           @input="updateActiveBlockField('count', Number($event.target.value))">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Offset - number of posts to pass over</label>
                    <input type="number" min="0"
                           class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                           :value="findActiveBlock()?.settings?.offset ?? 0"
                           @input="updateActiveBlockField('offset', Number($event.target.value))">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Published in the last (days)</label>
                    <input type="number" min="0"
                           class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                           :value="findActiveBlock()?.settings?.days ?? ''"
                           @input="updateActiveBlockField('days', $event.target.value)">
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Pagination</label>
                    <select class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            :value="findActiveBlock()?.settings?.pagination ?? 'disable'"
                            @change="updateActiveBlockField('pagination', $event.target.value)">
                        <option value="disable">Disable</option>
                        <option value="numeric">Numeric</option>
                        <option value="ajax-show-more">AJAX - Show More</option>
                        <option value="ajax-load-more">AJAX - Load More</option>
                        <option value="ajax-next-prev">AJAX - Next/Previous Buttons</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
