{{-- resources/views/livewire/admin/categories/category-table.blade.php --}}
<div class="space-y-4">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400 dark:text-slate-600">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-200">Blog</span>
        <span class="mx-1 text-slate-400 dark:text-slate-600">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Categories</span>
    </nav>

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="mb-2 rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs text-emerald-800 dark:border-emerald-500/50 dark:bg-emerald-900/30 dark:text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    {{-- Top toolbar --}}
    <div
        class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between
               rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-700 dark:bg-slate-900/70">

        <div class="flex items-center gap-2">
            {{-- Bulk actions --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-slate-500">
                    Bulk Actions
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>

                <div x-show="open" @click.outside="open = false"
                     class="absolute z-20 mt-1 w-44 rounded-md border border-slate-200 bg-white shadow dark:border-slate-700 dark:bg-slate-900">
                    <button type="button"
                            onclick="confirm('Delete selected categories?') || event.stopImmediatePropagation()"
                            wire:click="bulkDelete"
                            class="block w-full px-3 py-2 text-left text-xs text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-900/20">
                        Delete selected
                    </button>

                    <button type="button"
                            wire:click="bulkPublish"
                            class="block w-full px-3 py-2 text-left text-xs text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-900/20">
                        Mark as published
                    </button>

                    <button type="button"
                            wire:click="bulkDraft"
                            class="block w-full px-3 py-2 text-left text-xs text-amber-700 hover:bg-amber-50 dark:text-amber-300 dark:hover:bg-amber-900/20">
                        Mark as draft
                    </button>
                </div>
            </div>

            {{-- Filters button (future advanced filters) --}}
            <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-slate-500">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-300"><i class="fa-solid fa-sliders text-[10px]"></i></span>
                Filters
            </button>
        </div>

        <div class="flex flex-1 items-center justify-end gap-2">
            {{-- Search --}}
            <div class="w-full max-w-xs">
                <label class="relative block">
                    <span class="sr-only">Search</span>
                    <span class="absolute inset-y-0 left-2 flex items-center text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-7 pr-3 text-xs text-slate-800 placeholder-slate-400 shadow-inner focus:border-sky-300 focus:ring-sky-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-500"
                        placeholder="Search categories..."
                        type="text">
                </label>
            </div>

            {{-- Per page --}}
            <select wire:model.live="perPage"
                    class="rounded-lg border border-slate-200 bg-white px-2 py-2 text-xs text-slate-700 cursor-pointer shadow-sm focus:border-sky-300 focus:ring-sky-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:focus:border-sky-500 dark:focus:ring-slate-700">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>

            {{-- Create --}}
            <button
                onclick="window.location='{{ route('blogs.categories.create') }}'"
                type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-sky-500 cursor-pointer dark:bg-sky-500 dark:hover:bg-sky-400">
                <i class="fa-solid fa-plus text-xs"></i>
                Create
            </button>

            {{-- Reload --}}
            <button
                type="button"
                wire:click="refreshTable"
                wire:loading.attr="disabled"
                wire:target="refreshTable"
                class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 cursor-pointer dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">

                {{-- normal text --}}
                <span wire:loading.remove wire:target="refreshTable" class="inline-flex items-center gap-1">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                    Reload
                </span>

                {{-- loading state --}}
                <span wire:loading.inline wire:target="refreshTable" class="inline-flex items-center gap-1">
                    <svg class="h-3 w-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Loading...
                </span>

            </button>
        </div>
    </div>

    {{-- TABLE + SKELETON WRAPPER --}}
    <div class="relative">
        <div
            wire:loading
            class="absolute inset-0 z-20 bg-white/80 backdrop-blur-[8px] dark:bg-slate-900/70">

            <div class="p-4 space-y-3 animate-pulse">
                @for($i = 0; $i < 6; $i++)
                    <div class="grid grid-cols-12 gap-3 items-center py-2 border-b border-slate-100 dark:border-slate-800">

                        <div class="col-span-1">
                            <div class="h-4 w-4 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-8 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>

                        <div class="col-span-3">
                            <div class="h-4 w-32 bg-slate-200 rounded dark:bg-slate-700"></div>
                            <div class="mt-1 h-3 w-24 bg-slate-100 rounded dark:bg-slate-800"></div>
                        </div>

                        <div class="col-span-2">
                            <div class="h-4 w-24 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-12 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-10 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-10 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>

                        <div class="col-span-1 flex justify-end">
                            <div class="h-6 w-16 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ACTUAL TABLE --}}
        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <table class="min-w-full text-left text-sm">
                <thead class="sticky top-0 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500 border-b border-slate-200 shadow-[0_2px_0_rgba(15,23,42,0.02)] dark:bg-slate-900 dark:text-slate-400 dark:border-slate-700">
                <tr>
                    <th class="w-10 px-4 py-3">
                        <input type="checkbox"
                               wire:click="toggleSelectAll"
                               {{ $selectAll ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 focus:ring-sky-500 dark:border-slate-600 dark:bg-slate-800">
                    </th>

                    <th class="w-16 px-4 py-3 text-slate-700 dark:text-slate-200">ID</th>
                    <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Name</th>
                    <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Parent</th>
                    <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Order</th>
                    <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Featured</th>
                    <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Default</th>
                    <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Created at</th>
                    <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Status</th>
                    <th class="px-4 py-3 text-right text-slate-700 dark:text-slate-200">Operations</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-sm dark:divide-slate-800">
                @forelse($categories as $category)
                    <tr class="transition-colors duration-100 hover:bg-slate-50 dark:hover:bg-slate-800/60">
                        {{-- checkbox --}}
                        <td class="px-4 py-3">
                            <input type="checkbox"
                                   wire:model="selected"
                                   value="{{ $category->id }}"
                                   class="h-4 w-4 rounded border-slate-300 focus:ring-sky-500 dark:border-slate-600 dark:bg-slate-800">
                        </td>

                        {{-- ID --}}
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                            {{ $category->id }}
                        </td>

                        {{-- Name --}}
                        <td class="px-4 py-3 max-w-[240px]">
                            <a href="{{ route('blogs.categories.edit', $category->id) }}"
                               class="block truncate text-sky-700 hover:underline dark:text-sky-300">
                                @if($category->parent_id)
                                    <span class="mr-1 text-slate-400 dark:text-slate-500">↳</span>
                                @endif
                                {{ $category->name }}
                            </a>
                        </td>

                        {{-- Parent column: chain --}}
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">
                            {{ $category->parent_path ?: '—' }}
                        </td>

                        {{-- Order --}}
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">
                            {{ $category->order }}
                        </td>

                        {{-- Is featured --}}
                        <td class="px-4 py-3 text-xs">
                            @if($category->is_featured)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-700 shadow-sm dark:bg-emerald-900/30 dark:text-emerald-200">
                                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Yes
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-700 shadow-sm dark:bg-slate-800 dark:text-slate-200">
                                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                    No
                                </span>
                            @endif
                        </td>

                        {{-- Is default --}}
                        <td class="px-4 py-3 text-xs">
                            @if($category->is_default)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-700 shadow-sm dark:bg-emerald-900/30 dark:text-emerald-200">
                                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Yes
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-700 shadow-sm dark:bg-slate-800 dark:text-slate-200">
                                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                    No
                                </span>
                            @endif
                        </td>

                        {{-- Created at --}}
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                            {{ $category->created_at?->format('Y-m-d') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-xs">
                            @if($category->status === 'published')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-700 shadow-sm dark:bg-emerald-900/30 dark:text-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-700 shadow-sm dark:bg-slate-800 dark:text-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                    Draft
                                </span>
                            @endif
                        </td>

                        {{-- Operations --}}
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('blogs.categories.edit', $category->id) }}"
                                   class="inline-flex items-center rounded-md bg-sky-600 px-2.5 py-1.5 text-xs text-white shadow hover:bg-sky-500 dark:bg-sky-500 dark:hover:bg-sky-400"
                                   title="Edit">
                                    <i class="fa-solid fa-pen text-[11px]"></i>
                                </a>

                                <button
                                    type="button"
                                    onclick="confirm('Delete this category?') || event.stopImmediatePropagation()"
                                    wire:click="deleteCategory({{ $category->id }})"
                                    class="inline-flex items-center rounded-md bg-rose-600 px-2.5 py-1.5 text-xs text-white shadow hover:bg-rose-500 dark:bg-rose-700 dark:hover:bg-rose-600"
                                    title="Delete">
                                    <i class="fa-solid fa-trash text-[11px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                            <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-sky-600 dark:bg-slate-800 dark:text-sky-300">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">No categories found</div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Try adjusting your filters or create a new category to populate the list.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Footer --}}
            <div
                class="flex flex-col items-start justify-between gap-3 border-t border-slate-200 px-4 py-3 text-xs text-slate-500 md:flex-row md:items-center dark:border-slate-700 dark:text-slate-400">
                <div>
                    @if($categories->total())
                        Show from
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $categories->firstItem() }}</span>
                        to
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $categories->lastItem() }}</span>
                        in
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $categories->total() }}</span>
                        records
                    @endif
                </div>
                <div>া
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
