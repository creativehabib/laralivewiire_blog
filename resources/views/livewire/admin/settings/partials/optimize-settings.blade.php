@php
    $fields = collect($config['fields'] ?? []);
    $toggleField = $fields->firstWhere('key', 'optimize_enabled');
    $optionFields = $fields->filter(fn ($field) => ($field['key'] ?? null) !== 'optimize_enabled');
@endphp

<div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-[220px_minmax(0,1fr)]">
        <div class="space-y-2">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                {{ $config['title'] ?? 'Optimize' }}
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ $config['description'] ?? '' }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="p-5 space-y-4">
                <label class="flex items-center gap-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3">
                    <input
                        type="checkbox"
                        wire:model.live="data.{{ $toggleField['key'] ?? 'optimize_enabled' }}"
                        class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-100">
                        {{ $toggleField['label'] ?? 'Enable optimize page speed?' }}
                    </span>
                </label>

                <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 p-4 space-y-4"
                     x-data
                     x-show="$wire.data.optimize_enabled"
                     x-cloak>
                    @foreach($optionFields as $field)
                        <label class="flex items-start gap-3">
                            <input
                                type="checkbox"
                                wire:model.live="data.{{ $field['key'] }}"
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span>
                                <span class="block text-sm font-semibold text-slate-700 dark:text-slate-100">
                                    {{ $field['label'] ?? $field['key'] }}
                                </span>
                                <span class="block text-xs text-slate-500 dark:text-slate-400">
                                    {{ $field['description'] ?? $field['hint'] ?? '' }}
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <button
            type="button"
            onclick="return confirm('Reset these settings back to their defaults?')"
            wire:click="resetToDefaults"
            class="inline-flex items-center gap-2 rounded-lg cursor-pointer border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800"
        >
            Reset to defaults
        </button>
        <button wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">
            <span wire:loading.remove wire:target="save">Save settings</span>
            <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                <i class="fas fa-spinner fa-spin text-sm"></i>
                Saving...
            </span>
        </button>
    </div>
</div>
