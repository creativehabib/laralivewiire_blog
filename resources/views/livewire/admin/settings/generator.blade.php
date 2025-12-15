<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
            {{ $config['title'] ?? 'Settings' }}
        </h1>

        <div class="flex flex-wrap gap-2">
            @foreach($groups as $key => $g)
                <a href="{{ route('settings.dynamic', ['group' => $key]) }}"
                   class="text-xs px-3 py-1.5 rounded-lg border
                          {{ $group === $key ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700' }}">
                    {{ $g['title'] }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <div class="p-5 space-y-5">
            @foreach(($config['fields'] ?? []) as $field)
                @php
                    $key = $field['key'];
                    $type = $field['type'] ?? 'text';
                    $visibleWhen = $field['visible_when'] ?? null;
                @endphp

                <div
                    @if($visibleWhen)
                        x-data
                    x-show="
                            @foreach($visibleWhen as $depKey => $depVal)
                                ($wire.data.{{ $depKey }} == '{{ $depVal }}')
                            @endforeach
                        "
                    x-cloak
                    @endif
                >
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                        {{ $field['label'] ?? $key }}
                    </label>

                    {{-- ✅ PERMALINK STRUCTURE (radio cards) --}}
                    @if($type === 'permalink_structure')
                        @php
                            $options = \App\Support\PermalinkManager::availableStructures();
                            $tokens  = \App\Support\PermalinkManager::allowedTokens();
                        @endphp

                        <div class="space-y-3">
                            @foreach($options as $optKey => $opt)
                                @php
                                    $id = 'permalink-'.$optKey;
                                    $sampleUrl = \App\Support\PermalinkManager::previewSample($optKey);
                                @endphp

                                <label for="{{ $id }}"
                                       class="flex items-start gap-3 rounded-lg border px-3 py-2 cursor-pointer
                                              border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900
                                              hover:border-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-slate-800 transition-colors">
                                    <input type="radio"
                                           id="{{ $id }}"
                                           class="mt-1 h-4 w-4 text-indigo-600"
                                           wire:model.defer="data.permalink_structure"
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
                                       wire:model.defer="data.permalink_structure"
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
                        </div>

                        {{-- ✅ PREVIEW BLOCK --}}
                    @elseif($type === 'permalink_preview')
                        @php
                            $structure = $data['permalink_structure'] ?? \App\Support\PermalinkManager::DEFAULT_STRUCTURE;
                            $custom    = $data['custom_permalink_structure'] ?? null;

                            $sample = \App\Support\PermalinkManager::previewSample($structure, $custom);

                            $catPrefix = (bool) ($data['category_slug_prefix_enabled'] ?? true);
                            $categoryPreview = $catPrefix
                                ? url('category/sample-category')
                                : url('sample-category');

                            $tagBase = trim((string)($data['tag_slug_prefix'] ?? 'tags'), '/');
                            $tagPreview = url($tagBase.'/sample-tag');
                        @endphp

                        <div class="space-y-3">
                            <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-200">
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">Sample URL</div>
                                <div class="font-semibold">{{ $sample }}</div>
                            </div>

                            <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-200">
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">Category preview</div>
                                <div class="font-semibold">{{ $categoryPreview }}</div>
                            </div>

                            <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-xs text-slate-700 dark:text-slate-200">
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

                        {{-- ✅ switch --}}
                    @elseif($type === 'switch')
                        {{-- unchecked হলে null আসার সমস্যা avoid করতে hidden --}}
                        <input type="hidden" wire:model.defer="data.{{ $key }}" value="0">

                        <label class="inline-flex items-center gap-2 text-xs text-slate-700 dark:text-slate-200">
                            <input type="checkbox"
                                   wire:model.defer="data.{{ $key }}"
                                   value="1"
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

                    @if(!empty($field['hint']) && $type !== 'switch' && $type !== 'permalink_structure')
                        <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">{{ $field['hint'] }}</p>
                    @endif

                    @error("data.$key")
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

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
