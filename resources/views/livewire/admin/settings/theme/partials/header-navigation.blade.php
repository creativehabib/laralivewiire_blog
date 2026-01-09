<form wire:submit.prevent="saveHeader" class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="flex items-center justify-between gap-3 rounded-md border border-slate-200 dark:border-slate-700 p-4">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Sticky Header') }}</p>
                <p class="text-xs text-slate-500">{{ __('Keep the header visible while scrolling.') }}</p>
            </div>
            <input type="checkbox" wire:model="header.sticky_header"
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        </div>

        <div class="flex items-center justify-between gap-3 rounded-md border border-slate-200 dark:border-slate-700 p-4">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Live Search Toggle') }}</p>
                <p class="text-xs text-slate-500">{{ __('Enable Livewire-powered search bar.') }}</p>
            </div>
            <input type="checkbox" wire:model="header.search_toggle"
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        </div>
    </div>

    <div class="rounded-md border border-slate-200 dark:border-slate-700 p-4 space-y-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Breaking News Ticker') }}</p>
                <p class="text-xs text-slate-500">{{ __('Show a scrolling ticker from a selected category.') }}</p>
            </div>
            <input type="checkbox" wire:model="header.breaking_news_enabled"
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Breaking News Category') }}</label>
                <select wire:model.defer="header.breaking_news_category_id"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                    <option value="">{{ __('Select category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Ticker Speed (px/sec)') }}</label>
                <input type="number" min="10" wire:model.defer="header.breaking_news_speed"
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
            </div>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>
