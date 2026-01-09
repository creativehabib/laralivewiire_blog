<form wire:submit.prevent="saveLayout" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Primary Theme Color') }}</label>
            <input type="color" wire:model.defer="layout.primary_theme_color"
                   class="w-full h-12 p-1 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800">
        </div>
        <div class="flex items-center justify-between gap-3 rounded-md border border-slate-200 dark:border-slate-700 p-4">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Dark Mode Switch') }}</p>
                <p class="text-xs text-slate-500">{{ __('Allow users to toggle dark mode.') }}</p>
            </div>
            <input type="checkbox" wire:model="layout.dark_mode_enabled"
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('Category Colors') }}</h3>
            <p class="text-xs text-slate-500">{{ __('Assign a unique color to each category badge.') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($categories as $category)
                <div class="flex items-center justify-between gap-3 rounded-md border border-slate-200 dark:border-slate-700 p-3">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $category['name'] }}</span>
                    <input type="color" wire:model.defer="categoryColors.{{ $category['id'] }}"
                           class="h-10 w-16 p-1 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800">
                </div>
            @endforeach
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('Typography') }}</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Primary Font') }}</label>
                <input id="layout-primary-font-value" type="hidden" wire:model="primary_font">
                <div wire:ignore>
                    <select id="layout-primary-font-select" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                        <option value="">{{ __('Select font') }}</option>
                        @foreach($googleFonts as $font)
                            <option value="{{ $font['family'] }}" @selected($primary_font === $font['family'])>{{ $font['family'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Font Weights') }}</label>
                <input wire:model="primary_font_weights" type="text"
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"
                       placeholder="300;400;500;600;700">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Body Font Size') }}</label>
            <select wire:model="body_font_size" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                @foreach(['14px', '15px', '16px', '17px', '18px', '20px'] as $size)
                    <option value="{{ $size }}">{{ $size }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>

@push('scripts')
    @once
        <script>
            const initLayoutFontChoices = () => {
                const select = document.getElementById('layout-primary-font-select');
                const hiddenInput = document.getElementById('layout-primary-font-value');

                if (!select || !hiddenInput || select.dataset.choicesInitialized === 'true' || !window.Choices) {
                    return;
                }

                const choices = new window.Choices(select, {
                    searchEnabled: true,
                    shouldSort: false,
                    itemSelectText: '',
                    allowHTML: false,
                });

                select.dataset.choicesInitialized = 'true';

                select.addEventListener('change', () => {
                    hiddenInput.value = select.value;
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                });
            };

            document.addEventListener('DOMContentLoaded', initLayoutFontChoices);
            document.addEventListener('livewire:navigated', initLayoutFontChoices);
        </script>
    @endonce
@endpush
