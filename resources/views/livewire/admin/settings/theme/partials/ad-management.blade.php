<form wire:submit.prevent="saveAds" class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Header Ad Code') }}</label>
        <textarea rows="4" wire:model.defer="ads.header_ad_code"
                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Sidebar Ad Code') }}</label>
        <textarea rows="4" wire:model.defer="ads.sidebar_ad_code"
                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('In-Article Ad Code') }}</label>
        <textarea rows="4" wire:model.defer="ads.in_article_ad_code"
                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Insert After Paragraph') }}</label>
        <input type="number" min="1" wire:model.defer="ads.in_article_ad_paragraph"
               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="saveAds"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 disabled:cursor-not-allowed text-white font-medium rounded-md shadow-sm transition-all inline-flex items-center gap-2">
            <span wire:loading.remove wire:target="saveAds" class="inline-flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ __('Save Changes') }}
            </span>
            <span wire:loading wire:target="saveAds" class="inline-flex items-center gap-2">
                <i class="fas fa-circle-notch fa-spin"></i>
                {{ __('Saving...') }}
            </span>
        </button>
    </div>
</form>
