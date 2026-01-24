{{-- Group: Typography --}}
<form wire:submit.prevent="saveTypography" class="space-y-6">
    {{-- Group: Typography --}}
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Primary Font') }}</label>
            <input id="primary-font-value" type="hidden" wire:model="primary_font">
            <div wire:ignore>
                <select id="primary-font-select">
                    <option value="">{{ __('Select font') }}</option>
                    @foreach($googleFonts as $font)
                        <option value="{{ $font['family'] }}" @selected($primary_font === $font['family'])>{{ $font['family'] }}</option>
                    @endforeach
                </select>
            </div>
            <p class="mt-1 text-xs text-slate-500">{{ __('Choose a Google font for the frontend typography.') }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Font Weights') }}</label>
            <input wire:model="primary_font_weights" type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none" placeholder="300;400;500;600;700">
            <p class="mt-1 text-xs text-slate-500">{{ __('Use semicolons between weights, e.g. 300;400;500;600;700.') }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Body Font Size') }}</label>
            <select wire:model="body_font_size" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                @foreach(['14px', '15px', '16px', '17px', '18px', '20px'] as $size)
                    <option value="{{ $size }}">{{ $size }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500">{{ __('Set the default font size for frontend body text.') }}</p>
        </div>
    </div>
    {{-- Footer Action --}}
    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="saveTypography"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 disabled:cursor-not-allowed text-white font-medium rounded-md shadow-sm transition-all inline-flex items-center gap-2">
            <span wire:loading.remove wire:target="saveTypography" class="inline-flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ __('Save Changes') }}
            </span>
            <span wire:loading wire:target="saveTypography" class="inline-flex items-center gap-2">
                <i class="fas fa-circle-notch fa-spin"></i>
                {{ __('Saving...') }}
            </span>
        </button>
    </div>
</form>

@push('scripts')
    @once
        <script>
            const initPrimaryFontChoices = () => {
                const select = document.getElementById('primary-font-select');
                const hiddenInput = document.getElementById('primary-font-value');

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

            document.addEventListener('DOMContentLoaded', initPrimaryFontChoices);
            document.addEventListener('livewire:navigated', initPrimaryFontChoices);
        </script>
    @endonce
@endpush
