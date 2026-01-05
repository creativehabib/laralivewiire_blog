{{-- Group: Typography --}}
<form wire:submit.prevent="saveTypography" class="space-y-6">
    {{-- Group: Typography --}}
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Primary Font') }}</label>
            <select wire:model="primary_font" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                <option value="">{{ __('Select font') }}</option>
                @foreach($googleFonts as $font)
                    <option value="{{ $font['family'] }}">{{ $font['family'] }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500">{{ __('Choose a Google font for the frontend typography.') }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Font Weights') }}</label>
            <input wire:model="primary_font_weights" type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none" placeholder="300;400;500;600;700">
            <p class="mt-1 text-xs text-slate-500">{{ __('Use semicolons between weights, e.g. 300;400;500;600;700.') }}</p>
        </div>
    </div>
    {{-- Footer Action --}}
    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>
