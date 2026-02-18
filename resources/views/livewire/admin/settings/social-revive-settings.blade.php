<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Social Revive Package Settings</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Social Revive package-এর সব core configuration এখান থেকে কন্ট্রোল করুন।
        </p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Queue & UTM</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Queue Connection</label>
                    <select wire:model="queue_connection" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                        <option value="sync">sync</option>
                        <option value="database">database</option>
                        <option value="redis">redis</option>
                        <option value="sqs">sqs</option>
                    </select>
                    @error('queue_connection') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Enable UTM</span>
                    <input type="checkbox" wire:model="utm_enabled" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">UTM Source</label>
                        <input type="text" wire:model="utm_source" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                        @error('utm_source') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">UTM Medium</label>
                        <input type="text" wire:model="utm_medium" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                        @error('utm_medium') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">AI Settings</h2>

                <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Enable AI Caption</span>
                    <input type="checkbox" wire:model="ai_enabled" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">AI Provider</label>
                    <select wire:model="ai_provider" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                        <option value="openai">OpenAI</option>
                        <option value="gemini">Gemini</option>
                        <option value="anthropic">Anthropic</option>
                        <option value="none">None</option>
                    </select>
                    @error('ai_provider') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">AI Model</label>
                    <input type="text" wire:model="ai_model" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('ai_model') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">AI API Key</label>
                    <input type="password" wire:model="ai_api_key" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('ai_api_key') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-5 space-y-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Default Automation Rule (Package)</h2>
            <p class="text-xs text-gray-500">এগুলো Social Revive package-এর default rule create করার সময় ব্যবহার করতে পারবেন।</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Min Days Old</label>
                    <input type="number" wire:model="default_min_days_old" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('default_min_days_old') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Interval Minutes</label>
                    <input type="number" wire:model="default_interval_minutes" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('default_interval_minutes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Avoid Repeat Days</label>
                    <input type="number" wire:model="default_avoid_repeat_days" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('default_avoid_repeat_days') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Caption Template</label>
                    <textarea wire:model="default_template" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900" placeholder="{title} {url}"></textarea>
                    @error('default_template') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Timezone</label>
                    <input type="text" wire:model="default_timezone" placeholder="UTC" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-700 dark:bg-slate-900">
                    @error('default_timezone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Default AI Caption</span>
                    <input type="checkbox" wire:model="default_ai_caption" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                <label class="flex items-center justify-between p-3 rounded border border-slate-200 dark:border-slate-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Default Auto Hashtag</span>
                    <input type="checkbox" wire:model="default_auto_hashtag" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md">
                Save Package Settings
            </button>
        </div>
    </form>
</div>
