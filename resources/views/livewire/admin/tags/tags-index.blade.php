<div class="space-y-5">
    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-300">Blog</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Tags</span>
    </nav>

    {{-- Flash --}}
    @if (session('message'))
        <div class="mb-4 rounded-lg border px-4 py-2 text-xs border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-600/40 dark:bg-emerald-600/10 dark:text-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid gap-5 lg:grid-cols-3">
        {{-- Overview card --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-sky-50 via-white to-white shadow-sm dark:border-slate-700 dark:from-slate-900 dark:via-slate-900 dark:to-slate-900">
                <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-900/40">
                            <i class="fa-solid fa-tags text-lg"></i>
                        </span>
                        <div class="space-y-1">
                            <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100">Tag manager</h2>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Organize your blog taxonomy, search quickly, and jump into editing with a clean card-style layout.</p>
                            <div class="flex flex-wrap gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                    <i class="fa-regular fa-circle-check text-[10px]"></i>
                                    Live updates
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-3 py-1 font-semibold text-sky-700 dark:bg-sky-900/50 dark:text-sky-300">
                                    <i class="fa-solid fa-bolt text-[10px]"></i>
                                    Quick actions
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button onclick="window.location='{{ route('blogs.tags.create') }}'" type="button" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-sky-500 cursor-pointer dark:bg-sky-500 dark:hover:bg-sky-400">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Create tag
                        </button>
                        <button type="button" wire:click="$refresh" wire:loading.attr="disabled" wire:target="$refresh" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                            <span wire:loading.remove wire:target="$refresh" class="inline-flex items-center gap-1">
                                <i class="fa-solid fa-rotate-right text-xs"></i>
                                Refresh
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

                {{-- Filters --}}
                <div class="grid gap-3 border-b border-slate-200 px-6 py-4 text-xs dark:border-slate-800 md:grid-cols-2 lg:grid-cols-3">
                    <label class="relative block">
                        <span class="text-[11px] font-semibold text-slate-600 dark:text-slate-200">Search tags</span>
                        <span class="absolute inset-y-0 left-3 top-6 flex items-center text-slate-400"><i class="fa-solid fa-magnifying-glass text-[11px]"></i></span>
                        <input wire:model.live.debounce.400ms="search" type="text" placeholder="Type to filter tags" class="mt-1 block w-full rounded-lg border px-3 py-2 pl-8 text-xs border-slate-200 bg-slate-50 text-slate-800 placeholder:text-slate-400 focus:border-sky-400 focus:ring-sky-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    </label>

                    <label class="block">
                        <span class="text-[11px] font-semibold text-slate-600 dark:text-slate-200">Status</span>
                        <select wire:model.live="status" class="mt-1 block w-full rounded-lg border px-3 py-2 text-xs border-slate-200 bg-white text-slate-700 cursor-pointer focus:border-sky-400 focus:ring-sky-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            <option value="">All status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="text-[11px] font-semibold text-slate-600 dark:text-slate-200">Per page</span>
                        <select wire:model.live="perPage" class="mt-1 block w-full rounded-lg border px-3 py-2 text-xs border-slate-200 bg-white text-slate-700 cursor-pointer focus:border-sky-400 focus:ring-sky-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            <option value="10">10 results</option>
                            <option value="25">25 results</option>
                            <option value="50">50 results</option>
                        </select>
                    </label>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="sticky top-0 bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500 border-b border-slate-200 shadow-[0_2px_0_rgba(15,23,42,0.02)] dark:bg-slate-900 dark:text-slate-400 dark:border-slate-800">
                        <tr>
                            <th class="w-10 px-4 py-3">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 dark:border-slate-600 dark:bg-slate-800">
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
                                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 dark:border-slate-600 dark:bg-slate-800">
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $tag->id }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('blogs.tags.edit', $tag) }}" class="text-sky-700 hover:text-sky-600 hover:underline dark:text-sky-300 dark:hover:text-sky-200">{{ $tag->name }}</a>
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
                                        <button wire:click="delete({{ $tag->id }})" onclick="return confirm('Delete this tag?')" class="inline-flex items-center rounded-md bg-rose-600 px-2.5 py-1.5 text-xs text-white hover:bg-rose-500 dark:bg-rose-500 dark:hover:bg-rose-400">
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
                <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                            <i class="fa-solid fa-globe text-[11px]"></i>
                        </span>
                        <span>
                            Showing
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $tags->firstItem() }}</span>
                            to
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $tags->lastItem() }}</span>
                            of
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $tags->total() }}</span>
                            tags
                        </span>
                    </div>

                    <div class="md:text-right">
                        {{ $tags->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick stats --}}
        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40">
                        <i class="fa-regular fa-circle-check"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Tag health</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Keep your taxonomy tidy and consistent with clear status signals.</p>
                    </div>
                </div>
                <div class="px-5 py-4 space-y-3 text-sm text-slate-700 dark:text-slate-200">
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-[13px] dark:bg-slate-800/70">
                        <span class="font-semibold">Total tags</span>
                        <span class="text-slate-900 dark:text-slate-100">{{ $tags->total() }}</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Need a new tag? Use the <span class="font-semibold text-sky-600 dark:text-sky-400">Create tag</span> button above to add it instantly, just like creating a new post from the post form.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 shadow-inner dark:border-slate-700 dark:bg-slate-900/70">
                <div class="flex items-center gap-3 px-5 py-4">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-900/40">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </span>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Quick tips</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Use consistent naming, avoid duplicates, and keep drafts hidden until ready.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Skeleton overlay --}}
    <div wire:loading class="fixed inset-0 z-30 bg-white/80 backdrop-blur-[8px] dark:bg-slate-950/80">
        <div class="absolute inset-0 pointer-events-none">
            <div class="mx-auto mt-12 w-full max-w-6xl space-y-3 px-4 animate-pulse">
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
    </div>
</div>
