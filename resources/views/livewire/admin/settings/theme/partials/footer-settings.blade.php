<form wire:submit.prevent="saveFooter" class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Copyright Text') }}</label>
        <textarea rows="2" wire:model.defer="footer.copyright_text"
                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('About Summary') }}</label>
        <textarea rows="3" wire:model.defer="footer.about_summary"
                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Useful Links') }}</label>
        <select multiple wire:model.defer="footer.useful_links"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
            @foreach($pages as $page)
                <option value="{{ $page['id'] }}">{{ $page['title'] }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">{{ __('Select pages to show in the footer useful links section.') }}</p>
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="saveFooter"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 disabled:cursor-not-allowed text-white font-medium rounded-md shadow-sm transition-all inline-flex items-center gap-2">
            <span wire:loading.remove wire:target="saveFooter" class="inline-flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ __('Save Changes') }}
            </span>
            <span wire:loading wire:target="saveFooter" class="inline-flex items-center gap-2">
                <i class="fas fa-circle-notch fa-spin"></i>
                {{ __('Saving...') }}
            </span>
        </button>
    </div>
</form>
