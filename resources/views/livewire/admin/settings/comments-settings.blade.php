<div class="max-w-5xl mx-auto antialiased text-slate-900 dark:text-slate-100">
    {{-- Header Section --}}
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

        {{-- 1. Default Post Settings --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Default post settings</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="notify_linked_blogs" class="w-4 h-4 border-slate-300 rounded">
                    <span>Attempt to notify any blogs linked to from the post</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="allow_pingbacks" class="w-4 h-4 border-slate-300 rounded">
                    <span>Allow link notifications from other blogs (pingbacks and trackbacks) on new posts</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="allow_comments_default" class="w-4 h-4 border-slate-300 rounded">
                    <span>Allow people to submit comments on new posts</span>
                </label>
            </div>
        </div>

        {{-- 2. Comment Systems --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Comment systems</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <p class="text-slate-500 dark:text-slate-400">Choose which commenting experience you want to provide.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <label class="flex items-start gap-3 p-3 border rounded-lg bg-slate-50 dark:bg-slate-900/40 border-slate-200 dark:border-slate-700 cursor-pointer">
                        <input type="radio" class="mt-1 w-4 h-4 border-slate-300" value="default" wire:model.live="comment_system">
                        <div>
                            <div class="font-semibold">Default comments</div>
                            <p class="text-xs text-slate-500">Use the built-in comment system only.</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 border rounded-lg bg-slate-50 dark:bg-slate-900/40 border-slate-200 dark:border-slate-700 cursor-pointer">
                        <input type="radio" class="mt-1 w-4 h-4 border-slate-300" value="facebook" wire:model.live="comment_system">
                        <div>
                            <div class="font-semibold">Facebook comments</div>
                            <p class="text-xs text-slate-500">Show only the Facebook comment plugin.</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 border rounded-lg bg-slate-50 dark:bg-slate-900/40 border-slate-200 dark:border-slate-700 cursor-pointer">
                        <input type="radio" class="mt-1 w-4 h-4 border-slate-300" value="both" wire:model.live="comment_system">
                        <div>
                            <div class="font-semibold">Both systems</div>
                            <p class="text-xs text-slate-500">Display Facebook and default comments together.</p>
                        </div>
                    </label>
                </div>

                <div class="flex items-start gap-3 pt-2">
                    <input type="checkbox" wire:model.live="facebook_enabled" class="mt-1 w-4 h-4 border-slate-300 rounded">
                    <div>
                        <div class="font-semibold">Enable Facebook comments</div>
                        <p class="text-xs text-slate-500">Toggle to load the Facebook comments plugin instead of, or alongside, the default system.</p>
                    </div>
                </div>

                <div class="pt-4 space-y-1">
                    <label class="font-semibold text-slate-700 dark:text-slate-200">Facebook App ID</label>
                    <p class="text-xs text-slate-500">Provide your Facebook App ID to ensure the comments plugin loads correctly for your site.</p>
                    <input
                        type="text"
                        wire:model.live="facebook_app_id"
                        placeholder="123456789012345"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-slate-900/40 dark:border-slate-700"
                    >
                </div>

                <div class="space-y-1">
                    <label class="font-semibold text-slate-700 dark:text-slate-200">Facebook App Secret</label>
                    <p class="text-xs text-slate-500">Get this from the Facebook Developer Console. It is stored only on the server and never exposed to templates.</p>
                    <input
                        type="password"
                        wire:model.live="facebook_app_secret"
                        placeholder="••••••••"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-slate-900/40 dark:border-slate-700"
                    >
                </div>
            </div>
        </div>

        {{-- 3. Other Comment Settings --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Other comment settings</h2>
                <span class="text-[11px] px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-900 text-slate-500">Front-end enforced</span>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="require_name_email" class="w-4 h-4 border-slate-300 rounded">
                        <span>Comment author must fill out name and email</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="require_login" class="w-4 h-4 border-slate-300 rounded">
                        <span>Users must be registered and logged in to comment</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="auto_close" class="w-4 h-4 border-slate-300 rounded">
                        <span>Automatically close comments on posts older than</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" min="1" max="365" wire:model.live="auto_close_days" class="w-24 px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" @disabled(! $auto_close)>
                        <span>days</span>
                    </div>
                </div>

                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="cookies_opt_in" class="w-4 h-4 border-slate-300 rounded">
                    <span>Show comments cookies opt-in checkbox, allowing comment author cookies to be set</span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="threaded_comments" class="w-4 h-4 border-slate-300 rounded">
                        <span>Enable threaded (nested) comments</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" min="1" max="10" wire:model.live="thread_depth" class="w-16 px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" @disabled(! $threaded_comments)>
                        <span class="text-slate-500">levels deep</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="paginate_comments" class="w-4 h-4 border-slate-300 rounded">
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

        {{-- 4. Email Notifications --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Email me whenever</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="email_notify_any" class="w-4 h-4 border-slate-300 rounded">
                    <span>Anyone posts a comment</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="email_notify_moderation" class="w-4 h-4 border-slate-300 rounded">
                    <span>A comment is held for moderation</span>
                </label>
            </div>
        </div>

        {{-- 5. Before Comment Appears --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Before a comment appears</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="manual_approval" class="w-4 h-4 border-slate-300 rounded">
                    <span>Comment must be manually approved</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="require_prior_approval" class="w-4 h-4 border-slate-300 rounded">
                    <span>Comment author must have a previously approved comment</span>
                </label>
            </div>
        </div>

        {{-- 6. Moderation --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Comment moderation</h2>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="flex items-center gap-3 flex-wrap">
                    <span>Hold a comment in the queue if it contains</span>
                    <input type="number" min="0" max="20" wire:model.live="moderation_links" class="w-20 px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <span>or more links. <span class="text-slate-500">(A common characteristic of comment spam is a large number of hyperlinks.)</span></span>
                </div>

                <div class="space-y-1">
                    <label class="font-semibold text-slate-700 dark:text-slate-200">Comment Moderation</label>
                    <p class="text-xs text-slate-500">When a comment contains any of these words in its content, name, URL, email, or IP address, it will be held in the moderation queue.</p>
                    <textarea wire:model.live="moderation_keys" rows="4" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="One word or IP address per line, it will match inside words."></textarea>
                </div>

                <div class="space-y-1">
                    <label class="font-semibold text-slate-700 dark:text-slate-200">Disallowed Comment Keys</label>
                    <p class="text-xs text-slate-500">When a comment contains any of these words in its content, name, URL, email, or IP address, it will be put in the Trash. One word or IP address per line. It will match inside words, so “press” will match “WordPress”.</p>
                    <textarea wire:model.live="disallowed_keys" rows="4" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
        </div>

        {{-- 7. Avatars --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Avatars</h2>
            </div>
            <div class="p-6 space-y-5 text-sm">
                <div class="flex items-center gap-3">
                    <input type="checkbox" wire:model.live="show_avatars" class="w-4 h-4 border-slate-300 rounded">
                    <span>Show Avatars</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div>
                        <h3 class="text-xs uppercase font-semibold text-slate-500">Maximum Rating</h3>
                        <p class="text-xs text-slate-500">Show avatars of comments with a rating of</p>
                    </div>
                    <select wire:model.live="avatar_rating" class="px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500" @disabled(! $show_avatars)>
                        <option value="g">G — Suitable for all audiences</option>
                        <option value="pg">PG — Possibly offensive, usually for audiences 13 and above</option>
                        <option value="r">R — Intended for adult audiences above 17</option>
                        <option value="x">X — Even more mature than above</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <h3 class="text-xs uppercase font-semibold text-slate-500">Default Avatar</h3>
                    <p class="text-xs text-slate-500">For users without a custom avatar you can either display a generic logo or a generated one.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3">
                            <input type="radio" class="w-4 h-4 border-slate-300" value="mystery" wire:model.live="avatar_default" @disabled(! $show_avatars)>
                            <span>Mystery Person</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="radio" class="w-4 h-4 border-slate-300" value="blank" wire:model.live="avatar_default" @disabled(! $show_avatars)>
                            <span>Blank</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="radio" class="w-4 h-4 border-slate-300" value="gravatar" wire:model.live="avatar_default" @disabled(! $show_avatars)>
                            <span>Gravatar Logo</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="radio" class="w-4 h-4 border-slate-300" value="identicon" wire:model.live="avatar_default" @disabled(! $show_avatars)>
                            <span>Identicon (Generated)</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="radio" class="w-4 h-4 border-slate-300" value="wavatar" wire:model.live="avatar_default" @disabled(! $show_avatars)>
                            <span>Wavatar (Generated)</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="radio" class="w-4 h-4 border-slate-300" value="monsterid" wire:model.live="avatar_default" @disabled(! $show_avatars)>
                            <span>MonsterID (Generated)</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="radio" class="w-4 h-4 border-slate-300" value="retro" wire:model.live="avatar_default" @disabled(! $show_avatars)>
                            <span>Retro (Generated)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons (Using FontAwesome Icons & Loading Spinner) --}}
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-700">
            <button
                type="button"
                wire:click="$refresh"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-700 transition-colors"
            >
                <i class="fas fa-undo"></i>
                Reset
            </button>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-75 disabled:cursor-wait shadow-blue-500/30"
            >
                <i wire:loading wire:target="save" class="fas fa-spinner fa-spin"></i>

                <i wire:loading.remove wire:target="save" class="fas fa-save"></i>

                <span wire:loading.remove wire:target="save">Save changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
