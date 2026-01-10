@php
    $seo = $this->seoAnalysis;
@endphp

<div class="space-y-4" x-data="{
    name: @entangle('name').live,
    description: @entangle('description').live,
    builderState: @entangle('builderState').live,
    builderEnabled: false,
    sections: [],
    nextSectionId: 1,
    activeSectionId: null,
    activeBlockId: null,
    showSectionModal: false,
    showBlockModal: false,
    showBlockSettingsModal: false,
    sectionTab: 'general',
    blockTab: 'general',
    selectedSidebar: 'none',
    blocks: [
        { id: 1, name: 'Featured + Sidebar', layout: 'list-sidebar' },
        { id: 2, name: 'Stacked Cards', layout: 'stacked' },
        { id: 3, name: 'Featured + List', layout: 'featured-list' },
        { id: 4, name: 'Hero + List', layout: 'hero-list' },
        { id: 5, name: 'Half Width', layout: 'half-width' }
    ],
    defaultBlockSettings() {
        return {
            title: '',
            icon: '',
            url: '',
            categories: [],
            tags: '',
            trending: false,
            exclude: '',
            sort: 'recent',
            order: 'desc',
            count: 5,
            offset: 0,
            days: '',
            pagination: 'disable',
            contentOnly: false,
            darkMode: false,
            primaryColor: '',
            backgroundColor: '',
            secondaryColor: '',
            ajaxFilters: false,
            moreButton: false,
            titleLength: '',
            showExcerpt: true,
            excerptLength: '',
            readMoreButton: false,
            hideFirstThumbnail: false,
            hideSmallThumbnails: false,
            postMeta: true,
            mediaIcon: false,
        };
    },
    init() {
        const storedSections = Array.isArray(this.builderState?.sections) ? this.builderState.sections : [];
        const storedEnabled = this.builderState?.enabled ?? false;

        this.sections = storedSections.map((section) => ({
            id: section.id ?? Date.now() + Math.random(),
            blocks: Array.isArray(section.blocks)
                ? section.blocks.map((block) => ({
                    ...block,
                    settings: {
                        ...this.defaultBlockSettings(),
                        ...(block.settings ?? {})
                    }
                }))
                : [],
            sidebar: section.sidebar ?? 'none'
        }));
        this.builderEnabled = storedEnabled;
        this.nextSectionId = this.sections.length ? Math.max(...this.sections.map((section) => section.id)) + 1 : 1;
    },
    findActiveBlock() {
        const section = this.sections.find((item) => item.id === this.activeSectionId);

        if (!section) {
            return null;
        }

        return section.blocks.find((block) => block.id === this.activeBlockId) ?? null;
    },
    syncBuilderState() {
        this.builderState = {
            enabled: this.builderEnabled,
            sections: this.sections
        };
    },
    toggleBuilder() {
        this.builderEnabled = !this.builderEnabled;
        this.syncBuilderState();
    },
    addSection() {
        this.sections.push({ id: this.nextSectionId++, blocks: [], sidebar: 'none' });
        this.syncBuilderState();
    },
    removeSection(sectionId) {
        this.sections = this.sections.filter((section) => section.id !== sectionId);
        this.syncBuilderState();
    },
    openSectionModal(sectionId = null) {
        const section = this.sections.find((item) => item.id === sectionId);
        this.activeSectionId = sectionId;
        this.sectionTab = 'general';
        this.selectedSidebar = section?.sidebar ?? 'none';
        this.showSectionModal = true;
    },
    openBlockModal(sectionId = null) {
        this.activeSectionId = sectionId;
        this.activeBlockId = null;
        this.showBlockSettingsModal = false;
        this.showBlockModal = true;
    },
    openBlockSettings(sectionId, blockId) {
        this.activeSectionId = sectionId;
        this.activeBlockId = blockId;
        this.blockTab = 'general';
        this.showBlockSettingsModal = true;
    },
    addBlockToSection(block) {
        const section = this.sections.find((item) => item.id === this.activeSectionId);

        if (!section) {
            return;
        }

        const newBlock = {
            id: Date.now(),
            name: block.name,
            layout: block.layout,
            settings: this.defaultBlockSettings()
        };

        section.blocks.push(newBlock);
        this.syncBuilderState();
        this.openBlockSettings(section.id, newBlock.id);
    },
    removeBlockFromSection(sectionId, blockId) {
        const section = this.sections.find((item) => item.id === sectionId);

        if (!section) {
            return;
        }

        section.blocks = section.blocks.filter((block) => block.id !== blockId);
        this.syncBuilderState();
    },
    updateSidebarSelection(value) {
        this.selectedSidebar = value;
        const section = this.sections.find((item) => item.id === this.activeSectionId);

        if (!section) {
            return;
        }

        section.sidebar = value;
        this.syncBuilderState();
    },
    updateActiveBlockField(field, value) {
        const block = this.findActiveBlock();

        if (!block) {
            return;
        }

        block.settings = block.settings ?? {};
        block.settings[field] = value;
        this.syncBuilderState();
    },
    toggleActiveBlockCategory(categoryId) {
        const block = this.findActiveBlock();

        if (!block) {
            return;
        }

        block.settings = block.settings ?? {};
        block.settings.categories = block.settings.categories ?? [];

        if (block.settings.categories.includes(categoryId)) {
            block.settings.categories = block.settings.categories.filter((id) => id !== categoryId);
        } else {
            block.settings.categories.push(categoryId);
        }

        this.syncBuilderState();
    }
}">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-300">Pages</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">
            {{ $pageId ? 'Edit page' : 'Create page' }}
        </span>
    </nav>

    {{-- Flash --}}
    @if (session('message'))
        <div class="rounded-lg border px-4 py-2 text-xs border-emerald-300 bg-emerald-50 text-emerald-800
                    dark:border-emerald-600/40 dark:bg-emerald-600/10 dark:text-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- LEFT --}}
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3 text-slate-800 dark:text-slate-100">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-600 dark:bg-sky-900/40">
                            <i class="fa-solid fa-file-lines"></i>
                        </span>
                        <div>
                            <h3 class="text-sm font-semibold">Page details</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Title, slug, description and content.</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="save" id="page-form">
                    <div class="px-6 py-5 space-y-5">

                        {{-- Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] text-slate-500">Page title</span>
                                <span class="text-[11px]" :class="(name?.length||0) > {{ $nameMax }} ? 'text-rose-500' : 'text-slate-400'">
                                    <span x-text="(name?.length||0)"></span> / {{ $nameMax }}
                                </span>
                            </div>

                            <input
                                type="text"
                                wire:model.live="name"
                                maxlength="{{ $nameMax }}"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="Page title">
                            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Slug --}}
                        <x-admin.permalink-field
                            label="Permalink"
                            :base-url="$baseUrl"
                            preview-type="page"
                            :slug="$this->slug"
                            placeholder="slug"
                            label-class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1"
                            container-class=""
                            input-wrapper-class="mt-2 flex rounded-md shadow-sm"
                            prefix-class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs sm:text-sm"
                            input-class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm placeholder:text-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out outline-none"
                            action-wrapper-class="inline-flex items-center border border-l-0 border-gray-300 dark:border-gray-700 rounded-r-md bg-gray-50 dark:bg-gray-900 overflow-hidden"
                            action-button-class="px-3 py-2 text-gray-400 hover:text-amber-500 cursor-pointer transition-colors"
                            preview-wrapper-class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            preview-class="text-blue-500 dark:text-blue-400 underline"
                            error-class="text-xs text-red-600 mt-1"
                            generate-action="generateSlug"
                            wire:model.blur="slug"
                        />

                        {{-- Description --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Short description
                            </label>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] text-slate-500">Intro for SEO/listing</span>
                                <span class="text-[11px]" :class="(description?.length||0) > {{ $descMax }} ? 'text-rose-500' : 'text-slate-400'">
                                    <span x-text="(description?.length||0)"></span> / {{ $descMax }}
                                </span>
                            </div>

                            <textarea
                                wire:model.live="description"
                                maxlength="{{ $descMax }}"
                                rows="4"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="Short summary..."></textarea>

                            @error('description') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Content --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Content
                            </label>

                            <div class="flex flex-wrap items-center gap-2 text-[11px] mb-2">
                                <button type="button"
                                        class="inline-flex items-center gap-2 rounded border cursor-pointer border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-sky-400 hover:text-sky-600 dark:border-slate-600 dark:text-slate-200 dark:hover:text-sky-300"
                                        @click="toggleBuilder">
                                    <i class="fa-solid fa-layer-group text-[11px]"></i>
                                    <span x-text="builderEnabled ? 'Disable the Builder' : 'Enable the Builder'"></span>
                                </button>
                                <button type="button"
                                        onclick="openCkeditorImagePicker('page_content')"
                                        class="inline-flex items-center gap-1 rounded border cursor-pointer border-slate-300 px-2 py-1 text-xs text-slate-600 dark:border-slate-600 dark:text-slate-200"
                                        x-show="!builderEnabled"
                                        x-cloak>
                                    <i class="fa-regular fa-images"></i>
                                    Add media
                                </button>
                            </div>

                            <div x-show="!builderEnabled" x-cloak>
                                <div wire:ignore>
                                    <textarea
                                        id="page_content"
                                        rows="14"
                                        class="block w-full rounded-lg border px-3 py-2 text-sm
                                               border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                               focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                               dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                    >{{ $content }}</textarea>
                                </div>
                            </div>

                            <div x-show="builderEnabled" x-cloak class="rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex items-center justify-between px-4 py-3 text-xs font-semibold text-white bg-sky-600">
                                    <span>TieLabs Builder</span>
                                    <div class="flex items-center gap-3 text-white/80">
                                        <span class="text-[11px]">The content in the editor above will be ignored.</span>
                                        <button type="button" class="text-white/80 cursor-pointer hover:text-white">
                                            <i class="fa-solid fa-chevron-up"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4 space-y-4">
                                    <template x-for="section in sections" :key="section.id">
                                        <div class="border border-dashed border-slate-200 dark:border-slate-700">
                                            <div class="flex items-center justify-between bg-sky-600 px-4 py-2 text-xs font-semibold text-white">
                                                <span>Section</span>
                                                <div class="flex items-center gap-2">
                                                    <button type="button" class="h-7 w-7 rounded bg-sky-700 cursor-pointer hover:bg-sky-800" @click="removeSection(section.id)">
                                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                                    </button>
                                                    <button type="button" class="h-7 w-7 rounded bg-sky-700 cursor-pointer hover:bg-sky-800" @click="openSectionModal(section.id)">
                                                        <i class="fa-solid fa-pen text-[11px]"></i>
                                                    </button>
                                                    <button type="button" class="h-7 w-7 rounded bg-sky-700 cursor-pointer hover:bg-sky-800">
                                                        <i class="fa-solid fa-chevron-up text-[11px]"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="grid gap-4 p-4"
                                                 :class="{
                                                     'md:grid-cols-[1fr_220px]': section.sidebar === 'right',
                                                     'md:grid-cols-[220px_1fr]': section.sidebar === 'left',
                                                     'md:grid-cols-1': section.sidebar === 'none'
                                                 }">
                                                <div class="space-y-3 rounded border border-dashed border-slate-200 p-3 dark:border-slate-700">
                                                    <template x-if="section.blocks.length === 0">
                                                        <div class="flex min-h-[120px] items-center justify-center">
                                                            <button type="button" class="rounded bg-sky-600 px-5 py-2 text-xs font-semibold text-white hover:bg-sky-700 cursor-pointer" @click="openBlockModal(section.id)">
                                                                Add Block
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <template x-if="section.blocks.length > 0">
                                                        <div class="space-y-2">
                                                            <template x-for="block in section.blocks" :key="block.id">
                                                                <div class="flex items-center justify-between rounded bg-slate-800 px-3 py-2 text-xs font-semibold text-white">
                                                                    <span x-text="block.name"></span>
                                                                    <div class="flex items-center gap-2">
                                                                        <button type="button" class="h-7 w-7 rounded bg-slate-700 cursor-pointer hover:bg-slate-600" @click="removeBlockFromSection(section.id, block.id)">
                                                                            <i class="fa-solid fa-trash text-[11px]"></i>
                                                                        </button>
                                                                        <button type="button" class="h-7 w-7 rounded bg-slate-700 cursor-pointer hover:bg-slate-600" @click="openBlockSettings(section.id, block.id)">
                                                                            <i class="fa-solid fa-pen text-[11px]"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="w-full rounded border border-dashed border-slate-300 px-3 py-2 text-xs cursor-pointer font-semibold text-slate-500 hover:text-slate-700 dark:border-slate-600 dark:text-slate-400" @click="openBlockModal(section.id)">
                                                                Add Block
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                                <div class="rounded border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800"
                                                     x-show="section.sidebar !== 'none'"
                                                     x-cloak
                                                     :class="section.sidebar === 'left' ? 'md:order-first' : 'md:order-last'">
                                                    <div class="border-b border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:text-slate-200">
                                                        Sidebar
                                                    </div>
                                                    <div class="p-3">
                                                        <button type="button" class="flex w-full items-center justify-center gap-2 cursor-pointer rounded border border-dashed border-slate-300 bg-white px-3 py-2 text-xs text-slate-500 hover:text-slate-700 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-400">
                                                            <i class="fa-solid fa-gear text-[11px]"></i>
                                                            Manage Widgets
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <button type="button"
                                            class="flex w-full items-center justify-center gap-2 rounded border border-dashed cursor-pointer border-slate-300 px-3 py-6 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:border-slate-600 dark:text-slate-400"
                                            @click="addSection(); openSectionModal()">
                                        <i class="fa-solid fa-plus"></i>
                                        Add Section
                                    </button>
                                </div>
                            </div>

                            <div x-show="showSectionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4">
                                <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900">
                                    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
                                        <h3 class="text-base font-semibold">Edit Section</h3>
                                        <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold cursor-pointer hover:bg-sky-500" @click="showSectionModal = false">
                                            Done
                                        </button>
                                    </div>
                                    <div class="flex bg-sky-600 text-xs font-semibold text-white">
                                        <button type="button" class="relative px-5 py-3 cursor-pointer" :class="sectionTab === 'general' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'general'">
                                            General
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'general'"></span>
                                        </button>
                                        <button type="button" class="relative px-5 py-3 cursor-pointer" :class="sectionTab === 'background' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'background'">
                                            Background
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'background'"></span>
                                        </button>
                                        <button type="button" class="relative px-5 py-3 cursor-pointer" :class="sectionTab === 'styling' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'styling'">
                                            Styling
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'styling'"></span>
                                        </button>
                                    </div>
                                    <div class="max-h-[70vh] space-y-6 overflow-y-auto bg-slate-50 p-6 dark:bg-slate-800">
                                        <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900" x-show="sectionTab === 'general'" x-cloak>
                                            <div class="border-b border-slate-200 pb-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">
                                                Section Title
                                            </div>
                                            <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                                                <span>Section Title</span>
                                                <label class="inline-flex items-center gap-2">
                                                    <input type="checkbox" class="peer sr-only">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <span class="h-5 w-5 translate-x-1 rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900" x-show="sectionTab === 'general'" x-cloak>
                                            <div class="border-b border-slate-200 pb-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">
                                                Section Layout
                                            </div>
                                            <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                                                <div>
                                                    <p class="font-medium">Stretch Section</p>
                                                    <p class="text-xs text-slate-500">Stretch the section to the full width of the page, supported if the site layout is Full-Width.</p>
                                                </div>
                                                <label class="inline-flex items-center gap-2">
                                                    <input type="checkbox" class="peer sr-only">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <span class="h-5 w-5 translate-x-1 rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900" x-show="sectionTab === 'general'" x-cloak>
                                            <div class="border-b border-slate-200 pb-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">
                                                Sidebar Settings
                                            </div>
                                            <p class="text-xs text-slate-500">Sidebar Position</p>
                                            <div class="grid gap-4 sm:grid-cols-3">
                                                <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold cursor-pointer text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                                        :class="selectedSidebar === 'none' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                                                        @click="updateSidebarSelection('none')">
                                                    <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700"></div>
                                                    Without Sidebar
                                                </button>
                                                <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold cursor-pointer text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                                        :class="selectedSidebar === 'right' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                                                        @click="updateSidebarSelection('right')">
                                                    <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700">
                                                        <div class="h-full w-4/5 rounded bg-slate-300 dark:bg-slate-600"></div>
                                                    </div>
                                                    Sidebar Right
                                                </button>
                                                <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold cursor-pointer text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                                        :class="selectedSidebar === 'left' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                                                        @click="updateSidebarSelection('left')">
                                                    <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700">
                                                        <div class="h-full w-1/5 rounded bg-slate-400 dark:bg-slate-600"></div>
                                                    </div>
                                                    Sidebar Left
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="showBlockModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4">
                                <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900">
                                    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
                                        <h3 class="text-base font-semibold">Add Block</h3>
                                        <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold hover:bg-sky-500" @click="showBlockModal = false; showBlockSettingsModal = false">
                                            Done
                                        </button>
                                    </div>
                                    <div class="grid gap-4 bg-slate-50 p-4 dark:bg-slate-800 sm:grid-cols-3">
                                        <template x-for="block in blocks" :key="block.id">
                                            <button type="button"
                                                    class="rounded border border-slate-200 bg-white text-left text-xs font-semibold text-slate-600 cursor-pointer hover:border-sky-500 hover:text-sky-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                                    @click="addBlockToSection(block)">
                                                <div class="rounded bg-slate-100 p-3 dark:bg-slate-800">
                                                    <div class="space-y-4" x-show="block.layout === 'list-sidebar'" x-cloak>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div class="space-y-2">
                                                                <div class="h-20 w-full bg-slate-400"></div>
                                                                <div class="space-y-1.5">
                                                                    <div class="h-2 w-full bg-slate-300"></div>
                                                                    <div class="h-2 w-full bg-slate-300"></div>
                                                                    <div class="h-2 w-2/3 bg-slate-300"></div>
                                                                </div>
                                                            </div>

                                                            <div class="space-y-2">
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-2" x-show="block.layout === 'stacked'" x-cloak>
                                                        <div class="flex gap-3">
                                                            <div class="h-10 w-16 bg-slate-400"></div>
                                                            <div class="flex-1 space-y-2">
                                                                <div class="h-2 w-3/4 bg-slate-300"></div>
                                                                <div class="h-2 w-1/2 bg-slate-200"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex gap-3">
                                                            <div class="h-10 w-16 bg-slate-400"></div>
                                                            <div class="flex-1 space-y-2">
                                                                <div class="h-2 w-3/4 bg-slate-300"></div>
                                                                <div class="h-2 w-1/2 bg-slate-200"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex gap-3">
                                                            <div class="h-10 w-16 bg-slate-400"></div>
                                                            <div class="flex-1 space-y-2">
                                                                <div class="h-2 w-3/4 bg-slate-300"></div>
                                                                <div class="h-2 w-1/2 bg-slate-200"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-2" x-show="block.layout === 'featured-list'" x-cloak>
                                                        <div class="h-16 w-full bg-slate-400"></div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="h-8 bg-slate-300"></div>
                                                            <div class="h-8 bg-slate-300"></div>
                                                            <div class="h-8 bg-slate-300"></div>
                                                            <div class="h-8 bg-slate-300"></div>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-2" x-show="block.layout === 'hero-list'" x-cloak>
                                                        <div class="flex gap-3">
                                                            <div class="h-16 w-1/2 bg-slate-400"></div>
                                                            <div class="flex-1 space-y-2">
                                                                <div class="h-2 w-full bg-slate-300"></div>
                                                                <div class="h-2 w-2/4 bg-slate-200"></div>
                                                                <div class="h-2 w-2/4 bg-slate-200"></div>
                                                                <div class="h-2 w-1/3 bg-slate-200"></div>
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div class="space-y-2">
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="space-y-2">
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-4" x-show="block.layout === 'half-width'" x-cloak>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div class="space-y-2">
                                                                <div class="h-12 w-full bg-slate-400"></div>
                                                                <div class="space-y-1.5">
                                                                    <div class="h-2 w-full bg-slate-300"></div>
                                                                    <div class="h-2 w-2/3 bg-slate-300"></div>
                                                                </div>

                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <div class="h-6 w-10 bg-slate-400 flex-shrink-0"></div>
                                                                    <div class="flex-1 space-y-1.5">
                                                                        <div class="h-2 w-full bg-slate-300"></div>
                                                                        <div class="h-2 w-3/4 bg-slate-200"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="space-y-2">
                                                                <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">50% WIDTH GRID</div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <p x-text="block.name" class="p-2"></p>
                                            </button>
                                        </template>
                                    </div>
                                    <div class="border-t border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900" x-show="showBlockSettingsModal" x-cloak>
                                        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
                                            <div>
                                                <h3 class="text-base font-semibold">Block Settings</h3>
                                                <p class="text-xs text-white/70" x-text="findActiveBlock()?.name ?? ''"></p>
                                            </div>
                                            <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold hover:bg-sky-500" @click="showBlockSettingsModal = false">
                                                Close
                                            </button>
                                        </div>
                                        <div class="flex bg-sky-600 text-xs font-semibold text-white">
                                            <button type="button" class="relative px-5 py-3" :class="blockTab === 'general' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'general'">
                                                General
                                                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'general'"></span>
                                            </button>
                                            <button type="button" class="relative px-5 py-3" :class="blockTab === 'styling' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'styling'">
                                                Styling Settings
                                                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'styling'"></span>
                                            </button>
                                            <button type="button" class="relative px-5 py-3" :class="blockTab === 'advanced' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'advanced'">
                                                Advanced Settings
                                                <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'advanced'"></span>
                                            </button>
                                        </div>
                                        <div class="max-h-[60vh] overflow-y-auto bg-slate-50 p-6 dark:bg-slate-800">
                                            <div class="space-y-5" x-show="blockTab === 'general'" x-cloak>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Custom Title (optional)</label>
                                                    <input type="text"
                                                           class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                           :value="findActiveBlock()?.settings?.title ?? ''"
                                                           @input="updateActiveBlockField('title', $event.target.value)"
                                                           placeholder="Block Title">
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Icon (optional)</label>
                                                    <div class="flex items-center gap-3 max-w-md">
                                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-indigo-500 text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10">
                                                            <i class="fa-solid fa-star"></i>
                                                        </span>
                                                        <input type="text"
                                                               class="flex-1 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                               :value="findActiveBlock()?.settings?.icon ?? ''"
                                                               @input="updateActiveBlockField('icon', $event.target.value)"
                                                               placeholder="fa-solid fa-star">
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Title URL (optional)</label>
                                                    <input type="url"
                                                           class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                           :value="findActiveBlock()?.settings?.url ?? ''"
                                                           @input="updateActiveBlockField('url', $event.target.value)"
                                                           placeholder="https://">
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Categories</label>
                                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                                        @foreach ($categories as $category)
                                                            <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                                                <input type="checkbox"
                                                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                                                       :checked="(findActiveBlock()?.settings?.categories ?? []).includes({{ $category['id'] }})"
                                                                       @change="toggleActiveBlockCategory({{ $category['id'] }})">
                                                                <span>{{ $category['name'] }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Tags</label>
                                                    <div>
                                                        <input type="text"
                                                               class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                               :value="findActiveBlock()?.settings?.tags ?? ''"
                                                               @input="updateActiveBlockField('tags', $event.target.value)"
                                                               placeholder="Enter tag names separated by commas.">
                                                        <p class="mt-2 text-xs text-slate-500">Enter a tag name, or names separated by comma.</p>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Trending Posts</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.trending ?? false"
                                                                   @change="updateActiveBlockField('trending', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                        <span class="text-xs text-slate-500">Only show posts marked as trending</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="space-y-5" x-show="blockTab === 'styling'" x-cloak>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Show the content only?</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.contentOnly ?? false"
                                                                   @change="updateActiveBlockField('contentOnly', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                        <span class="text-xs text-slate-500">Without background, padding nor borders.</span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Dark Mode</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.darkMode ?? false"
                                                                   @change="updateActiveBlockField('darkMode', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Primary Color</label>
                                                    <input type="color"
                                                           class="h-10 w-14 rounded border border-slate-300 bg-white"
                                                           :value="findActiveBlock()?.settings?.primaryColor ?? ''"
                                                           @input="updateActiveBlockField('primaryColor', $event.target.value)">
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Background Color</label>
                                                    <input type="color"
                                                           class="h-10 w-14 rounded border border-slate-300 bg-white"
                                                           :value="findActiveBlock()?.settings?.backgroundColor ?? ''"
                                                           @input="updateActiveBlockField('backgroundColor', $event.target.value)">
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Secondary Color</label>
                                                    <input type="color"
                                                           class="h-10 w-14 rounded border border-slate-300 bg-white"
                                                           :value="findActiveBlock()?.settings?.secondaryColor ?? ''"
                                                           @input="updateActiveBlockField('secondaryColor', $event.target.value)">
                                                </div>
                                            </div>

                                            <div class="space-y-5" x-show="blockTab === 'advanced'" x-cloak>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Ajax Filters</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.ajaxFilters ?? false"
                                                                   @change="updateActiveBlockField('ajaxFilters', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                        <span class="text-xs text-slate-500">Will not appear if the numeric pagination is active.</span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">More Button</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.moreButton ?? false"
                                                                   @change="updateActiveBlockField('moreButton', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Title Length</label>
                                                    <input type="number"
                                                           class="w-28 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                           :value="findActiveBlock()?.settings?.titleLength ?? ''"
                                                           @input="updateActiveBlockField('titleLength', $event.target.value)">
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Show excerpt</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.showExcerpt ?? true"
                                                                   @change="updateActiveBlockField('showExcerpt', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Excerpt Length</label>
                                                    <input type="number"
                                                           class="w-28 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                           :value="findActiveBlock()?.settings?.excerptLength ?? ''"
                                                           @input="updateActiveBlockField('excerptLength', $event.target.value)">
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Read More Button</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.readMoreButton ?? false"
                                                                   @change="updateActiveBlockField('readMoreButton', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Hide first thumbnail</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.hideFirstThumbnail ?? false"
                                                                   @change="updateActiveBlockField('hideFirstThumbnail', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Hide small thumbnails</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.hideSmallThumbnails ?? false"
                                                                   @change="updateActiveBlockField('hideSmallThumbnails', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Post meta</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.postMeta ?? true"
                                                                   @change="updateActiveBlockField('postMeta', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                    <label class="text-sm text-slate-600 dark:text-slate-300">Media Icon</label>
                                                    <label class="inline-flex items-center gap-3">
                                                        <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                            <input type="checkbox" class="peer sr-only"
                                                                   :checked="findActiveBlock()?.settings?.mediaIcon ?? false"
                                                                   @change="updateActiveBlockField('mediaIcon', $event.target.checked)">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="showBlockSettingsModal && !showBlockModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4">
                                <div class="w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900">
                                    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
                                        <div>
                                            <h3 class="text-base font-semibold">Edit Block</h3>
                                            <p class="text-xs text-white/70" x-text="findActiveBlock()?.name ?? ''"></p>
                                        </div>
                                        <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold cursor-pointer hover:bg-sky-500" @click="showBlockSettingsModal = false">
                                            Done
                                        </button>
                                    </div>
                                    <div class="flex bg-sky-600 text-xs font-semibold text-white">
                                        <button type="button" class="relative px-5 py-3 cursor-pointer" :class="blockTab === 'general' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'general'">
                                            General
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'general'"></span>
                                        </button>
                                        <button type="button" class="relative px-5 py-3 cursor-pointer" :class="blockTab === 'styling' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'styling'">
                                            Styling Settings
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'styling'"></span>
                                        </button>
                                        <button type="button" class="relative px-5 py-3 cursor-pointer" :class="blockTab === 'advanced' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="blockTab = 'advanced'">
                                            Advanced Settings
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="blockTab === 'advanced'"></span>
                                        </button>
                                    </div>
                                    <div class="max-h-[70vh] overflow-y-auto bg-slate-50 p-6 dark:bg-slate-800">
                                        <div class="space-y-5" x-show="blockTab === 'general'" x-cloak>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Custom Title (optional)</label>
                                                <input type="text"
                                                       class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                       :value="findActiveBlock()?.settings?.title ?? ''"
                                                       @input="updateActiveBlockField('title', $event.target.value)"
                                                       placeholder="Block Title">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Icon (optional)</label>
                                                <div class="flex items-center gap-3 max-w-md">
                                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-indigo-500 text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10">
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                    <input type="text"
                                                           class="flex-1 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                           :value="findActiveBlock()?.settings?.icon ?? ''"
                                                           @input="updateActiveBlockField('icon', $event.target.value)"
                                                           placeholder="fa-solid fa-star">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Title URL (optional)</label>
                                                <input type="url"
                                                       class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                       :value="findActiveBlock()?.settings?.url ?? ''"
                                                       @input="updateActiveBlockField('url', $event.target.value)"
                                                       placeholder="https://">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Categories</label>
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                                    @foreach ($categories as $category)
                                                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                                            <input type="checkbox"
                                                                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                                                   :checked="(findActiveBlock()?.settings?.categories ?? []).includes({{ $category['id'] }})"
                                                                   @change="toggleActiveBlockCategory({{ $category['id'] }})">
                                                            <span>{{ $category['name'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Tags</label>
                                                <div>
                                                    <input type="text"
                                                           class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                           :value="findActiveBlock()?.settings?.tags ?? ''"
                                                           @input="updateActiveBlockField('tags', $event.target.value)"
                                                           placeholder="Enter tag names separated by commas.">
                                                    <p class="mt-2 text-xs text-slate-500">Enter a tag name, or names separated by comma.</p>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Trending Posts</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.trending ?? false"
                                                               @change="updateActiveBlockField('trending', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                    <span class="text-xs text-slate-500">Only show posts marked as trending</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="space-y-5" x-show="blockTab === 'styling'" x-cloak>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Show the content only?</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.contentOnly ?? false"
                                                               @change="updateActiveBlockField('contentOnly', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                    <span class="text-xs text-slate-500">Without background, padding nor borders.</span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Dark Mode</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.darkMode ?? false"
                                                               @change="updateActiveBlockField('darkMode', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Primary Color</label>
                                                <input type="color"
                                                       class="h-10 w-14 rounded border border-slate-300 bg-white"
                                                       :value="findActiveBlock()?.settings?.primaryColor ?? ''"
                                                       @input="updateActiveBlockField('primaryColor', $event.target.value)">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Background Color</label>
                                                <input type="color"
                                                       class="h-10 w-14 rounded border border-slate-300 bg-white"
                                                       :value="findActiveBlock()?.settings?.backgroundColor ?? ''"
                                                       @input="updateActiveBlockField('backgroundColor', $event.target.value)">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Secondary Color</label>
                                                <input type="color"
                                                       class="h-10 w-14 rounded border border-slate-300 bg-white"
                                                       :value="findActiveBlock()?.settings?.secondaryColor ?? ''"
                                                       @input="updateActiveBlockField('secondaryColor', $event.target.value)">
                                            </div>
                                        </div>

                                        <div class="space-y-5" x-show="blockTab === 'advanced'" x-cloak>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Ajax Filters</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.ajaxFilters ?? false"
                                                               @change="updateActiveBlockField('ajaxFilters', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                    <span class="text-xs text-slate-500">Will not appear if the numeric pagination is active.</span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">More Button</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.moreButton ?? false"
                                                               @change="updateActiveBlockField('moreButton', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                    <span class="text-xs text-slate-500">Will not appear if the Block URL is empty.</span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Posts Title Length</label>
                                                <input type="number" min="0"
                                                       class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                       :value="findActiveBlock()?.settings?.titleLength ?? ''"
                                                       @input="updateActiveBlockField('titleLength', $event.target.value)">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Posts Excerpt</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.showExcerpt ?? true"
                                                               @change="updateActiveBlockField('showExcerpt', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Posts Excerpt Length</label>
                                                <input type="number" min="0"
                                                       class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                       :value="findActiveBlock()?.settings?.excerptLength ?? ''"
                                                       @input="updateActiveBlockField('excerptLength', $event.target.value)">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Read More Button</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.readMoreButton ?? false"
                                                               @change="updateActiveBlockField('readMoreButton', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Hide thumbnail for the First post</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.hideFirstThumbnail ?? false"
                                                               @change="updateActiveBlockField('hideFirstThumbnail', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Hide small thumbnails</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.hideSmallThumbnails ?? false"
                                                               @change="updateActiveBlockField('hideSmallThumbnails', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Post Meta</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.postMeta ?? true"
                                                               @change="updateActiveBlockField('postMeta', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Media Icon</label>
                                                <label class="inline-flex items-center gap-3">
                                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-200 transition peer-checked:bg-sky-600">
                                                        <input type="checkbox" class="peer sr-only"
                                                               :checked="findActiveBlock()?.settings?.mediaIcon ?? false"
                                                               @change="updateActiveBlockField('mediaIcon', $event.target.checked)">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-start rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300 mt-2">Exclude Posts</label>
                                                <div>
                                                    <input type="text"
                                                           class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                           :value="findActiveBlock()?.settings?.exclude ?? ''"
                                                           @input="updateActiveBlockField('exclude', $event.target.value)"
                                                           placeholder="Enter a post ID, or IDs separated by comma.">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Sort by</label>
                                                <select class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                        :value="findActiveBlock()?.settings?.sort ?? 'recent'"
                                                        @change="updateActiveBlockField('sort', $event.target.value)">
                                                    <option value="recent">Recent Posts</option>
                                                    <option value="popular">Popular Posts</option>
                                                </select>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Order</label>
                                                <select class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                        :value="findActiveBlock()?.settings?.order ?? 'desc'"
                                                        @change="updateActiveBlockField('order', $event.target.value)">
                                                    <option value="desc">Descending</option>
                                                    <option value="asc">Ascending</option>
                                                </select>
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Number of posts to show</label>
                                                <input type="number" min="1"
                                                       class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                       :value="findActiveBlock()?.settings?.count ?? 5"
                                                       @input="updateActiveBlockField('count', Number($event.target.value))">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Offset - number of posts to pass over</label>
                                                <input type="number" min="0"
                                                       class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                       :value="findActiveBlock()?.settings?.offset ?? 0"
                                                       @input="updateActiveBlockField('offset', Number($event.target.value))">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Published in the last (days)</label>
                                                <input type="number" min="0"
                                                       class="w-full max-w-xs rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                       :value="findActiveBlock()?.settings?.days ?? ''"
                                                       @input="updateActiveBlockField('days', $event.target.value)">
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[220px_1fr] items-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <label class="text-sm text-slate-600 dark:text-slate-300">Pagination</label>
                                                <select class="w-full max-w-md rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                                        :value="findActiveBlock()?.settings?.pagination ?? 'disable'"
                                                        @change="updateActiveBlockField('pagination', $event.target.value)">
                                                    <option value="disable">Disable</option>
                                                    <option value="numeric">Numeric</option>
                                                    <option value="ajax-show-more">AJAX - Show More</option>
                                                    <option value="ajax-load-more">AJAX - Load More</option>
                                                    <option value="ajax-next-prev">AJAX - Next/Previous Buttons</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('content') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- SEO meta inputs (তুমি আগে যেটা বানিয়েছো সেটাই reuse) --}}
                        <div class="pt-4">
                            @include('admin.meta.seo-meta-box', [
                                'baseUrl'     => url('/'),
                                'previewType' => 'page',
                            ])
                        </div>

                        {{-- Yoast-like analysis (তোমার yoast-box blade reuse) --}}
                        @include('admin.meta.yoast-box', ['seo' => $seo])
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="space-y-4">
            {{-- Publish --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Publish</h3>
                </div>

                <div class="px-5 py-4 space-y-2">
                    <div class="flex gap-2">
                        <button type="button"
                                wire:click="save('stay')"
                                wire:loading.attr="disabled"
                                wire:target="save"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg cursor-pointer bg-sky-600 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-500">
                            <span wire:loading.remove wire:target="save"><i class="fa-solid fa-floppy-disk text-xs"></i> Save</span>
                            <span wire:loading.inline wire:target="save" class="inline-flex items-center gap-2">
                                <svg class="h-3 w-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>

                        <button type="button"
                                wire:click="save('exit')"
                                wire:loading.attr="disabled"
                                wire:target="save"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            Save & Exit
                        </button>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Status</h3>
                </div>
                <div class="px-5 py-4">
                    <select wire:model="status"
                            class="block w-full rounded-lg border px-3 py-2 text-sm
                                   border-slate-300 bg-white text-slate-800 focus:border-sky-500 focus:ring-sky-500
                                   dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Template --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Template</h3>
                </div>
                <div class="px-5 py-4">
                    <input type="text"
                           wire:model.defer="template"
                           class="block w-full rounded-lg border px-3 py-2 text-sm
                                  border-slate-300 bg-white text-slate-800 focus:border-sky-500 focus:ring-sky-500
                                  dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100"
                           placeholder="default, right-sidebar...">
                    @error('template') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Image --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 p-3">
                @include('mediamanager::includes.media-input', [
                    'name'  => 'image',
                    'id'    => 'page_image',
                    'label' => 'Image',
                    'value' => $image ?? '',
                ])
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>

        function initPageCkeditor() {
            const id = 'page_content';
            const textarea = document.getElementById(id);
            if (!textarea || typeof CKEDITOR === 'undefined') return;

            window.setupCkeditorBase('{{ setting("hippo_api_key") }}');
            // মোড ডিটেক্ট করা
            const isDarkMode = document.documentElement.classList.contains('dark');
            const bgColor = isDarkMode ? '#0f172a' : '#ffffff';
            const textColor = isDarkMode ? '#f1f5f9' : '#1e293b';

            if (CKEDITOR.instances[id]) {
                CKEDITOR.instances[id].destroy(true);
            }


            const editor = CKEDITOR.replace(id, {
                contentsCss: [
                    `body { background-color: ${bgColor}; color: ${textColor}; font-family: sans-serif; padding: 20px; line-height: 1.6; }`,
                    'a { color: #38bdf8; }'
                ],
                height: 360,
                removePlugins: 'cloudservices,uploadimage,uploadfile',
                extraPlugins: 'wordcount,notification,ImgHippoUploader,imagemenu',
                wordcount: { showCharCount: true, showWordCount: true },
                allowedContent: true,
                extraAllowedContent: '*(*){*}',
                toolbar: [
                    {items: ['Undo','Redo']},
                    { name: 'styles', items: ['Format','Font','FontSize'] },
                    { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike'] },
                    { name: 'paragraph', items: ['NumberedList','BulletedList','Outdent','Indent','Blockquote'] },
                    { name: 'links', items: ['Link','Unlink'] },
                    { name: 'insert', items: ['Image','Table','ImgHippoUpload','ImageMenu','HorizontalRule','SpecialChar'] },
                    { name: 'tools', items: ['Maximize','Source'] }
                ],
            });

            // ✅ CKEditor → Livewire sync
            editor.on('change', function () {
            @this.set('content', editor.getData());
            });
        }

        document.addEventListener('livewire:init', () => {
            initPageCkeditor();
        });

        // যদি পরে কখনো re-render এ editor হারায়, তখন component থেকে dispatch করলে reinit হবে:
        // $this->dispatch('reinit-page-ckeditor');
        document.addEventListener('reinit-page-ckeditor', () => initPageCkeditor());
    </script>
@endpush
