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
                            ? 'text-white border-indigo-600'
                            : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700' }}">
                    {{ $g['title'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Card --}}
    @if(($config['layout'] ?? null) === 'optimize')
        @include('livewire.admin.settings.partials.optimize-settings')
    @else
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="p-5 space-y-5">
                @foreach(($config['fields'] ?? []) as $field)
                    @php
                        $key = $field['key'];
                        $type = $field['type'] ?? 'text';
                        $visibleWhen = $field['visible_when'] ?? null;

                        // visible_when expression builds: (cond1 && cond2 && ...)
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

                        {{-- PERMALINK STRUCTURE --}}
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
                                            hover:border-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-slate-800">
                                        <input type="radio"
                                               id="{{ $id }}"
                                               class="mt-1 h-4 w-4"
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
                                        hover:border-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-slate-800">
                                    <input type="radio"
                                           id="permalink-custom"
                                           class="mt-1 h-4 w-4"
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

                                $categoryPrefix = trim((string) ($data['category_slug_prefix'] ?? ''), '/');
                                $categoryPreview = url($categoryPrefix . '/sample-category');

                                $tagBase = trim((string) ($data['tag_slug_prefix'] ?? ''), '/');
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

                        @elseif($type === 'number')
                            <input type="number" wire:model.defer="data.{{ $key }}"
                               class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-sm">
                        @elseif($type === 'color')
                            <input type="color" wire:model.defer="data.{{ $key }}"
                               class="h-10 w-16 cursor-pointer rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-1 py-1 text-sm">
                        @elseif($type === 'email')
                            <input type="email" wire:model.defer="data.{{ $key }}"
                               class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-3 py-2 text-sm">
                        @elseif($type === 'page_select')
                            @php
                                $pages = \App\Models\Admin\Page::query()
                                    ->published()
                                    ->orderBy('name')
                                    ->get(['id', 'name']);
                                $placeholder = $field['placeholder'] ?? '— Select a page —';
                            @endphp
                            <select wire:model.defer="data.{{ $key }}"
                                class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm">
                                <option value="">{{ $placeholder }}</option>
                                @foreach($pages as $pageOption)
                                    <option value="{{ $pageOption->id }}">{{ $pageOption->name }}</option>
                                @endforeach
                            </select>
                            {{-- select --}}
                        @elseif($type === 'select')
                            @if($key === 'admin_primary_font')
                                <input id="admin-primary-font-value" type="hidden" wire:model.defer="data.{{ $key }}">
                                <div wire:ignore>
                                    <select id="admin-primary-font-select"
                                        class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm">
                                        @foreach(($field['options'] ?? []) as $val => $label)
                                            <option value="{{ $val }}" @selected(($data[$key] ?? null) === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <select wire:model.defer="data.{{ $key }}"
                                    class="w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm">
                                    @foreach(($field['options'] ?? []) as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            @endif

                            {{-- switch (boolean stable) --}}
                        @elseif($type === 'switch')
                            <label class="inline-flex items-center gap-2 text-xs text-slate-700 dark:text-slate-200">
                                <input type="checkbox"
                                   wire:model.live="data.{{ $key }}"
                                   class="h-4 w-4 rounded border-slate-300">
                                <span>{{ $field['hint'] ?? '' }}</span>
                            </label>
                            {{-- ✅ CKEDITOR FOR DYNAMIC SETTINGS --}}
                        @elseif($type === 'richtext')
                            <div wire:ignore class="ck-editor-container">
                                <textarea
                                    id="editor-{{ $key }}"
                                    class="ck-editor-instance"
                                    data-key="{{ $key }}"
                                >{{ $data[$key] ?? '' }}
                            </textarea>
                            </div>
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
            <div class="border-t border-slate-200 dark:border-slate-700 p-4 flex flex-wrap items-center justify-between gap-3">
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
                    <span wire:loading.remove wire:target="save">Save</span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin text-sm"></i>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    @endif
</div>
@push('scripts')

    <script>
        /**
         * মিডিয়া ম্যানেজার থেকে ইমেজ সিলেক্ট করে নির্দিষ্ট CKEditor-এ ইনসার্ট করার হেল্পার
         */
        function openCkeditorImagePicker(editorId) {
            if (typeof openMediaManagerForEditor !== 'function') {
                console.error('Media Manager function (openMediaManagerForEditor) not found!');
                return;
            }

            // আপনার মিডিয়া ম্যানেজার ওপেন করার ফাংশন
            openMediaManagerForEditor(function (url, data) {
                const editor = CKEDITOR.instances[editorId];
                if (!editor) return;

                const selection = editor.getSelection();
                const element = selection && selection.getStartElement ? selection.getStartElement() : null;

                // যদি অলরেডি কোনো ইমেজ সিলেক্ট করা থাকে তবে সেটি রিপ্লেস করবে, নয়তো নতুন ইনসার্ট করবে
                if (element && element.getName && element.getName() === 'img') {
                    element.setAttribute('src', url);
                    if (data?.name) element.setAttribute('alt', data.name);
                } else {
                    editor.insertHtml(`<img src="${url}" alt="${data?.name || ''}" style="max-width:100%; height:auto;"/>`);
                }
            });
        }

        /**
         * ImageManager কাস্টম প্লাগইন রেজিস্ট্রেশন
         */
        if (!CKEDITOR.plugins.get('ImageManager')) {
            CKEDITOR.plugins.add('ImageManager', {
                init: function(editor) {
                    editor.addCommand('openImageManager', {
                        exec: function(ed) {
                            openCkeditorImagePicker(ed.name); // ed.name হলো textarea এর id
                        }
                    });

                    editor.ui.addButton('ImageManager', {
                        label: 'Media Manager',
                        command: 'openImageManager',
                        toolbar: 'insert',
                        icon: 'https://cdn-icons-png.flaticon.com/512/3342/3342137.png' // আপনার আইকন পাথ
                    });
                }
            });
        }

        function initDynamicSettingsEditors() {
            const textareas = document.querySelectorAll('.ck-editor-instance');
            const isDarkMode = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
            const bgColor = isDarkMode ? '#0f172a' : '#ffffff';
            const textColor = isDarkMode ? '#f1f5f9' : '#1e293b';

            textareas.forEach(textarea => {
                const id = textarea.id;
                const dataKey = textarea.getAttribute('data-key');

                if (CKEDITOR.instances[id]) {
                    CKEDITOR.instances[id].destroy(true);
                }

                const editor = CKEDITOR.replace(id, {
                    height: 250,
                    // কাস্টম প্লাগইন যুক্ত করা
                    extraPlugins: 'ImageManager,notification',
                    contentsCss: [
                        `body { background-color: ${bgColor}; color: ${textColor}; font-family: ui-sans-serif, system-ui, sans-serif; padding: 20px; line-height: 1.6; }`,
                        'a { color: #38bdf8; }'
                    ],
                    removeButtons: 'Save,NewPage,Preview,Print,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Image', // ডিফল্ট Image বাটন সরিয়ে কাস্টমটি ব্যবহার করছি
                    toolbarGroups: [
                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
                        { name: 'links' },
                        { name: 'insert', groups: [ 'insert' ] },
                        { name: 'colors' },
                        { name: 'tools' },
                    ]
                });

                editor.on('change', function() {
                    @this.set('data.' + dataKey, editor.getData());
                });
            });
        }

        function initAdminPrimaryFontChoices() {
            const select = document.getElementById('admin-primary-font-select');
            const hiddenInput = document.getElementById('admin-primary-font-value');

            if (!select || !hiddenInput || !window.Choices) {
                return;
            }

            if (select._choicesInstance) {
                select._choicesInstance.destroy();
            }

            const choices = new window.Choices(select, {
                searchEnabled: true,
                shouldSort: false,
                itemSelectText: '',
                allowHTML: false,
            });

            const syncValue = () => {
                hiddenInput.value = select.value;
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            };

            select.addEventListener('change', syncValue);
            select._choicesInstance = choices;
        }

        document.addEventListener('livewire:init', () => initAdminPrimaryFontChoices());
        document.addEventListener('livewire:navigated', () => initAdminPrimaryFontChoices());


        document.addEventListener('livewire:init', () => initDynamicSettingsEditors());
        document.addEventListener('livewire:navigated', () => initDynamicSettingsEditors());
    </script>
@endpush
