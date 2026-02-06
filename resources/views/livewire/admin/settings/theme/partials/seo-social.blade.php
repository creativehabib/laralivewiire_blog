<form wire:submit.prevent="saveSeo" class="space-y-8">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">{{ __('Google Search') }}</h3>
        <div class="mt-4 max-w-xl">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Google Search Engine ID') }}</label>
            <input
                type="text"
                wire:model.defer="seo.google_search_engine_id"
                placeholder="127310ace15f648af"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none"
            >
            <a class="mt-2 inline-block text-sm text-blue-600 hover:underline" href="https://programmablesearchengine.google.com/" target="_blank" rel="noopener noreferrer">
                {{ __('Register to Google Custom Search Engine, and enter your Google Search Engine here.') }}
            </a>
        </div>
    </div>

    <div class="space-y-4">
        @include('livewire.admin.settings.theme.partials.social_links', ['showSaveButton' => true])
    </div>
</form>
