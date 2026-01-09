<form wire:submit.prevent="savePost" class="space-y-8">
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('Social Share Buttons') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <label class="flex items-center gap-2 rounded-md border border-slate-200 dark:border-slate-700 p-3 text-sm text-slate-700 dark:text-slate-300">
                <input type="checkbox" wire:model="post.share_facebook" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                {{ __('Facebook') }}
            </label>
            <label class="flex items-center gap-2 rounded-md border border-slate-200 dark:border-slate-700 p-3 text-sm text-slate-700 dark:text-slate-300">
                <input type="checkbox" wire:model="post.share_whatsapp" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                {{ __('WhatsApp') }}
            </label>
            <label class="flex items-center gap-2 rounded-md border border-slate-200 dark:border-slate-700 p-3 text-sm text-slate-700 dark:text-slate-300">
                <input type="checkbox" wire:model="post.share_telegram" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                {{ __('Telegram') }}
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="flex items-center justify-between gap-3 rounded-md border border-slate-200 dark:border-slate-700 p-4">
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Author Box') }}</p>
                <p class="text-xs text-slate-500">{{ __('Show author profile under posts.') }}</p>
            </div>
            <input type="checkbox" wire:model="post.author_box_enabled"
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Related News Count') }}</label>
            <input type="number" min="1" wire:model.defer="post.related_news_count"
                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Comments System') }}</label>
            <select wire:model.defer="post.comments_system"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none">
                <option value="livewire">{{ __('Livewire Comments') }}</option>
                <option value="disqus">{{ __('Disqus') }}</option>
            </select>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>
