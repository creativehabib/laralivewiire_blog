<div class="space-y-4">

    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 mb-2">
        <span class="uppercase tracking-[0.16em] text-sky-600">Dashboard</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600">Blog</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400">Posts</span>
    </nav>

    {{-- Flash --}}
    @if (session('message'))
        <div class="mb-2 rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs text-emerald-800">
            {{ session('message') }}
        </div>
    @endif

    {{-- Top toolbar --}}
    <div
        class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between
               rounded-xl border border-slate-200 bg-white px-4 py-3">

        <div class="flex items-center gap-2">

            {{-- Bulk actions --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700">
                    Bulk Actions
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>

                <div x-show="open" @click.outside="open = false"
                     class="absolute z-20 mt-1 w-36 rounded-md border border-slate-200 bg-white shadow">
                    <button type="button"
                            wire:click="bulkDelete"
                            class="block w-full px-3 py-2 text-left text-xs text-rose-600 hover:bg-rose-50">
                        Delete selected
                    </button>
                </div>
            </div>

            {{-- Filters button (future advanced filters) --}}
            <button type="button"
                    class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700">
                Filters
                <i class="fa-solid fa-sliders text-[10px]"></i>
            </button>

            {{-- Category filter --}}
            <select wire:model.live="category"
                    class="rounded-lg border border-slate-300 bg-white px-2 py-2 text-xs text-slate-700 cursor-pointer">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select wire:model.live="status"
                    class="rounded-lg border border-slate-300 bg-white px-2 py-2 text-xs text-slate-700 cursor-pointer">
                <option value="">All status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="trash">Trash</option>
            </select>
        </div>

        <div class="flex flex-1 items-center justify-end gap-2">

            {{-- Search --}}
            <div class="w-full max-w-xs">
                <label class="relative block">
                    <span class="sr-only">Search</span>
                    <span class="absolute inset-y-0 left-2 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        wire:model.live.debounce.400ms="search"
                        class="block w-full rounded-lg border border-slate-300 bg-slate-50 py-2 pl-7 pr-3 text-xs text-slate-800 placeholder-slate-400"
                        placeholder="Search posts..."
                        type="text">
                </label>
            </div>

            {{-- Per page --}}
            <select wire:model.live="perPage"
                    class="rounded-lg border border-slate-300 bg-white px-2 py-2 text-xs text-slate-700 cursor-pointer">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>

            {{-- Create --}}
            <button
                onclick="window.location='{{ route('blogs.posts.create') }}'"
                type="button"
                class="inline-flex items-center gap-1 rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-sky-500 cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                Create
            </button>

            {{-- Reload --}}
            <button
                type="button"
                wire:click="$refresh"
                wire:loading.attr="disabled"
                wire:target="$refresh"
                class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 cursor-pointer">

                {{-- normal text --}}
                <span wire:loading.remove wire:target="$refresh" class="inline-flex items-center gap-1">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                    Reload
                </span>

                {{-- loading state --}}
                <span wire:loading.inline wire:target="$refresh" class="inline-flex items-center gap-1">
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
            class="absolute inset-0 z-20 bg-white/80 backdrop-blur-[8px]">

            <div class="p-4 space-y-3 animate-pulse">
                @for($i = 0; $i < 6; $i++)
                    <div class="grid grid-cols-12 gap-3 items-center py-2 border-b border-slate-100">

                        <div class="col-span-1">
                            <div class="h-4 w-4 bg-slate-200 rounded"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-8 bg-slate-200 rounded"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-10 w-10 bg-slate-200 rounded"></div>
                        </div>

                        <div class="col-span-3">
                            <div class="h-4 w-32 bg-slate-200 rounded"></div>
                            <div class="mt-1 h-3 w-24 bg-slate-100 rounded"></div>
                        </div>

                        <div class="col-span-2">
                            <div class="h-4 w-24 bg-slate-200 rounded"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-20 bg-slate-200 rounded"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-16 bg-slate-200 rounded"></div>
                        </div>

                        <div class="col-span-1">
                            <div class="h-4 w-12 bg-slate-200 rounded"></div>
                        </div>

                        <div class="col-span-1 flex justify-end">
                            <div class="h-6 w-16 bg-slate-200 rounded"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ACTUAL TABLE --}}
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="w-10 px-4 py-3">
                        <input type="checkbox"
                               wire:click="toggleSelectAll"
                               {{ $selectAll ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    </th>

                    {{-- sortable ID --}}
                    <th class="w-16 px-4 py-3 cursor-pointer" wire:click="sortBy('id')">
                        <div class="inline-flex items-center gap-1">
                            ID
                            @if($sortField === 'id')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-slate-300"><i class="fa fa-sort"></i></span>
                            @endif
                        </div>
                    </th>

                    {{-- thumbnail --}}
                    <th class="w-20 px-4 py-3">Image</th>

                    {{-- sortable name --}}
                    <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('name')">
                        <div class="inline-flex items-center gap-1">
                            Name
                            @if($sortField === 'name')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-slate-300"><i class="fa fa-sort"></i></span>
                            @endif
                        </div>
                    </th>

                    <th class="px-4 py-3">Categories</th>
                    <th class="px-4 py-3">Author</th>

                    {{-- sortable date --}}
                    <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                        <div class="inline-flex items-center gap-1">
                            Created at
                            @if($sortField === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @else
                                <span class="text-slate-300"><i class="fa fa-sort"></i></span>
                            @endif
                        </div>
                    </th>

                    <th class="px-4 py-3">SEO</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Operations</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($posts as $post)
                    <tr class="hover:bg-slate-50">
                        {{-- checkbox --}}
                        <td class="px-4 py-3">
                            <input type="checkbox"
                                   wire:model="selected"
                                   value="{{ $post->id }}"
                                   class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        </td>

                        {{-- ID --}}
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $post->id }}
                        </td>

                        {{-- thumb --}}
                        <td class="px-4 py-3">
                            @if($post->image)
                                <img src="{{ $post->image }}"
                                     alt=""
                                     class="h-10 w-10 rounded object-cover border border-slate-200">
                            @else
                                <div class="h-10 w-10 rounded bg-slate-200 flex items-center justify-center text-[10px] text-slate-500">
                                    No img
                                </div>
                            @endif
                        </td>

                        {{-- Name --}}
                        <td class="px-4 py-3 max-w-[220px]">
                            <a href="{{ route('blogs.posts.edit', $post->id) }}"
                               class="block truncate text-sky-700 hover:underline">
                                {{ $post->name }}
                            </a>
                        </td>


                        {{-- categories --}}
                        <td class="px-4 py-3 text-xs text-slate-600">
                            {{ $post->categories->pluck('name')->implode(', ') ?: '—' }}
                        </td>

                        {{-- author --}}
                        <td class="px-4 py-3 text-xs text-slate-600">
                            {{ optional($post->author)->name ?? '—' }}
                        </td>

                        {{-- created at --}}
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $post->created_at?->format('Y-m-d') }}
                        </td>

                        {{-- SEO score (color badge) --}}
                        <td class="px-4 py-3 text-xs">
                            @php
                                $score = $post->seo_score;

                                if (is_null($score)) {
                                    $badgeBg   = 'bg-slate-100';
                                    $badgeText = 'text-slate-500';
                                    $dotBg     = 'bg-slate-400';
                                    $label     = 'N/A';
                                } elseif ($score >= 80) {
                                    // ✅ Green – good SEO
                                    $badgeBg   = 'bg-emerald-50';
                                    $badgeText = 'text-emerald-700';
                                    $dotBg     = 'bg-emerald-500';
                                    $label     = $score . ' / 100';
                                } elseif ($score >= 50) {
                                    // 🟠 Orange – medium
                                    $badgeBg   = 'bg-amber-50';
                                    $badgeText = 'text-amber-700';
                                    $dotBg     = 'bg-amber-500';
                                    $label     = $score . ' / 100';
                                } else {
                                    // 🔴 Red – low SEO
                                    $badgeBg   = 'bg-rose-50';
                                    $badgeText = 'text-rose-700';
                                    $dotBg     = 'bg-rose-500';
                                    $label     = $score . ' / 100';
                                }
                            @endphp

                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $badgeBg }} {{ $badgeText }}">
                                <span class="mr-1 h-1.5 w-1.5 rounded-full {{ $dotBg }}"></span>
                                SEO: {{ $label }}
                            </span>
                        </td>


                        {{-- inline status toggle --}}
                        <td class="px-4 py-3">
                            @if ($post->status === 'published')
                                <button type="button"
                                        wire:click="toggleStatus({{ $post->id }})"
                                        class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-700 cursor-pointer">
                                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Published
                                </button>
                            @else
                                <button type="button"
                                        wire:click="toggleStatus({{ $post->id }})"
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-700 cursor-pointer">
                                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                                    Draft
                                </button>
                            @endif
                        </td>

                        {{-- operations --}}
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                @if($post->trashed())
                                    {{-- trashed: restore + delete forever --}}
                                    <button
                                        type="button"
                                        wire:click="restore({{ $post->id }})"
                                        class="inline-flex items-center rounded-md bg-emerald-600 px-2.5 py-1.5 text-xs text-white hover:bg-emerald-500">
                                        <i class="fa-solid fa-rotate-left text-[11px] mr-1"></i>
                                        Restore
                                    </button>

                                    <button
                                        type="button"
                                        onclick="confirm('Delete permanently?') || event.stopImmediatePropagation()"
                                        wire:click="forceDelete({{ $post->id }})"
                                        class="inline-flex items-center rounded-md bg-rose-700 px-2.5 py-1.5 text-xs text-white hover:bg-rose-600">
                                        <i class="fa-solid fa-skull-crossbones text-[11px] mr-1"></i>
                                        Delete
                                    </button>
                                @else
                                    {{-- normal: edit + soft delete --}}
                                    <a href="{{ route('blogs.posts.edit', $post->id) }}"
                                       class="inline-flex items-center rounded-md bg-sky-600 px-2.5 py-1.5 text-xs text-white hover:bg-sky-500">
                                        <i class="fa-solid fa-pen text-[11px]"></i>
                                    </a>

                                    <button
                                        type="button"
                                        onclick="confirm('Delete this post?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $post->id }})"
                                        class="inline-flex items-center rounded-md bg-rose-600 px-2.5 py-1.5 text-xs text-white hover:bg-rose-500">
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-sm text-slate-500">
                            No posts found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Footer --}}
            <div
                class="flex flex-col items-start justify-between gap-3 border-t border-slate-200 px-4 py-3 text-xs text-slate-500 md:flex-row md:items-center">
                <div>
                    @if($posts->total())
                        Show from
                        <span class="font-semibold text-slate-800">{{ $posts->firstItem() }}</span>
                        to
                        <span class="font-semibold text-slate-800">{{ $posts->lastItem() }}</span>
                        in
                        <span class="font-semibold text-slate-800">{{ $posts->total() }}</span>
                        records
                    @endif
                </div>
                <div>
                    {{ $posts->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
