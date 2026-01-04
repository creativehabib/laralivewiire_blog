<x-theme.layout>
    {{-- Group: Basic Info --}}
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Designed by') }}</label>
            <input type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all" value="Designed by AliThemes | All rights reserved.">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Primary Font') }}</label>
                <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                    <option>Roboto</option>
                    <option>Inter</option>
                    <option>Poppins</option>
                </select>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Primary Color') }}</label>
                    <input type="color" class="w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800" value="#2563eb">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Danger Color') }}</label>
                    <input type="color" class="w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800" value="#ef4444">
                </div>
            </div>
        </div>
    </div>

    <hr class="border-slate-200 dark:border-slate-700">

    {{-- Group: SEO --}}
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('SEO Settings') }}</h3>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Site Description') }}</label>
            <textarea rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('SEO Index') }}</label>
            <div class="flex items-center gap-6">
                <label class="flex items-center text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="radio" name="seo_index" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 mr-2" checked> Index
                </label>
                <label class="flex items-center text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="radio" name="seo_index" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 mr-2"> No Index
                </label>
            </div>
        </div>
    </div>

    {{-- Footer Action --}}
    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</x-theme.layout>
