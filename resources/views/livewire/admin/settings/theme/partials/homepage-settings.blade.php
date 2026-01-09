<form wire:submit.prevent="saveHomepage" class="space-y-8">
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
