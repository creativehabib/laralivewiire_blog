<div class="space-y-4">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-4">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-300">Blog</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Posts</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">
            {{ $postId ? 'Edit post' : 'Create a new post' }}
        </span>
    </nav>

    {{-- Flash --}}
    @if (session('message'))
        <div
            class="mb-4 rounded-lg border px-4 py-2 text-xs
                   border-emerald-300 bg-emerald-50 text-emerald-800
                   dark:border-emerald-600/40 dark:bg-emerald-600/10 dark:text-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- LEFT: main form --}}
        <div class="lg:col-span-2">
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm
                       dark:border-slate-700 dark:bg-slate-800">
                <form wire:submit.prevent="save" id="post-form">
                    <div class="px-6 py-5 space-y-5">

                        {{-- Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] text-slate-500">
                                    Post title
                                </span>
                                <!-- Counter -->
                                <span class="text-[11px]" :class="( $wire.name?.length || 0 ) > {{ $nameMax }} ? 'text-rose-500' : 'text-slate-400'">
                                    <span>{{ strlen($name ?? '') }}</span> / {{ $nameMax }}
                                </span>
                            </div>

                            <input
                                type="text"
                                wire:model.live="name"
                                maxlength="{{ $nameMax }}"
                                class="block w-full rounded-lg border px-3 py-2 text-sm border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                                placeholder="Post title"
                            >

                            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Permalink --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Permalink <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model.defer="slug"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="post-slug">
                            @error('slug')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror

                            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">
                                Preview:
                                <a href="{{ preview_url('post', $this->slug) }}" target="_blank"
                                   class="text-sky-600 dark:text-sky-400">
                                    {{ preview_url('post', $this->slug) }}
                                </a>
                            </p>
                        </div>

                        {{-- Short description --}}
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Short Description
                            </label>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] text-slate-500">
                                    Short intro for list/SEO
                                </span>
                                <!-- Counter -->
                                <span class="text-[11px]"
                                      :class="( $wire.description?.length || 0 ) > {{ $descMax }} ? 'text-rose-500' : 'text-slate-400'">
                                    <span>{{ strlen($description ?? '') }}</span> / {{ $descMax }}
                                </span>
                            </div>

                            <textarea
                                wire:model.live="description"
                                rows="4"
                                maxlength="{{ $descMax }}"
                                class="block w-full rounded-lg border px-3 py-2 text-sm border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
                                placeholder="Short description..."
                            ></textarea>

                            @error('description')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Is featured --}}
                        <div class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-200">
                            <input type="checkbox"
                                   wire:model="is_featured"
                                   class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500
                                          dark:border-slate-600 dark:bg-slate-900">
                            <span>Is featured?</span>
                        </div>

                        {{-- Content --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Content
                            </label>
                            <div class="flex gap-2 text-[11px]">
                                <button type="button"
                                        class="rounded border border-slate-300 px-2 py-1 text-xs text-slate-600">
                                    Show/Hide Editor
                                </button>
                                <button type="button"
                                        onclick="openCkeditorImagePicker('content')"
                                        class="rounded border border-slate-300 px-2 py-1 text-xs text-slate-600">
                                    Add media
                                </button>
                            </div>
                            {{-- এখানে তুমি চাইলে TinyMCE/CKEditor init করতে পারো --}}
                            <div wire:ignore>
                                <textarea
                                    id="content"
                                    name="content"
                                    rows="12"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm
                                           border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                           focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                           dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                    placeholder="Write your content here..."
                                >{{ $content }}</textarea>
                            </div>
                            @error('content')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SEO box --}}
                        <div class="pt-4">
                            @include('admin.meta.seo-meta-box', [
                                'baseUrl'     => $baseUrl,
                                'previewType' => 'post',   // preview_url('post', $slug)
                            ])
                        </div>

                        @php
                            $seo = $this->seoAnalysis;
                        @endphp

                        @include('admin.meta.yoast-box', ['seo' => $seo])
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT: sidebar --}}
        <div class="space-y-4">
            {{-- Publish --}}
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm
                       dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Publish
                    </h3>
                </div>
                <div class="px-5 py-4 space-y-2">
                    <div class="flex gap-2">
                        <button
                            type="button"
                            wire:click="save('stay')"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-500">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            Save
                        </button>
                        <button
                            type="button"
                            wire:click="save('exit')"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50
                                   dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            Save &amp; Exit
                        </button>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm
                       dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Status <span class="text-rose-500">*</span>
                    </h3>
                </div>
                <div class="px-5 py-4">
                    <select
                        wire:model="status"
                        class="block w-full rounded-lg border px-3 py-2 text-sm
                               border-slate-300 bg-white text-slate-800 focus:border-sky-500 focus:ring-sky-500
                               dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Categories card --}}
            <div class="rounded-lg border border-gray-200 bg-white">
                {{-- Header --}}
                <div class="px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Categories
                    </h3>
                </div>

                {{-- Search --}}
                <div class="px-4 pt-3 pb-2 border-b border-gray-100">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="categorySearch"
                            placeholder="Search..."
                            class="block w-full rounded-md border border-gray-200 bg-gray-50 py-2 pl-3 pr-8 text-xs
                       text-gray-800 placeholder:text-gray-400
                       focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400">
                            <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                        </span>
                    </div>
                </div>

                {{-- List --}}
                <div class="px-4 py-3 max-h-72 overflow-y-auto space-y-1">
                    @forelse($rootCategories as $cat)
                        @include('admin.posts.partials.category-checkbox-item', [
                            'category' => $cat,
                            'level'    => 0,
                            'selected' => $category_ids,
                        ])
                    @empty
                        <p class="text-xs text-gray-500">
                            @if($categorySearch)
                                No categories found for "<span class="font-semibold">{{ $categorySearch }}</span>".
                            @else
                                No categories found.
                            @endif
                        </p>
                    @endforelse
                </div>

                {{-- Error --}}
                @error('category_ids')
                <p class="px-4 pb-3 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image (thumbnail) --}}
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm
                       dark:border-slate-700 dark:bg-slate-800 p-3">
                @include('mediamanager::includes.media-input', [
                    'name'  => 'image',
                    'id'    => 'image',
                    'label' => 'Image',
                    'value' => $image ?? '',
                ])
            </div>

            {{-- Tags --}}
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm
                       dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Tags
                    </h3>
                </div>
                <div class="px-5 py-4 space-y-1">
                    <div class="flex flex-wrap gap-1 mb-1">
                        @if($selectedTagIds)
                            @php
                                $tags = \App\Models\Admin\Tag::whereIn('id', $selectedTagIds)->get();
                            @endphp

                            @foreach($tags as $tag)
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-medium text-sky-700 border border-sky-200">
                    <i class="fa-solid fa-tag text-[10px]"></i>
                    {{ $tag->name }}
                    <button
                        type="button"
                        wire:click="removeTag({{ $tag->id }})"
                        class="ml-1 text-[10px] text-slate-500 hover:text-rose-500 cursor-pointer">
                        <i class="fa fa-close"></i>
                    </button>
                </span>
                            @endforeach
                        @else
                            <span class="text-[11px] text-slate-400">
                No tags selected yet.
            </span>
                        @endif
                    </div>

                    {{-- Input + suggestions --}}
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="tagInput"
                            wire:keydown.enter.prevent="createTagFromInput"
                            class="block w-full rounded-lg border px-3 py-2 text-sm
                                      border-slate-300 bg-white text-slate-800 focus:border-sky-500 focus:ring-sky-500
                                      dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100"
                            placeholder="Type to search or create tags (press Enter to add)"
                        >

                        {{-- Suggestions dropdown --}}
                        @if(!empty($tagInput) && !empty($tagSuggestions))
                            <div
                                class="absolute z-30 mt-1 w-full rounded-md border border-slate-200 bg-white shadow text-xs max-h-48 overflow-auto">
                                @foreach($tagSuggestions as $suggest)
                                    <button
                                        type="button"
                                        wire:click="addTag({{ $suggest['id'] }})"
                                        class="flex w-full items-center justify-between px-3 py-1.5 text-left hover:bg-slate-50 cursor-pointer">
                                        <span>{{ $suggest['name'] }}</span>
                                        <span class="text-[10px] text-slate-400">Click to add</span>
                                    </button>
                                @endforeach
                            </div>
                        @elseif(!empty($tagInput))
                            {{-- যখন টাইপ করা হচ্ছে কিন্তু সাজেশন পাচ্ছে না → নতুন create হবে Enter চাপলে --}}
                            <div class="absolute z-30 mt-1 w-full rounded-md border border-dashed border-slate-200 bg-slate-50 px-3 py-1.5 text-[11px] text-slate-500">
                                No match found. Press <span class="font-semibold">Enter</span> to create
                                "<span class="font-semibold">{{ $tagInput }}</span>".
                            </div>
                        @endif
                    </div>

                    <p class="mt-1 text-[11px] text-slate-400">
                        Existing tags automatically appear as you type. Press <span class="font-semibold">Enter</span> to create &amp; add a new tag.
                    </p>
                </div>
            </div>

            {{-- Layout / options --}}
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm
                       dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Options
                    </h3>
                </div>
                <div class="px-5 py-4 space-y-3 text-xs text-slate-700 dark:text-slate-200">
                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               wire:model="allow_comments"
                               class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500
                                      dark:border-slate-600 dark:bg-slate-900">
                        <span>Allow comments</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               wire:model="is_breaking"
                               class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500
                                      dark:border-slate-600 dark:bg-slate-900">
                        <span>Is breaking news?</span>
                    </label>

                    <div>
                        <label class="block text-[11px] font-semibold mb-1">
                            Format type
                        </label>
                        <input type="text"
                               wire:model.defer="format_type"
                               class="block w-full rounded-lg border px-3 py-2 text-sm
                                      border-slate-300 bg-white text-slate-800 focus:border-sky-500 focus:ring-sky-500
                                      dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100"
                               placeholder="standard, video, gallery...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        /**
         * মিডিয়া ম্যানেজার থেকে ইমেজ সিলেক্ট করে CKEditor-এ ইনসার্ট করার হেল্পার
         */
        function openCkeditorImagePicker(editorId) {
            if (typeof openMediaManagerForEditor !== 'function') {
                console.error('openMediaManagerForEditor() not found');
                return;
            }

            openMediaManagerForEditor(function (url, data) {
                const editor = CKEDITOR.instances[editorId];
                if (!editor) return;

                const selection = editor.getSelection();
                const element = selection && selection.getStartElement
                    ? selection.getStartElement()
                    : null;

                if (element && element.getName && element.getName() === 'img') {
                    element.setAttribute('src', url);
                    if (data?.name) {
                        element.setAttribute('alt', data.name);
                    }
                } else {
                    editor.insertHtml(
                        '<img src="' + url + '" alt="' + (data?.name || '') + '"/>'
                    );
                }
            });
        }

        // 🔸 কাস্টম প্লাগইন: ImageManager বাটন
        CKEDITOR.plugins.add('ImageManager', {
            icons: 'image-plus',
            init: function(editor) {
                editor.addCommand('openImageManager', {
                    exec: function(ed) {
                        openCkeditorImagePicker(ed.name); // textarea এর id = editor.name
                    }
                });

                editor.ui.addButton('ImageManager', {
                    label: 'Media Manager',
                    command: 'openImageManager',
                    toolbar: 'insert',
                    icon: '/assets/icons/image-plus.svg' // চাইলে কাস্টম আইকনও দিতে পার
                });
            }
        });
        /**
         * CKEditor init ফাংশন
         */
        function initCkeditor() {
            const textarea = document.getElementById('content');
            if (! textarea) return;

            // আগের instance থাকলে destroy
            if (CKEDITOR.instances.content) {
                CKEDITOR.instances.content.destroy(true);
            }

            const editor = CKEDITOR.replace('content', {
                mathJaxLib: '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
                height: 300,
                uiColor: '',
                removePlugins: 'cloudservices,uploadimage,uploadfile',
                extraPlugins: 'imagemenu,mathjax,tableresize,wordcount,notification,ImageManager,codesnippet,embed',
                wordcount: { showCharCount: true, showWordCount: true },
                toolbar: [
                    {items: ['Undo', 'Redo']},
                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                    { name: 'document', items: ['Source', '-', 'Preview'] },
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'] },
                    { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'RemoveFormat','CopyFormatting'] },
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript','ImageManager'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock','BidiLtr', 'BidiRtl'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    {
                        name: 'insert',
                        items: [
                            'Image',
                            'Table',
                            'HorizontalRule',
                            'SpecialChar',
                            'Mathjax',
                            '-',
                            'Iframe',
                            'Smiley',
                            'ImageMenu','CodeSnippet','EasyImage','Embed',
                            // custom image button চাইলে এখানে আর plugin যোগ করবে
                        ]
                    },
                    { name: 'colors', items: ['TextColor', 'BGColor', 'ShowBlocks'] },
                    { name: 'tools', items: ['Maximize'] }
                ],
                allowedContent: true,
                extraAllowedContent: '*(*){*}',
            });

            // 🔥 CKEditor → Livewire sync
            editor.on('change', function (e) {
            @this.set('content', e.editor.getData());
            });
        }

        // Livewire v3 → সাধারণত 'livewire:init' ইভেন্ট ফায়ার হয়
        document.addEventListener('livewire:init', function () {
            initCkeditor();
        });

        // যদি component থেকে re-init করতে চাও:
        // $this->dispatch('reinit-ckeditor');
        document.addEventListener('reinit-ckeditor', function () {
            initCkeditor();
        });
    </script>
@endpush

