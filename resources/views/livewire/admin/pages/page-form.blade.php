@php
    $seo = $this->seoAnalysis;
@endphp

<div class="space-y-4" x-data="{
    name: @entangle('name').live,
    description: @entangle('description').live,
    builderEnabled: false,
    sections: [],
    nextSectionId: 1,
    activeSectionId: null,
    showSectionModal: false,
    showBlockModal: false,
    sectionTab: 'general',
    selectedSidebar: 'none',
    blocks: [
        { id: 1, name: 'List + Sidebar', layout: 'list-sidebar' },
        { id: 2, name: 'Stacked Cards', layout: 'stacked' },
        { id: 3, name: 'Featured + List', layout: 'featured-list' },
        { id: 4, name: 'Hero + List', layout: 'hero-list' },
        { id: 5, name: 'Half Width', layout: 'half-width' }
    ],
    addSection() {
        this.sections.push({ id: this.nextSectionId++, blocks: [], sidebar: 'none' });
    },
    removeSection(sectionId) {
        this.sections = this.sections.filter((section) => section.id !== sectionId);
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
        this.showBlockModal = true;
    },
    addBlockToSection(block) {
        const section = this.sections.find((item) => item.id === this.activeSectionId);

        if (!section) {
            return;
        }

        section.blocks.push({
            id: Date.now() + Math.random(),
            name: block.name,
            layout: block.layout
        });
        this.showBlockModal = false;
    },
    removeBlockFromSection(sectionId, blockId) {
        const section = this.sections.find((item) => item.id === sectionId);

        if (!section) {
            return;
        }

        section.blocks = section.blocks.filter((block) => block.id !== blockId);
    },
    updateSidebarSelection(value) {
        this.selectedSidebar = value;
        const section = this.sections.find((item) => item.id === this.activeSectionId);

        if (!section) {
            return;
        }

        section.sidebar = value;
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
                                        class="inline-flex items-center gap-2 rounded border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-sky-400 hover:text-sky-600 dark:border-slate-600 dark:text-slate-200 dark:hover:text-sky-300"
                                        @click="builderEnabled = !builderEnabled">
                                    <i class="fa-solid fa-layer-group text-[11px]"></i>
                                    <span x-text="builderEnabled ? 'Disable the Builder' : 'Enable the Builder'"></span>
                                </button>
                                <button type="button"
                                        onclick="openCkeditorImagePicker('page_content')"
                                        class="inline-flex items-center gap-1 rounded border border-slate-300 px-2 py-1 text-xs text-slate-600 dark:border-slate-600 dark:text-slate-200"
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
                                        <button type="button" class="text-white/80 hover:text-white">
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
                                                    <button type="button" class="h-7 w-7 rounded bg-sky-700 hover:bg-sky-800" @click="removeSection(section.id)">
                                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                                    </button>
                                                    <button type="button" class="h-7 w-7 rounded bg-sky-700 hover:bg-sky-800" @click="openSectionModal(section.id)">
                                                        <i class="fa-solid fa-pen text-[11px]"></i>
                                                    </button>
                                                    <button type="button" class="h-7 w-7 rounded bg-sky-700 hover:bg-sky-800">
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
                                                            <button type="button" class="rounded bg-sky-600 px-5 py-2 text-xs font-semibold text-white hover:bg-sky-700" @click="openBlockModal(section.id)">
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
                                                                        <button type="button" class="h-7 w-7 rounded bg-slate-700 hover:bg-slate-600" @click="removeBlockFromSection(section.id, block.id)">
                                                                            <i class="fa-solid fa-trash text-[11px]"></i>
                                                                        </button>
                                                                        <button type="button" class="h-7 w-7 rounded bg-slate-700 hover:bg-slate-600">
                                                                            <i class="fa-solid fa-pen text-[11px]"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="w-full rounded border border-dashed border-slate-300 px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:border-slate-600 dark:text-slate-400" @click="openBlockModal(section.id)">
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
                                                        <button type="button" class="flex w-full items-center justify-center gap-2 rounded border border-dashed border-slate-300 bg-white px-3 py-2 text-xs text-slate-500 hover:text-slate-700 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-400">
                                                            <i class="fa-solid fa-gear text-[11px]"></i>
                                                            Manage Widgets
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <button type="button"
                                            class="flex w-full items-center justify-center gap-2 rounded border border-dashed border-slate-300 px-3 py-6 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:border-slate-600 dark:text-slate-400"
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
                                        <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold hover:bg-sky-500" @click="showSectionModal = false">
                                            Done
                                        </button>
                                    </div>
                                    <div class="flex bg-sky-600 text-xs font-semibold text-white">
                                        <button type="button" class="relative px-5 py-3" :class="sectionTab === 'general' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'general'">
                                            General
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'general'"></span>
                                        </button>
                                        <button type="button" class="relative px-5 py-3" :class="sectionTab === 'background' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'background'">
                                            Background
                                            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 h-0 w-0 border-x-8 border-x-transparent border-t-8 border-t-sky-700" x-show="sectionTab === 'background'"></span>
                                        </button>
                                        <button type="button" class="relative px-5 py-3" :class="sectionTab === 'styling' ? 'bg-sky-700' : 'bg-sky-600 hover:bg-sky-500'" @click="sectionTab = 'styling'">
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
                                                <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                                        :class="selectedSidebar === 'none' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                                                        @click="updateSidebarSelection('none')">
                                                    <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700"></div>
                                                    Without Sidebar
                                                </button>
                                                <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                                        :class="selectedSidebar === 'right' ? 'border-sky-600 ring-2 ring-sky-200 dark:ring-sky-600/40' : ''"
                                                        @click="updateSidebarSelection('right')">
                                                    <div class="mb-2 h-16 rounded bg-slate-200 dark:bg-slate-700">
                                                        <div class="h-full w-4/5 rounded bg-slate-300 dark:bg-slate-600"></div>
                                                    </div>
                                                    Sidebar Right
                                                </button>
                                                <button type="button" class="rounded border border-slate-200 bg-slate-50 p-3 text-xs font-semibold text-slate-600 hover:border-sky-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
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
                                <div class="w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900">
                                    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-900 px-6 py-4 text-white dark:border-slate-700">
                                        <h3 class="text-base font-semibold">Add Block</h3>
                                        <button type="button" class="rounded bg-sky-600 px-4 py-2 text-xs font-semibold hover:bg-sky-500" @click="showBlockModal = false">
                                            Done
                                        </button>
                                    </div>
                                    <div class="grid gap-4 bg-slate-50 p-6 dark:bg-slate-800 sm:grid-cols-2">
                                        <template x-for="block in blocks" :key="block.id">
                                            <button type="button"
                                                    class="rounded border border-slate-200 bg-white p-4 text-left text-xs font-semibold text-slate-600 hover:border-sky-500 hover:text-sky-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                                    @click="addBlockToSection(block)">
                                                <div class="mb-3 rounded bg-slate-100 p-3 dark:bg-slate-800">
                                                    <div class="space-y-2" x-show="block.layout === 'list-sidebar'">
                                                        <div class="flex gap-3">
                                                            <div class="h-8 w-12 bg-slate-400"></div>
                                                            <div class="flex-1 space-y-2">
                                                                <div class="h-2 w-3/4 bg-slate-300"></div>
                                                                <div class="h-2 w-1/2 bg-slate-200"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex gap-3">
                                                            <div class="h-8 w-12 bg-slate-400"></div>
                                                            <div class="flex-1 space-y-2">
                                                                <div class="h-2 w-3/4 bg-slate-300"></div>
                                                                <div class="h-2 w-1/2 bg-slate-200"></div>
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
                                                                <div class="h-2 w-3/4 bg-slate-300"></div>
                                                                <div class="h-2 w-2/3 bg-slate-200"></div>
                                                                <div class="h-2 w-1/2 bg-slate-200"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-2" x-show="block.layout === 'half-width'" x-cloak>
                                                        <div class="flex items-center justify-between">
                                                            <div class="h-10 w-1/2 bg-slate-400"></div>
                                                            <div class="text-[10px] font-semibold text-slate-400">50% WIDTH</div>
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <div class="h-6 w-6 bg-slate-300"></div>
                                                            <div class="h-6 w-6 bg-slate-300"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span x-text="block.name"></span>
                                            </button>
                                        </template>
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
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-500">
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
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
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
