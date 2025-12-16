@php use App\Support\PermalinkManager; @endphp
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between gap-3">
        <h1 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
            {{ $config['title'] ?? 'Settings' }}
        </h1>

        <div class="flex flex-wrap gap-2">
            @foreach($groups as $key => $g)
                <a href="{{ route('settings.dynamic', ['group' => $key]) }}"
                   wire:navigate
                   class="text-xs px-3 py-1.5 rounded-lg border
                        {{ $group === $key
                            ? 'bg-indigo-600 text-white border-indigo-600'
                            : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700' }}">
                    {{ $g['title'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Card --}}
    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <div class="p-5 space-y-5">
            @foreach(($config['fields'] ?? []) as $field)
                @php
                    $key = $field['key'];
                    $type = $field['type'] ?? 'text';
                    $visibleWhen = $field['visible_when'] ?? null;

                    // visible_when expression build: (cond1 && cond2 && ...)
                    $visibleExpr = null;
                    if (is_array($visibleWhen) && count($visibleWhen)) {
                        $visibleExpr = collect($visibleWhen)->map(function ($depVal, $depKey) {
                            if (is_bool($depVal)) {
                                return "(\$wire.data.$depKey === " . ($depVal ? 'true' : 'false') . ")";
                            }
                            if (is_numeric($depVal)) {
                                return "(\$wire.data.$depKey == $depVal)";
                            }
                            $depVal = addslashes((string) $depVal);
                            return "(\$wire.data.$depKey == '$depVal')";
                        })->implode(' && ');
                    }
                @endphp

                <div
                    @if($visibleExpr)
                        x-data
                    x-show="{!! $visibleExpr !!}"
                    x-cloak
                    @endif
                >
                    <label class="block text-xs font-semibold uppercase text-slate-700 dark:text-slate-200 mb-1">
                        {{ $field['label'] ?? $key }}
                    </label>

                    {{-- ✅ PERMALINK STRUCTURE --}}
                    @if($type === 'permalink_structure')
                        @php
                            $options = PermalinkManager::availableStructures();
                            $tokens  = PermalinkManager::allowedTokens();

                            $current = $data[$key] ?? PermalinkManager::DEFAULT_STRUCTURE;
                        @endphp

                        <div class="space-y-3">
                            @foreach($options as $optKey => $opt)
                                @php
                                    $id = 'permalink-'.$optKey;
                                    $sampleUrl = PermalinkManager::previewSample($optKey);
                                @endphp

                                <label for="{{ $id }}"
                                       class="flex items-start gap-3 rounded-lg border px-3 py-2 cursor-pointer
                                            border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900
                                            hover:border-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-slate-800 transition-colors">
                                    <input type="radio"
                                           id="{{ $id }}"
                                           class="mt-1 h-4 w-4 text-indigo-600"
                                           wire:model.live="data.{{ $key }}"
                                           value="{{ $optKey }}">
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                                            {{ $opt['label'] }}
                                        </span>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400">
                                            {{ $sampleUrl }}
                                        </span>
                                    </span>
                                </label>
                            @endforeach

                            {{-- custom --}}
                            <label for="permalink-custom"
                                   class="flex items-start gap-3 rounded-lg border px-3 py-2 cursor-pointer
                                        border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900
                                        hover:border-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-slate-800 transition-colors">
                                <input type="radio"
                                       id="permalink-custom"
                                       class="mt-1 h-4 w-4 text-indigo-600"
                                       wire:model.live="data.{{ $key }}"
                                       value="custom">
                                <span>
                                    <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                                        Custom structure
                                    </span>
                                    <span class="block text-xs text-slate-500 dark:text-slate-400">
                                        Example: /news/%year%/%postname%
                                    </span>
                                    <span class="block mt-1 text-[11px] text-slate-400">
                                        Allowed tokens: {{ implode(', ', $tokens) }}
                                    </span>
                                </span>
                            </label>

                            {{-- custom input only when custom selected --}}
                            @if(($data[$key] ?? null) === 'custom')
                                <div class="space-y-1">
                                    <div class="flex rounded-lg shadow-sm">
                                        <span class="inline-flex items-center rounded-l-lg border border-r-0 border-slate-300 dark:border-slate-700
                                                bg-slate-50 dark:bg-slate-900 px-3 text-xs text-slate-500 dark:text-slate-300">
                                            {{ rtrim(url('/'), '/') }}/
                                        </span>
                                        <input type="text"
                                               class="block w-full rounded-r-lg border border-slate-300 dark:border-slate-700
                                                    bg-white dark:bg-slate-900 px-2 py-1.5 text-sm text-slate-900 dark:text-slate-100
                                                    placeholder-slate-400 dark:placeholder-slate-500
                                                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:outline-none"
                                               wire:model.live.debounce.300ms="data.custom_permalink_structure"
                                               placeholder="%category%/%postname%">
                                    </div>

                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                        Allowed tokens: {{ implode(', ', $tokens) }}
                                    </p>

                                    @error("data.custom_permalink_structure")
                                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        {{-- ✅ PERMALINK PREVIEW --}}
                    @elseif($type === 'permalink_preview')
                        @php
                            $structureKey = 'permalink_structure';
                            $structure = $data[$structureKey] ?? PermalinkManager::DEFAULT_STRUCTURE;
                            $custom    = $data['custom_permalink_structure'] ?? null;

                            $sample = PermalinkManager::previewSample($structure, $custom);

                            $catPrefix = $data['category_slug_prefix_enabled'] ?? true;
                            $catPrefix = is_null($catPrefix) || (bool)$catPrefix;

                            $categoryPreview = $catPrefix
                                ? url('category/sample-category')
                                : url('sample-category');

                            $tagBase = trim((string)($data['tag_slug_prefix'] ?? 'tag'), '/');
                            $tagPreview = url($tagBase . '/sample-tag');
                        @endphp

                        <div class="space-y-3">
                            <div
                                class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-200">
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">Sample URL</div>
                                <div class="font-semibold">{{ $sample }}</div>
                            </div>

                            <div
                                class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-200">
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">Category preview</div>
                                <div class="font-semibold">{{ $categoryPreview }}</div>
                            </div>

                            <div
                                class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-200">
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">Tag preview</div>
                                <div class="font-semibold">{{ $tagPreview }}</div>
                            </div>
                        </div>

                        {{-- textarea --}}
                    @elseif($type === 'textarea')
                        <textarea wire:model.defer="data.{{ $key }}" rows="3"
                                  class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-sm"></textarea>

                        {{-- select --}}
                    @elseif($type === 'select')
                        <select wire:model.defer="data.{{ $key }}"
                                class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm">
                            @foreach(($field['options'] ?? []) as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        {{-- switch (boolean stable) --}}
                    @elseif($type === 'switch')
                        <label class="inline-flex items-center gap-2 text-xs text-slate-700 dark:text-slate-200">
                            <input type="checkbox"
                                   wire:model.live="data.{{ $key }}"
                                   class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                            <span>{{ $field['hint'] ?? '' }}</span>
                        </label>

                        {{-- image --}}
                    @elseif($type === 'image')
                        @include('mediamanager::includes.media-input', [
                            'name'  => "data.$key",
                            'id'    => $key,
                            'label' => $field['label'] ?? $key,
                            'value' => $data[$key] ?? '',
                        ])

                        {{-- default text --}}
                    @else
                        <input type="text" wire:model.defer="data.{{ $key }}"
                               class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-sm">
                    @endif

                    {{-- hint --}}
                    @if(!empty($field['hint']) && !in_array($type, ['switch','permalink_structure'], true))
                        <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">{{ $field['hint'] }}</p>
                    @endif

                    {{-- error --}}
                    @error("data.$key")
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div class="border-t border-slate-200 dark:border-slate-700 p-4 flex justify-end">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">
                <span wire:loading.remove>Save</span>
                <span wire:loading class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity=".25"/>
                        <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" opacity=".75"/>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </div>
</div>
