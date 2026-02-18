<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Social Revive সেটিংস</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Revive Social WordPress plugin এর মতো পুরোনো পোস্ট অটো শেয়ার করার কনফিগারেশন এখানে ম্যানেজ করুন।
        </p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">General</h2>

            <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Enable Social Revive</span>
                <input type="checkbox" wire:model="enabled" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            </label>

            <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Debug Mode</span>
                <input type="checkbox" wire:model="debug_mode" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            </label>

            <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Share Old Posts</span>
                <input type="checkbox" wire:model="share_old_posts" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            </label>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Posting Schedule</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Minimum Post Age (days)</label>
                    <input type="number" wire:model="minimum_post_age" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('minimum_post_age') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Maximum Post Age (days)</label>
                    <input type="number" wire:model="maximum_post_age" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('maximum_post_age') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Share Every</label>
                        <input type="number" wire:model="share_interval_value" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                        @error('share_interval_value') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Unit</label>
                        <select wire:model="share_interval_unit" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                            <option value="minutes">Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                        </select>
                        @error('share_interval_unit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Posts per run</label>
                    <input type="number" wire:model="posts_per_run" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('posts_per_run') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Post Filters</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Post Types (comma separated)</label>
                    <input type="text" wire:model="post_types" placeholder="post,page" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('post_types') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Include Categories (slug comma separated)</label>
                    <input type="text" wire:model="include_categories" placeholder="news,tech" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Exclude Categories (slug comma separated)</label>
                    <input type="text" wire:model="exclude_categories" placeholder="sponsored,private" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Content Format</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Hashtag Source</label>
                    <select wire:model="hashtags_mode" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                        <option value="none">No hashtags</option>
                        <option value="post_tags">Use post tags</option>
                        <option value="post_categories">Use post categories</option>
                        <option value="custom">Custom hashtags</option>
                    </select>
                    @error('hashtags_mode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Custom Hashtags</label>
                    <input type="text" wire:model="custom_hashtags" placeholder="#বাংলা,#news" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Post Template</label>
                    <textarea wire:model="post_template" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900" placeholder="{title} {url} {hashtags}"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Available placeholders: <code>{title}</code>, <code>{url}</code>, <code>{hashtags}</code></p>
                    @error('post_template') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Link & Tracking</h2>

                <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Use URL shortener</span>
                    <input type="checkbox" wire:model="url_shortener_enabled" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Shortener Provider</label>
                    <select wire:model="url_shortener_provider" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                        <option value="none">None</option>
                        <option value="bitly">Bitly</option>
                        <option value="rebrandly">Rebrandly</option>
                    </select>
                    @error('url_shortener_provider') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Shortener API Key</label>
                    <input type="password" wire:model="url_shortener_api_key" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                </div>

                <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Enable UTM tracking</span>
                    <input type="checkbox" wire:model="utm_enabled" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">UTM Source</label>
                        <input type="text" wire:model="utm_source" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">UTM Medium</label>
                        <input type="text" wire:model="utm_medium" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">UTM Campaign</label>
                        <input type="text" wire:model="utm_campaign" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md">
                Save Social Revive Settings
            </button>
        </div>
    </form>
</div>
