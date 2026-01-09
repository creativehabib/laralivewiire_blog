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
            <p class="text-xs text-slate-500">{{ __('Set the order and post count for each category section. Leave order empty to hide a section.') }}</p>
        </div>
        <div class="space-y-3">
            @foreach($categories as $category)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center rounded-md border border-slate-200 dark:border-slate-700 p-3">
                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $category['name'] }}</div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">{{ __('Order') }}</label>
                        <input type="number" min="1" wire:model.defer="homepageSectionOrder.{{ $category['id'] }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
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
