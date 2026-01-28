<form wire:submit.prevent="saveGeneral" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Site Name') }}</label>
            <input type="text" wire:model.defer="general.site_title"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Tagline') }}</label>
            <input type="text" wire:model.defer="general.site_tagline"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div>
            @include('mediamanager::includes.media-input', [
                'name'  => 'general.site_logo_light',
                'id'    => 'site-logo-light',
                'label' => 'Light Logo',
                'value' => $general['site_logo_light'] ?? '',
            ])
        </div>
        <div>
            @include('mediamanager::includes.media-input', [
                'name'  => 'general.site_logo_dark',
                'id'    => 'site-logo-dark',
                'label' => 'Dark Logo',
                'value' => $general['site_logo_dark'] ?? '',
            ])
        </div>
        <div>
            @include('mediamanager::includes.media-input', [
                'name'  => 'general.site_favicon',
                'id'    => 'site-favicon',
                'label' => 'Favicon',
                'value' => $general['site_favicon'] ?? '',
            ])
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Timezone') }}</label>
            <select wire:model.defer="general.timezone" id="select_time_zone"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                @foreach($timezoneOptions as $timezone)
                    <option value="{{ $timezone }}" @selected(($general['timezone'] ?? '') === $timezone)>{{ $timezone }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Date Format') }}</label>
            <select wire:model.defer="general.date_display_format"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                <option value="gregorian_and_bangla">{{ __('Bangla & Gregorian') }}</option>
                <option value="gregorian_only">{{ __('Gregorian Only') }}</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Office Address') }}</label>
        <textarea rows="3" wire:model.defer="general.contact_address"
                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Contact Email') }}</label>
            <input type="email" wire:model.defer="general.site_email"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Contact Phone') }}</label>
            <input type="text" wire:model.defer="general.site_phone"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
        </div>
    </div>

    <div class="pt-4 flex justify-end sticky bottom-0 z-10 bg-gray-50/90 dark:bg-slate-900/90 backdrop-blur-sm py-4 border-t border-slate-200 dark:border-slate-700 -mx-6 px-6 -mb-6 rounded-b-lg">
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="saveGeneral"
                class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-medium cursor-pointer rounded-lg shadow-lg shadow-indigo-500/30 transition-all inline-flex items-center justify-center gap-2 transform active:scale-95 min-w-[160px]">

                    <span wire:loading.remove wire:target="saveGeneral" class="inline-flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>{{ __('Save Changes') }}</span>
                    </span>

            <span wire:loading wire:target="saveGeneral" class="inline-flex items-center gap-2">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        <span>{{ __('Saving...') }}</span>
                    </span>

        </button>
    </div>
</form>

@push('scripts')
    @once
        <script>
            const initPrimaryFontChoices = () => {
                const select = document.getElementById('select_time_zone');
                const hiddenInput = document.getElementById('select_time_zone');

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
