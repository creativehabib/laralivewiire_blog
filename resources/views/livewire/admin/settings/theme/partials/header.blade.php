<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Header Layout') }}</label>
            <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                <option>{{ __('Classic') }}</option>
                <option>{{ __('Centered') }}</option>
                <option>{{ __('Split') }}</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Sticky Header') }}</label>
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center text-sm text-slate-600 dark:text-slate-400">
                    <input type="checkbox" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 mr-2" checked>
                    {{ __('Enable sticky header') }}
                </label>
            </div>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Header Announcement') }}</label>
        <input type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none" placeholder="{{ __('Free shipping on orders over $50') }}">
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</div>
