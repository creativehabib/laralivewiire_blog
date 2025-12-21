<div class="max-w-5xl mx-auto antialiased text-slate-900 dark:text-slate-100">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Discussion Settings</h1>
            <p class="text-sm text-slate-500">Configure how comments behave across the site.</p>
        </div>
        <a href="{{ route('settings.comments.moderation') }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/40 border border-blue-200 dark:border-blue-700 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/60">
            <i class="fas fa-comments"></i>
            Moderate Comments
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-5">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Default post settings</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="notify_linked_blogs" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>Attempt to notify any blogs linked to from the post</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="allow_pingbacks" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>Allow link notifications from other blogs (pingbacks and trackbacks) on new posts</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="allow_comments_default" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>Allow people to submit comments on new posts</span>
                </label>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Other comment settings</h2>
                <span class="text-[11px] px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-900 text-slate-500">Front-end enforced</span>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="require_name_email" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                        <span>Comment author must fill out name and email</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="require_login" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                        <span>Users must be registered and logged in to comment</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="auto_close" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                        <span>Automatically close comments on posts older than</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" min="1" max="365" wire:model.live="auto_close_days" class="w-24 px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" @disabled(! $auto_close)>
                        <span>days</span>
                    </div>
                </div>

                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="cookies_opt_in" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>Show comments cookies opt-in checkbox, allowing comment author cookies to be set</span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="threaded_comments" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                        <span>Enable threaded (nested) comments</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" min="1" max="10" wire:model.live="thread_depth" class="w-16 px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" @disabled(! $threaded_comments)>
                        <span class="text-slate-500">levels deep</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="paginate_comments" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                        <span>Break comments into pages with</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" min="1" max="200" wire:model.live="comments_per_page" class="w-20 px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" @disabled(! $paginate_comments)>
                        <span>top level comments per page and the</span>
                        <select wire:model.live="comments_page_display" class="px-2 py-1.5 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" @disabled(! $paginate_comments)>
                            <option value="first">first</option>
                            <option value="last">last</option>
                        </select>
                        <span>page displayed by default</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <span class="text-sm">Comments should be displayed with the</span>
                    <select wire:model.live="comments_order" class="px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="older">older</option>
                        <option value="newer">newer</option>
                    </select>
                    <span>comments at the top of each page</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Email me whenever</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="email_notify_any" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>Anyone posts a comment</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="email_notify_moderation" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>A comment is held for moderation</span>
                </label>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Before a comment appears</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="manual_approval" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>Comment must be manually approved</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="require_prior_approval" class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                    <span>Comment author must have a previously approved comment</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="button" wire:click="$refresh" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-300 rounded-md hover:bg-slate-50">Reset</button>
            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">Save settings</button>
        </div>
    </form>
</div>
