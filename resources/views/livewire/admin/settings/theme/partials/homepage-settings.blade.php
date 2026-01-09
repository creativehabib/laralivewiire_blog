<form wire:submit.prevent="saveHomepage" class="space-y-8" x-data="{ activeTab: 'general', selectedBlock: 1 }">
    @php
        $blocks = [
            ['id' => 1, 'label' => __('Block #1')],
            ['id' => 2, 'label' => __('Block #2')],
            ['id' => 3, 'label' => __('Block #3')],
            ['id' => 4, 'label' => __('Block #4')],
            ['id' => 5, 'label' => __('Block #5')],
            ['id' => 6, 'label' => __('Block #6')],
        ];
    @endphp

    <div class="space-y-4">
        <div>
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('Homepage Builder') }}</h3>
            <p class="text-xs text-slate-500">{{ __('Choose a block layout and customize its settings just like a page builder.') }}</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
            @foreach($blocks as $block)
                <button type="button"
                        class="rounded-md border bg-white dark:bg-slate-900 p-3 text-left transition shadow-sm"
                        :class="selectedBlock === {{ $block['id'] }} ? 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-500/40' : 'border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-500'"
                        @click="selectedBlock = {{ $block['id'] }}">
                    <div class="flex flex-col gap-2">
                        <div class="space-y-1">
                            <div class="h-3 w-6 rounded bg-slate-700/80 dark:bg-slate-200/80"></div>
                            <div class="grid grid-cols-2 gap-1">
                                <div class="h-3 rounded bg-slate-200 dark:bg-slate-800"></div>
                                <div class="h-3 rounded bg-slate-200 dark:bg-slate-800"></div>
                                <div class="h-3 rounded bg-slate-200 dark:bg-slate-800"></div>
                                <div class="h-3 rounded bg-slate-200 dark:bg-slate-800"></div>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $block['label'] }}</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <div class="rounded-md border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="flex flex-wrap bg-indigo-600 text-white text-sm font-medium">
            <button type="button" class="px-4 py-3"
                    :class="activeTab === 'general' ? 'bg-indigo-700' : 'bg-indigo-600 hover:bg-indigo-500'"
                    @click="activeTab = 'general'">
                {{ __('General') }}
            </button>
            <button type="button" class="px-4 py-3"
                    :class="activeTab === 'styling' ? 'bg-indigo-700' : 'bg-indigo-600 hover:bg-indigo-500'"
                    @click="activeTab = 'styling'">
                {{ __('Styling Settings') }}
            </button>
            <button type="button" class="px-4 py-3"
                    :class="activeTab === 'advanced' ? 'bg-indigo-700' : 'bg-indigo-600 hover:bg-indigo-500'"
                    @click="activeTab = 'advanced'">
                {{ __('Advanced Settings') }}
            </button>
        </div>

        <div class="p-6 space-y-6 bg-white dark:bg-slate-900" x-show="activeTab === 'general'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">{{ __('Custom Title (optional)') }}</label>
                <input type="text" wire:model.defer="homepageBlockTitle"
                       class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"
                       placeholder="{{ __('Block Title') }}">
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">{{ __('Icon (optional)') }}</label>
                <div class="flex items-center gap-3 max-w-md">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-indigo-500 text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10">
                        <i class="fa-solid fa-star"></i>
                    </span>
                    <input type="text" wire:model.defer="homepageBlockIcon"
                           class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"
                           placeholder="fa-solid fa-star">
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">{{ __('Title URL (optional)') }}</label>
                <input type="url" wire:model.defer="homepageBlockUrl"
                       class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"
                       placeholder="https://">
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">{{ __('Categories') }}</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($categories as $category)
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" value="{{ $category['id'] }}" wire:model.defer="homepageBlockCategories"
                                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $category['name'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">{{ __('Tags') }}</label>
                <div>
                    <input type="text" wire:model.defer="homepageBlockTags"
                           class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"
                           placeholder="{{ __('Enter tag names separated by commas.') }}">
                    <p class="text-xs text-slate-500 mt-2">{{ __('Enter a tag name, or names separated by comma.') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-center">
                <label class="text-sm text-slate-600 dark:text-slate-300">{{ __('Trending Posts') }}</label>
                <label class="inline-flex items-center gap-3">
                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition">
                        <input type="checkbox" wire:model.defer="homepageBlockTrending" class="peer sr-only">
                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5 peer-checked:bg-indigo-600"></span>
                    </span>
                    <span class="text-xs text-slate-500">{{ __('Only show posts marked as trending') }}</span>
                </label>
            </div>
        </div>

        <div class="p-6 space-y-6 bg-white dark:bg-slate-900" x-show="activeTab === 'styling'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">{{ __('Block Style') }}</label>
                <select wire:model.defer="homepageBlockStyle"
                        class="w-full max-w-xs px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                    <option value="standard">{{ __('Standard') }}</option>
                    <option value="card">{{ __('Card') }}</option>
                    <option value="overlay">{{ __('Overlay') }}</option>
                    <option value="split">{{ __('Split') }}</option>
                </select>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4 items-start">
                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">{{ __('Columns') }}</label>
                <input type="number" min="1" max="4" wire:model.defer="homepageBlockColumns"
                       class="w-full max-w-xs px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
            </div>
            <div class="rounded-md border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 p-4 text-xs text-slate-500">
                {{ __('Use the Layout tab for global typography and color settings.') }}
            </div>
        </div>

        <div class="p-6 space-y-6 bg-white dark:bg-slate-900" x-show="activeTab === 'advanced'" x-cloak>
            <div class="rounded-md border border-slate-200 dark:border-slate-700 p-4 space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Featured Slider') }}</p>
                        <p class="text-xs text-slate-500">{{ __('Show a hero slider on the homepage.') }}</p>
                    </div>
                    <input type="checkbox" wire:model="homepage.featured_slider_enabled"
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Featured Slider Category') }}</label>
                    <select wire:model.defer="homepage.featured_slider_category_id"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                        <option value="">{{ __('Select category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('Section Management') }}</h3>
                    <p class="text-xs text-slate-500">{{ __('Drag & drop to reorder sections and set the post count for each category. Leave order empty to hide a section.') }}</p>
                </div>
                @php
                    $orderedCategories = collect($categories)
                        ->map(function ($category) use ($homepageSectionOrder) {
                            return array_merge($category, [
                                'order' => $homepageSectionOrder[$category['id']] ?? null,
                            ]);
                        })
                        ->sort(function ($first, $second) {
                            $firstOrder = $first['order'] ?? null;
                            $secondOrder = $second['order'] ?? null;

                            if (is_null($firstOrder) && is_null($secondOrder)) {
                                return strcasecmp($first['name'], $second['name']);
                            }

                            if (is_null($firstOrder)) {
                                return 1;
                            }

                            if (is_null($secondOrder)) {
                                return -1;
                            }

                            return $firstOrder <=> $secondOrder;
                        });
                @endphp
                <div id="homepage-section-sortable" class="space-y-3">
                    @foreach($orderedCategories as $category)
                        <div class="grid grid-cols-1 md:grid-cols-[auto_1fr_140px_160px] gap-4 items-center rounded-md border border-slate-200 dark:border-slate-700 p-3 bg-white dark:bg-slate-900"
                             data-category-id="{{ $category['id'] }}">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-move js-drag-handle">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M7 4a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm8-12a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            </div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $category['name'] }}
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Order') }}</label>
                                <input type="number" min="1" wire:model.defer="homepageSectionOrder.{{ $category['id'] }}"
                                       class="js-order-input w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">{{ __('Post Count') }}</label>
                                <input type="number" min="1" wire:model.defer="homepageSectionPostCounts.{{ $category['id'] }}"
                                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>

@push('scripts')
    @once
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endonce
    <script>
        document.addEventListener('livewire:init', () => {
            const initHomepageSortable = () => {
                const list = document.getElementById('homepage-section-sortable');

                if (!list || list.dataset.sortableInit === '1' || typeof Sortable === 'undefined') {
                    return;
                }

                new Sortable(list, {
                    handle: '.js-drag-handle',
                    animation: 150,
                    ghostClass: 'bg-indigo-50',
                    onEnd: () => {
                        list.querySelectorAll('[data-category-id]').forEach((item, index) => {
                            const input = item.querySelector('.js-order-input');

                            if (!input) {
                                return;
                            }

                            input.value = index + 1;
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                        });
                    },
                });

                list.dataset.sortableInit = '1';
            };

            initHomepageSortable();

            Livewire.hook('message.processed', () => {
                initHomepageSortable();
            });
        });
    </script>
@endpush
