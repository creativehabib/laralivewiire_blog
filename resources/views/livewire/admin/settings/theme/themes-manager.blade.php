<div class="space-y-6">
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Themes</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Install, activate/deactivate, and delete themes (Botble-style workflow).</p>

        <div class="mt-4 flex flex-col md:flex-row gap-3 md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Upload Theme ZIP</label>
                <input type="file" wire:model="themeZip" accept=".zip" class="block w-full text-sm border border-slate-300 dark:border-slate-600 rounded-lg p-2 bg-white dark:bg-slate-900" />
                @error('themeZip')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button wire:click="installTheme" wire:loading.attr="disabled" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium disabled:opacity-60">
                <span wire:loading.remove wire:target="installTheme">Install Theme</span>
                <span wire:loading wire:target="installTheme">Installing...</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($themes as $theme)
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $theme['name'] }}</h3>
                        <p class="text-xs text-slate-500">slug: {{ $theme['slug'] }}</p>
                    </div>

                    @if($theme['active'])
                        <span class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Inactive</span>
                    @endif
                </div>

                <div class="mt-3 text-sm text-slate-600 dark:text-slate-300 space-y-1">
                    <p><span class="font-medium">Version:</span> {{ $theme['version'] ?: '-' }}</p>
                    <p><span class="font-medium">Author:</span> {{ $theme['author'] ?: '-' }}</p>
                    @if(!empty($theme['description']))
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $theme['description'] }}</p>
                    @endif
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @if(!$theme['active'])
                        <button wire:click="activateTheme('{{ $theme['slug'] }}')" class="px-3 py-1.5 text-xs rounded-md bg-indigo-600 hover:bg-indigo-700 text-white">Activate</button>
                    @elseif($theme['slug'] !== $defaultTheme)
                        <button wire:click="deactivateTheme('{{ $theme['slug'] }}')" class="px-3 py-1.5 text-xs rounded-md bg-amber-500 hover:bg-amber-600 text-white">Deactivate</button>
                    @endif

                    @if($theme['slug'] !== $defaultTheme && $theme['slug'] !== $activeTheme)
                        <button wire:click="deleteTheme('{{ $theme['slug'] }}')" class="px-3 py-1.5 text-xs rounded-md bg-rose-600 hover:bg-rose-700 text-white">Delete</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 text-sm text-slate-500">
                No theme found. Upload a ZIP to install your first theme.
            </div>
        @endforelse
    </div>
</div>
