{{-- Group: Typography --}}
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Primary Font') }}</label>
        <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
            <option>Roboto</option>
            <option>Inter</option>
            <option>Poppins</option>
        </select>
    </div>
</div>
{{-- Footer Action --}}
<div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
        {{ __('Save Changes') }}
    </button>
</div>
