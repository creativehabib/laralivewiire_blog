<div class="">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-4">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-300">Blog</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Tags</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Create a new tag</span>
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
        {{-- Main form card --}}
        <div class="lg:col-span-2">
            <div
                class="rounded-xl border border-slate-200 bg-white shadow-sm
                       dark:border-slate-700 dark:bg-slate-800">
                <form wire:submit.prevent="save" id="tag-create-form">
                    <div class="px-6 py-5 space-y-5">

                        {{-- Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Name <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model.defer="name"
                                wire:keyup.debounce.300ms="syncSlugFromName($event.target.value)"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="Name">
                            @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Permalink / slug --}}
                        <x-admin.permalink-field
                            label="Permalink"
                            :base-url="$baseUrl"
                            preview-type="tag"
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
                                Description
                            </label>
                            <textarea
                                wire:model.defer="description"
                                rows="4"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="Short description"></textarea>
                            @error('description')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- SEO section --}}
                    <div class="px-6 py-4">
                        @include('admin.meta.seo-meta-box')
                    </div>
                </form>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-4">
            {{-- Publish card --}}
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

            {{-- Status card --}}
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
        </div>
    </div>
</div>
