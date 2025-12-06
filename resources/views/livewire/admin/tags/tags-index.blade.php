<div class="">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-4">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-300">Blog</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Tags</span>
    </nav>

    {{-- Flash message --}}
    @if (session('message'))
        <div
            class="mb-4 rounded-lg border px-4 py-2 text-xs
                   border-emerald-300 bg-emerald-50 text-emerald-800
                   dark:border-emerald-600/40 dark:bg-emerald-600/10 dark:text-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    {{-- Card --}}
    <div
        class="bg-white border border-slate-200 text-slate-900 rounded-xl shadow-sm
               dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">

        {{-- Header actions --}}
        <div
            class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between px-4 py-3
                   border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-2">
                {{-- Bulk actions --}}
                <button
                    class="inline-flex items-center gap-1 rounded-lg border px-3 py-2 text-xs font-medium
                           border-slate-300 bg-white text-slate-700 hover:bg-slate-50
                           dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">
                    Bulk Actions
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>

                {{-- Filters (status) --}}
                <select
                    wire:model="status"
                    class="rounded-lg border px-3 py-2 text-xs
                           border-slate-300 bg-white text-slate-700 focus:border-sky-500 focus:ring-sky-500
                           dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                    <option value="">All status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="flex flex-1 items-center justify-end gap-2">
                {{-- Search --}}
                <div class="w-full max-w-xs">
                    <label class="relative block">
                        <span class="sr-only">Search</span>
                        <span
                            class="absolute inset-y-0 left-2 flex items-center text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input
                            wire:model.debounce.400ms="search"
                            class="block w-full rounded-lg border py-2 pl-7 pr-3 text-xs
                                   border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400
                                   focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                   dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                            placeholder="Search..."
                            type="text">
                    </label>
                </div>

                {{-- Create --}}
                <button
                    onclick="window.location='{{ route('blogs.tags.create') }}'"
                    class="inline-flex items-center gap-1 rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-sky-500">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Create
                </button>

                {{-- Reload --}}
                <button
                    wire:click="$refresh"
                    class="inline-flex items-center gap-1 rounded-lg border px-3 py-2 text-xs font-medium
                           border-slate-300 bg-white text-slate-700 hover:bg-slate-50
                           dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                    Reload
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead
                    class="text-xs uppercase border-b
                           bg-slate-50 text-slate-500 border-slate-200
                           dark:bg-slate-800/80 dark:text-slate-400 dark:border-slate-700">
                <tr>
                    <th class="w-10 px-4 py-3">
                        <input type="checkbox"
                               class="h-4 w-4 rounded border-slate-300 bg-white text-sky-600 focus:ring-sky-500
                                      dark:border-slate-600 dark:bg-slate-900 dark:text-sky-500">
                    </th>
                    <th class="w-16 px-4 py-3">
                        <div class="inline-flex items-center gap-1">
                            ID
                            <span class="text-slate-300 dark:text-slate-500"><i class="fa fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">
                        <div class="inline-flex items-center gap-1">
                            Created at
                            <span class="text-slate-300 dark:text-slate-500"><i class="fa fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3">
                        <div class="inline-flex items-center gap-1">
                            Status
                            <span class="text-slate-300 dark:text-slate-500"><i class="fa fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-right">Operations</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                @forelse ($tags as $tag)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40">
                        <td class="px-4 py-3">
                            <input type="checkbox"
                                   class="h-4 w-4 rounded border-slate-300 bg-white text-sky-600 focus:ring-sky-500
                                          dark:border-slate-600 dark:bg-slate-900 dark:text-sky-500">
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                            {{ $tag->id }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="#"
                               class="text-sky-700 hover:text-sky-600 hover:underline
                                      dark:text-sky-300 dark:hover:text-sky-200">
                                {{ $tag->name }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                            {{ $tag->created_at?->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($tag->status === 'published')
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide
                                           bg-emerald-100 text-emerald-700
                                           dark:bg-emerald-500/10 dark:text-emerald-300">
                                    <span
                                        class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                    Published
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide
                                           bg-slate-100 text-slate-600
                                           dark:bg-slate-500/10 dark:text-slate-300">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                {{-- Edit --}}
                                <a href="{{ route('blogs.tags.edit', $tag) }}"
                                   class="inline-flex items-center rounded-md bg-sky-600 px-2.5 py-1.5 text-xs text-white hover:bg-sky-500">
                                    <i class="fa-solid fa-pen text-[11px]"></i>
                                </a>


                                {{-- Delete --}}
                                <button
                                    wire:click="delete({{ $tag->id }})"
                                    onclick="return confirm('Delete this tag?')"
                                    class="inline-flex items-center rounded-md bg-rose-600 px-2.5 py-1.5 text-xs text-white hover:bg-rose-500">
                                    <i class="fa-solid fa-trash text-[11px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                            No tags found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 px-4 py-3 border-t
                   border-slate-200 text-xs text-slate-500
                   dark:border-slate-700 dark:text-slate-400">
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex h-6 w-6 items-center justify-center rounded-full border
                           border-slate-300 bg-white text-slate-500
                           dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <i class="fa-solid fa-globe text-[11px]"></i>
                </span>
                <span>
                    Show from
                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $tags->firstItem() }}</span>
                    to
                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $tags->lastItem() }}</span>
                    in
                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $tags->total() }}</span>
                    records
                </span>
            </div>

            <div>
                {{ $tags->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
