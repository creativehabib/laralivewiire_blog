<form wire:submit.prevent="saveSeo" class="space-y-8">
    <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Meta Description') }}</label>
        <textarea rows="3" wire:model.defer="seo.meta_description"
                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Facebook Page URL') }}</label>
            <input type="url" wire:model.defer="seo.facebook_url"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('YouTube Channel URL') }}</label>
            <input type="url" wire:model.defer="seo.youtube_url"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('X (Twitter) URL') }}</label>
            <input type="url" wire:model.defer="seo.x_url"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Instagram URL') }}</label>
            <input type="url" wire:model.defer="seo.instagram_url"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('Additional Social Links') }}</h3>
        @include('livewire.admin.settings.theme.partials.social_links', ['showSaveButton' => false])
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Google Analytics Code') }}</label>
            <textarea rows="4" wire:model.defer="seo.google_analytics_code"
                      class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Facebook Pixel Code') }}</label>
            <textarea rows="4" wire:model.defer="seo.facebook_pixel_code"
                      class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none"></textarea>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="saveSeo"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 disabled:cursor-not-allowed text-white font-medium rounded-md shadow-sm transition-all inline-flex items-center gap-2">
            <span wire:loading.remove wire:target="saveSeo" class="inline-flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ __('Save Changes') }}
            </span>
            <span wire:loading wire:target="saveSeo" class="inline-flex items-center gap-2">
                <i class="fas fa-circle-notch fa-spin"></i>
                {{ __('Saving...') }}
            </span>
        </button>
    </div>
</form>
