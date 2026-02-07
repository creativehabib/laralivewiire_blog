<form wire:submit.prevent="saveHeader" class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="flex items-center justify-between gap-3 rounded-md border border-slate-200 dark:border-slate-700 p-4">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Sticky Header') }}</p>
                <p class="text-xs text-slate-500">{{ __('Keep the header visible while scrolling.') }}</p>
            </div>
            <input type="checkbox" wire:model="header.sticky_header"
                   class="h-4 w-4 rounded border-slate-300 focus:ring-indigo-500">
        </div>

        <div class="flex items-center justify-between gap-3 rounded-md border border-slate-200 dark:border-slate-700 p-4">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Live Search Toggle') }}</p>
                <p class="text-xs text-slate-500">{{ __('Enable Livewire-powered search bar.') }}</p>
            </div>
            <input type="checkbox" wire:model="header.search_toggle"
                   class="h-4 w-4 rounded border-slate-300 focus:ring-indigo-500">
        </div>
    </div>

    <div class="rounded-md border border-slate-200 dark:border-slate-700 p-4 space-y-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Breaking News Ticker') }}</p>
                <p class="text-xs text-slate-500">{{ __('Show a scrolling ticker from a selected category.') }}</p>
            </div>
            <input type="checkbox" wire:model="header.breaking_news_enabled"
                   class="h-4 w-4 rounded border-slate-300  focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Show More Breaking News') }}</label>
                <input type="number" min="10" wire:model.defer="header.show_more_breaking_news"
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Breaking News Position') }}</label>
                <select wire:model.defer="header.breaking_news_position"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                    <option value="top">{{ __('Top (Header)') }}</option>
                    <option value="bottom">{{ __('Bottom (Fixed)') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Ticker Speed (px/sec)') }}</label>
                <input type="number" min="10" wire:model.defer="header.breaking_news_speed"
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
            </div>
        </div>
    </div>

    <div class="pt-4 flex justify-end sticky bottom-0 z-10 bg-gray-50/90 dark:bg-slate-900/90 backdrop-blur-sm py-4 border-t border-slate-200 dark:border-slate-700 -mx-6 px-6 -mb-6 rounded-b-lg">
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="saveHeader"
                class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-medium cursor-pointer rounded-lg shadow-lg shadow-indigo-500/30 transition-all inline-flex items-center justify-center gap-2 transform active:scale-95 min-w-[160px]">

            <span wire:loading.remove wire:target="saveHeader" class="inline-flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>{{ __('Save Changes') }}</span>
            </span>

            <span wire:loading wire:target="saveHeader" class="inline-flex items-center gap-2">
                <i class="fas fa-circle-notch fa-spin"></i>
                <span>{{ __('Saving...') }}</span>
            </span>

        </button>
    </div>
</form>
