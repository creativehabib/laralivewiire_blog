<div class="space-y-4">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400 dark:text-slate-600">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-200">Blog</span>
        <span class="mx-1 text-slate-400 dark:text-slate-600">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Tags</span>
    </nav>

    {{-- Flash --}}
    @if (session('message'))
        <div class="mb-2 rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs text-emerald-800 dark:border-emerald-500/50 dark:bg-emerald-900/30 dark:text-emerald-100">
            {{ session('message') }}
        </div>
    @endif

    {{-- Card + Skeleton Wrapper --}}
    <div class="relative">
        <div wire:loading class="absolute inset-0 z-20 bg-white/80 backdrop-blur-[8px] dark:bg-slate-900/70">
            <div class="p-4 space-y-3 animate-pulse">
                @for($i = 0; $i < 6; $i++)
                    <div class="grid grid-cols-12 gap-3 items-center py-2 border-b border-slate-100 dark:border-slate-800">
                        <div class="col-span-1">
                            <div class="h-4 w-4 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>
                        <div class="col-span-1">
                            <div class="h-4 w-8 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>
                        <div class="col-span-4">
                            <div class="h-4 w-32 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>
                        <div class="col-span-3">
                            <div class="h-4 w-24 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>
                        <div class="col-span-2">
                            <div class="h-4 w-20 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>
                        <div class="col-span-1 flex justify-end">
                            <div class="h-6 w-16 bg-slate-200 rounded dark:bg-slate-700"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            {{-- Header actions --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2">
                    {{-- Bulk actions --}}
                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open"
                                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-slate-500">
                            Bulk Actions
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false"
                             class="absolute z-20 mt-1 w-36 rounded-md border border-slate-200 bg-white shadow dark:border-slate-700 dark:bg-slate-900">
                            <button type="button"
                                    data-confirm="Delete selected tags?"
                                    wire:click="bulkDelete"
                                    class="block w-full px-3 py-2 text-left text-xs text-rose-700 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-900/20">
                                Delete selected
                            </button>
                        </div>
                    </div>

                    {{-- Filters (status) --}}
                    <select wire:model.live="status" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 cursor-pointer shadow-sm focus:border-sky-300 focus:ring-sky-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:focus:border-sky-500 dark:focus:ring-slate-700">
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
                            <span class="absolute inset-y-0 left-2 flex items-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input wire:model.live.debounce.400ms="search" class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-7 pr-3 text-xs text-slate-800 placeholder-slate-400 shadow-inner focus:border-sky-300 focus:ring-sky-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-500" placeholder="Search tags..." type="text">
                        </label>
                    </div>

                    {{-- Per page --}}
                    <select wire:model.live="perPage" class="rounded-lg border border-slate-200 bg-white px-2 py-2 text-xs text-slate-700 cursor-pointer shadow-sm focus:border-sky-300 focus:ring-sky-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:focus:border-sky-500 dark:focus:ring-slate-700">
                        <option value="10">10 / page</option>
                        <option value="25">25 / page</option>
                        <option value="50">50 / page</option>
                    </select>

                    {{-- Create --}}
                    <button onclick="window.location='{{ route('blogs.tags.create') }}'" type="button" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-sky-500 cursor-pointer dark:bg-sky-500 dark:hover:bg-sky-400">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Create
                    </button>

                    {{-- Reload --}}
                    <button type="button" wire:click="$refresh" wire:loading.attr="disabled" wire:target="$refresh" class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 cursor-pointer dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        <span wire:loading.remove wire:target="$refresh" class="inline-flex items-center gap-1">
                            <i class="fa-solid fa-rotate-right text-xs"></i>
                            Reload
                        </span>
                        <span wire:loading.inline wire:target="$refresh" class="inline-flex items-center gap-1">
                            <svg class="h-3 w-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
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
                        <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Created at</th>
                        <th class="px-4 py-3 text-slate-700 dark:text-slate-200">Status</th>
                        <th class="px-4 py-3 text-right text-slate-700 dark:text-slate-200">Operations</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse ($tags as $tag)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60">
                            <td class="px-4 py-3">
                                <input type="checkbox"
                                       wire:model="selected"
                                       value="{{ $tag->id }}"
                                       class="h-4 w-4 rounded border-slate-300 focus:ring-sky-500 dark:border-slate-600 dark:bg-slate-800">
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $tag->id }}</td>
                            <td class="px-4 py-3">
                                <a href="#" class="text-sky-700 hover:text-sky-600 hover:underline dark:text-sky-300 dark:hover:text-sky-200">{{ $tag->name }}</a>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $tag->created_at?->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">
                                @if ($tag->status === 'published')
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide bg-slate-100 text-slate-600 dark:bg-slate-500/10 dark:text-slate-300">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    {{-- Edit --}}
                                    <a href="{{ route('blogs.tags.edit', $tag) }}" class="inline-flex items-center rounded-md bg-sky-600 px-2.5 py-1.5 text-xs text-white hover:bg-sky-500 dark:bg-sky-500 dark:hover:bg-sky-400">
                                        <i class="fa-solid fa-pen text-[11px]"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <button wire:click="delete({{ $tag->id }})" data-confirm="Delete this tag?" class="inline-flex items-center rounded-md bg-rose-600 px-2.5 py-1.5 text-xs text-white hover:bg-rose-500 dark:bg-rose-500 dark:hover:bg-rose-400">
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                No tags found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 px-4 py-3 border-t border-slate-200 text-xs text-slate-500 dark:border-slate-700 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300">
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
</div>
