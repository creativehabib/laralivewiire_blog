<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Default Page Layout') }}</label>
            <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                <option>{{ __('Full Width') }}</option>
                <option>{{ __('Contained') }}</option>
                <option>{{ __('Sidebar Left') }}</option>
                <option>{{ __('Sidebar Right') }}</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Posts Per Page') }}</label>
            <input type="number" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none" value="10">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Show Breadcrumbs') }}</label>
            <label class="inline-flex items-center text-sm text-slate-600 dark:text-slate-400">
                <input type="checkbox" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 mr-2" checked>
                {{ __('Enable breadcrumbs on pages') }}
            </label>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Sidebar Widgets') }}</label>
            <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                <option>{{ __('Recent Posts') }}</option>
                <option>{{ __('Categories') }}</option>
                <option>{{ __('Tag Cloud') }}</option>
            </select>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</div>
