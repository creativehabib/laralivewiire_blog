<form wire:submit.prevent="saveGeneral" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Site Name') }}</label>
            <input type="text" wire:model.defer="general.site_title"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Tagline') }}</label>
            <input type="text" wire:model.defer="general.site_tagline"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
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
            <select wire:model.defer="general.timezone"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                @foreach($timezoneOptions as $timezone)
                    <option value="{{ $timezone }}">{{ $timezone }}</option>
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

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="saveGeneral"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 disabled:cursor-not-allowed text-white font-medium rounded-md shadow-sm transition-all inline-flex items-center gap-2">
            <span wire:loading.remove wire:target="saveGeneral" class="inline-flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ __('Save Changes') }}
            </span>
            <span wire:loading wire:target="saveGeneral" class="inline-flex items-center gap-2">
                <i class="fas fa-circle-notch fa-spin"></i>
                {{ __('Saving...') }}
            </span>
        </button>
    </div>
</form>
