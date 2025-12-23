<x-layouts.app :title="__('Permission')">
    <div class="h-full w-full rounded-xl">
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl
                   border border-slate-200 dark:border-slate-700
                   bg-white dark:bg-slate-900
                   p-4 sm:p-6"
        >
            {{-- Header --}}
            <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[11px] font-medium uppercase tracking-[0.12em] text-slate-600 dark:text-slate-300">
                            Access Control
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-100">
                        Permissions
                    </h1>

                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xl">
                        Manage granular access by assigning permissions to roles and system users.
                    </p>
                </div>

                <div class="w-full sm:w-auto flex items-center gap-3 justify-end">
                    {{-- total count badge --}}
                    <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                        {{ $permissions->total() }} permissions
                    </span>

                    <a
                        href="{{ route('system.permissions.create') }}"
                        class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg
                               bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                               hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-indigo-500
                               focus:ring-offset-2 focus:ring-offset-slate-50
                               dark:focus:ring-offset-slate-900"
                    >
                        <x-icon.plus class="mr-1 h-4 w-4" />
                        Create permission
                    </a>
                </div>
            </header>

            {{-- Content --}}
            <section class="mt-2 space-y-4">
                {{-- Table wrapper card --}}
                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/90 shadow-sm">
                    <div class="border-b border-slate-200 dark:border-slate-800 px-4 py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <i class="fa-solid fa-shield-halved text-slate-400 dark:text-slate-500"></i>
                            <span>System permissions overview</span>
                        </div>

                        {{-- placeholder for future filters/search --}}
                        {{-- <x-system.search-input /> --}}
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50/90 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300"
                                >
                                    #
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300"
                                >
                                    Group
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300"
                                >
                                    Actions
                                </th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @forelse ($permissions as $permission)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/60 transition-colors">
                                    <td class="px-4 py-3 align-middle text-slate-600 dark:text-slate-300 text-xs">
                                        {{ $permissions->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="text-sm font-medium text-slate-900 dark:text-slate-50">
                                                {{ $permission->name }}
                                            </span>
                                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                                @if(method_exists($permission, 'getDescriptionForHuman'))
                                                    {{ $permission->getDescriptionForHuman() }}
                                                @else
                                                    System key:
                                                    <code class="rounded bg-slate-100 px-1.5 py-0.5 text-[11px] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                        {{ $permission->name }}
                                                    </code>
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 align-middle">
                                        @if($permission->group_name)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-1 text-[11px] font-medium text-slate-700 dark:text-slate-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                                {{ ucfirst($permission->group_name) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">
                                                Ungrouped
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center align-middle">
                                        <div class="inline-flex items-center gap-2">
                                            <a
                                                href="{{ route('system.permissions.edit', $permission) }}"
                                                class="inline-flex items-center gap-1 rounded-md border border-slate-300 dark:border-slate-600 px-3 py-1.5 text-xs font-medium
                                                       text-slate-700 dark:text-slate-100
                                                       bg-white dark:bg-slate-900
                                                       hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400
                                                       hover:bg-indigo-50/60 dark:hover:bg-indigo-500/10
                                                       focus:outline-none focus:ring-2 focus:ring-indigo-500
                                                       focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                                            >
                                                <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                                <span>Edit</span>
                                            </a>

                                            <form
                                                action="{{ route('system.permissions.destroy', $permission) }}"
                                                method="POST"
                                                class="inline-flex"
                                                onsubmit="return confirm('Are you sure you want to delete this permission?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1 rounded-md border border-red-500/80 px-3 py-1.5 text-xs font-semibold
                                                           text-red-600 dark:text-red-400
                                                           bg-white dark:bg-slate-900
                                                           hover:bg-red-50 dark:hover:bg-red-500/10
                                                           focus:outline-none focus:ring-2 focus:ring-red-500
                                                           focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                                                >
                                                    <i class="fa-regular fa-trash-can text-[11px]"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400"
                                    >
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400">
                                                <i class="fa-regular fa-circle-question"></i>
                                            </span>
                                            <p class="text-sm font-medium">
                                                No permissions found
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs">
                                                Start by creating a new permission to control access to specific actions and resources.
                                            </p>
                                            <a
                                                href="{{ route('system.permissions.create') }}"
                                                class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                                            >
                                                <x-icon.plus class="h-3 w-3" />
                                                Create first permission
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($permissions->hasPages())
                        <div class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 px-4 py-3">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span>
                                    Showing
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                                        {{ $permissions->firstItem() }}–{{ $permissions->lastItem() }}
                                    </span>
                                    of
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                                        {{ $permissions->total() }}
                                    </span>
                                </span>
                                <div class="sm:ml-auto">
                                    {{ $permissions->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
