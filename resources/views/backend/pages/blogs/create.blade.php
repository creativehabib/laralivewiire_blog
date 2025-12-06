<x-layouts.app :title="__('Create a new post')">
    <div class="mb-4">
        <nav class="text-xs text-slate-500 mb-1 space-x-1 rtl:space-x-reverse">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-700">Dashboard</a>
            <span>/</span>
            <a href="{{ route('blogs.posts.index') }}" class="hover:text-slate-700">Blog</a>
            <span>/</span>
            <span class="text-slate-900 font-medium">Create a new post</span>
        </nav>
    </div>
    <form class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" x-data="{
        title: '',
        slug: '',
        makeSlug() {
            this.slug = this.title
                .toLowerCase()
                .replace(/[^a-z0-9\u0980-\u09FF]+/g, '-')
                .replace(/(^-|-$)+/g, '');
        },
        featured: false,
        statusOpen: false,
        status: 'Draft',
        tagInput: '',
        tags: [],
        addTag() {
            if (!this.tagInput.trim()) return;
            if (!this.tags.includes(this.tagInput.trim())) {
                this.tags.push(this.tagInput.trim());
            }
            this.tagInput = '';
        },
        removeTag(i) { this.tags.splice(i,1); }
     }"
    >
        <!-- LEFT COLUMN -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Basic info card -->
            <section class="bg-white rounded-lg shadow-sm border border-slate-100 p-5 md:p-6 space-y-5">
                <!-- Name -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-slate-800">
                            Name <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-slate-400">0/250</span>
                    </div>
                    <input
                        type="text"
                        x-model="name"
                        @input="makeSlug()"
                        placeholder="Post name"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50/70 px-3 py-2.5 text-sm text-slate-900
                               focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary">
                </div>

                <!-- Permalink -->
                <div>
                    <label class="block text-sm font-medium text-slate-800 mb-1.5">
                        Permalink <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center rounded-lg border border-slate-200 bg-slate-50/70 focus-within:ring-2 focus-within:ring-primary/60 focus-within:border-primary overflow-hidden">
                            <span class="px-3 text-xs md:text-sm text-slate-500 bg-slate-100 border-r border-slate-200">
                                {{ url('/') }}
                            </span>
                            <input
                                type="text"
                                x-model="slug"
                                class="flex-1 border-0 bg-transparent px-2 py-2 text-sm focus:outline-none focus:ring-0"
                                placeholder="post-slug" />
                        </div>
                        <p class="text-xs text-slate-500">
                            Preview:
                            <span class="text-primary font-medium">
                                {{ url('/') }}/<span x-text="slug || 'your-slug-here'"></span>
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Short description -->
                <div>
                    <label class="block text-sm font-medium text-slate-800 mb-1.5">
                        Description
                    </label>
                    <textarea
                        rows="3"
                        placeholder="Short description"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50/70 px-3 py-2.5 text-sm text-slate-900
                               focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary resize-y"></textarea>
                </div>

                <!-- Featured toggle -->
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="button"
                        @click="featured = !featured"
                        :class="featured ? 'bg-primary' : 'bg-slate-200'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                        <span
                            :class="featured ? 'translate-x-5 bg-white' : 'translate-x-1 bg-white'"
                            class="inline-block h-4 w-4 transform rounded-full transition-transform shadow-sm">
                        </span>
                    </button>
                    <div>
                        <p class="text-sm font-medium text-slate-800">Is featured?</p>
                        <span class="text-xs text-slate-500">
                            Show this post on featured section of homepage.
                        </span>
                    </div>
                </div>

                <div class="">
                        Content
                </div>

                <div class="flex items-center gap-2 text-xs">
                    <button type="button" class="cursor-pointer px-2.5 py-1 rounded-md border border-slate-200 bg-white text-slate-700 hover:bg-slate-100">
                        Show/Hide Editor
                    </button>
                    <button type="button" onclick="openCkeditorImagePicker('post_content')" class="cursor-pointer px-2.5 py-1 rounded-md border border-slate-200 bg-white text-slate-700 hover:bg-slate-100">
                        Add media
                    </button>
                    <button type="button" class="cursor-pointer px-2.5 py-1 rounded-md border border-slate-200 bg-white text-slate-700 hover:bg-slate-100">
                        UI Blocks
                    </button>
                </div>
                <textarea id="post_content" name="content">{{ old('content', $post->content ?? '') }}</textarea>
            </section>
            <!-- SEO card -->
            @include('admin.meta.seo-meta-box')
        </div>

        <!-- RIGHT COLUMN / SIDEBAR -->
        <aside class="space-y-5">

            <!-- Publish card -->
            <section class="bg-white rounded-lg shadow-sm border border-slate-100 p-4 md:p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800">Publish</h2>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-medium text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Draft
                    </span>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-medium text-slate-600">Status <span class="text-rose-500">*</span></label>

                    <div class="relative" x-data="{open:false}">
                        <button
                            type="button"
                            @click="open = !open"
                            class="flex w-full items-center justify-between rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 hover:bg-slate-50">
                            <span x-text="status"></span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition
                             class="absolute z-20 mt-1 w-full overflow-hidden rounded-lg border border-slate-200 bg-white text-xs shadow-lg">
                            <button type="button"
                                    @click="status = 'Published'; open = false"
                                    class="block w-full px-3 py-2 text-left hover:bg-slate-50">
                                Published
                            </button>
                            <button type="button"
                                    @click="status = 'Draft'; open = false"
                                    class="block w-full px-3 py-2 text-left hover:bg-slate-50">
                                Draft
                            </button>
                            <button type="button"
                                    @click="status = 'Pending review'; open = false"
                                    class="block w-full px-3 py-2 text-left hover:bg-slate-50">
                                Pending review
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 ">
                    <button type="button"
                            class="flex-1 min-w-[40%] inline-flex justify-center items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                        <i class="fa-regular fa-eye text-slate-400"></i>
                        <span>Preview</span>
                    </button>
                    <button type="submit"
                            class="flex-1 min-w-[40%] inline-flex justify-center items-center gap-2 rounded-lg bg-primary text-white px-3 py-2 text-xs font-semibold hover:bg-primary-dark">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Publish</span>
                    </button>
                </div>
            </section>

            <!-- Categories -->
            <section class="bg-white rounded-lg shadow-sm border border-slate-100 p-4 md:p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800">Categories</h2>
                    <button type="button" class="text-[11px] text-primary hover:underline">
                        + New
                    </button>
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text"
                           placeholder="Search..."
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/70 pl-8 pr-3 py-1.5 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary">
                </div>

                <div class="max-h-56 overflow-y-auto pt-2 pr-1 space-y-1 text-xs">
                    <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                        <span>Uncategorized</span>
                    </label>

                    <div class="mt-1">
                        <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer font-medium text-slate-800">
                            <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                            <span>Travel</span>
                        </label>
                        <div class="ml-6 space-y-1">
                            <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                                <span>Guides</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                                <span>Destination</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                                <span>Hotels</span>
                            </label>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                        <span>Food</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                        <span>Review</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                        <span>Healthy</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-md px-1 py-1 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary">
                        <span>Lifestyle</span>
                    </label>
                </div>
            </section>

            <!-- Image -->
            <section class="bg-white rounded-lg shadow-sm border border-slate-100 p-4 md:p-5 space-y-3">
                @include('mediamanager::includes.media-input', [
                      'name'  => 'thumbnail_path',
                      'id'    => 'thumbnail_path',
                      'label' => 'Featured Image',
                      'value' => $thumbnail_path ?? '',
                  ])
            </section>

            <!-- Tags -->
            <section class="bg-white rounded-lg shadow-sm border border-slate-100 p-4 md:p-5 space-y-3">
                <h2 class="text-sm font-semibold text-slate-800">Tags</h2>

                <div class="flex flex-wrap gap-2" x-show="tags.length">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-[11px] text-slate-700">
                            <span x-text="tag"></span>
                            <button type="button" @click="removeTag(index)">
                                <i class="fa-solid fa-xmark text-[10px] text-slate-500"></i>
                            </button>
                        </span>
                    </template>
                </div>

                <div class="flex items-center gap-2 mt-1">
                    <input
                        type="text"
                        x-model="tagInput"
                        @keydown.enter.prevent="addTag()"
                        placeholder="Write some tags"
                        class="flex-1 rounded-lg border border-slate-200 bg-slate-50/70 px-3 py-1.5 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary">
                    <button type="button"
                            @click="addTag()"
                            class="rounded-lg bg-slate-800 text-white px-3 py-1.5 text-[11px] font-medium hover:bg-slate-900">
                        Add
                    </button>
                </div>
                <p class="text-[11px] text-slate-400">
                    Press Enter to add tag.
                </p>
            </section>

            <!-- Time to read -->
            <section class="bg-white rounded-lg shadow-sm border border-slate-100 p-4 md:p-5 space-y-3">
                <h2 class="text-sm font-semibold text-slate-800">Time to read (minute)</h2>
                <input type="number" min="1"
                       class="w-32 rounded-lg w-full border border-slate-200 bg-slate-50/70 px-3 py-1.5 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary">
            </section>

            <section class="bg-white rounded-lg shadow-sw border border-slate-100 p-4 md:p-5 space-y-3">
                <flux:skeleton.group animate="shimmer" class="flex items-center gap-4">
                    <flux:skeleton class="size-10 rounded-full" />

                    <div class="flex-1">
                        <flux:skeleton.line />
                        <flux:skeleton.line class="w-1/2" />
                    </div>
                </flux:skeleton.group>
            </section>

            <section class="bg-white rounded-lg shadow-sw border border-slate-100 p-4 md:p-5 space-y-3">
                <flux:modal.trigger name="delete-profile">
                    <flux:button variant="danger" class="cursor-pointer">Delete</flux:button>
                </flux:modal.trigger>

                <flux:modal name="delete-profile" class="min-w-[22rem]">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Delete project?</flux:heading>

                            <flux:text class="mt-2">
                                You're about to delete this project.<br>
                                This action cannot be reversed.
                            </flux:text>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />

                            <flux:modal.close>
                                <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                            </flux:modal.close>

                            <flux:button type="submit" variant="danger" class="cursor-pointer">Delete project</flux:button>
                        </div>
                    </div>
                </flux:modal>
            </section>

            <!-- Layout + comments -->
            <section class="bg-white rounded-lg shadow-sm border border-slate-100 p-4 md:p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">
                        Layout
                    </label>
                    <select class="w-full rounded-lg border border-slate-200 bg-slate-50/70 px-3 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary">
                        <option>Inherit</option>
                        <option>Full width</option>
                        <option>With sidebar</option>
                    </select>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary" checked>
                    <span>Allow comments</span>
                </label>
            </section>
        </aside>
    </form>


    <!-- ছোট হেল্পার ক্লাস (Tailwind এর @layer utilities এ রাখতে পারো) -->
    <style>
        .toolbar-btn {
            @apply inline-flex items-center justify-center h-7 w-7 rounded-md border border-transparent hover:border-slate-200 hover:bg-slate-50;
        }
    </style>

</x-layouts.app>
