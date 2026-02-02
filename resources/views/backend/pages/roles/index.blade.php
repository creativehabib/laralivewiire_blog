<x-layouts.app :title="__('Role & Permission')">
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
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                        <span class="text-[11px] font-medium uppercase tracking-[0.12em] text-slate-600 dark:text-slate-300">
                            Roles &amp; Permissions
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-100">
                        Roles &amp; Permissions
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 max-w-xl">
                        Manage system roles and the permissions assigned to each.
                    </p>
                </div>

                <div class="w-full sm:w-auto flex items-center justify-end">
                    <a
                        href="{{ route('system.roles.create') }}"
                        class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg
                               bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                               hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-indigo-500
                               focus:ring-offset-2 focus:ring-offset-slate-50
                               dark:focus:ring-offset-slate-900"
                    >
                        <i class="fa-solid fa-plus mr-1 h-4 w-4"></i>
                        Create role
                    </a>
                </div>
            </header>

            {{-- Content --}}
            <div class="page-section">
                <div class="bg-white dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="border-b border-slate-200 dark:border-slate-800 px-4 py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <i class="fa-solid fa-users-gear text-slate-400 dark:text-slate-500"></i>
                            <span>Role overview and assigned permissions</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50/80 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300"
                                >
                                    Permissions
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
                            @forelse ($roles as $role)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/60 transition-colors">
                                    {{-- Role Name --}}
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-col gap-0.5">
                                                <span class="font-medium text-slate-900 dark:text-slate-50">
                                                    {{ $role->name }}
                                                </span>

                                            @if($role->guard_name ?? false)
                                                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                                        Guard:
                                                        <span class="font-mono">
                                                            {{ $role->guard_name }}
                                                        </span>
                                                    </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Permissions --}}
                                    <td class="px-4 py-3 align-top text-center">
                                        @if ($role->permissions_count > 0)
                                            <div class="flex flex-wrap justify-center gap-1">
                                                @foreach ($role->permissions->take(4) as $permission)
                                                    <span
                                                        class="inline-flex items-center rounded-full border border-slate-200 dark:border-slate-700
                                                                   bg-slate-50 dark:bg-slate-800 px-2.5 py-0.5
                                                                   text-xs font-medium text-slate-700 dark:text-slate-200"
                                                    >
                                                            {{ $permission->name }}
                                                        </span>
                                                @endforeach

                                                @if ($role->permissions_count > 4)
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-slate-900 dark:bg-slate-200
                                                                   px-2.5 py-0.5 text-xs font-semibold
                                                                   text-slate-50 dark:text-slate-900"
                                                    >
                                                            +{{ $role->permissions_count - 4 }}
                                                        </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-slate-500">
                                                    None
                                                </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-3 align-top text-center">
                                        <div class="inline-flex flex-wrap items-center justify-center gap-2">
                                            {{-- Edit --}}
                                            <a
                                                href="{{ route('system.roles.edit', $role) }}"
                                                class="inline-flex items-center rounded-lg border border-slate-300 dark:border-slate-600
                                                           bg-white dark:bg-slate-900
                                                           px-3 py-1.5 text-xs font-semibold
                                                           text-slate-700 dark:text-slate-100
                                                           hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400
                                                           hover:bg-indigo-50/60 dark:hover:bg-indigo-500/10
                                                           focus:outline-none focus:ring-2 focus:ring-indigo-500
                                                           focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                                            >
                                                <i class="fa-regular fa-pen-to-square mr-1 text-[11px]"></i>
                                                Edit
                                            </a>

                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('system.roles.destroy', $role) }}"
                                                method="POST"
                                                class="inline-flex"
                                                data-confirm="Are you sure you want to delete this role?"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    {{ $role->name === 'admin' ? 'disabled' : '' }}
                                                    class="inline-flex items-center rounded-lg border border-red-500/80
                                                               bg-white dark:bg-slate-900
                                                               px-3 py-1.5 text-xs font-semibold
                                                               text-red-600 dark:text-red-400
                                                               hover:bg-red-50 dark:hover:bg-red-500/10
                                                               focus:outline-none focus:ring-2 focus:ring-red-500
                                                               focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900
                                                               disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    <i class="fa-regular fa-trash-can mr-1 text-[11px]"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="3"
                                        class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400"
                                    >
                                        <div class="flex flex-col items-center justify-center gap-2">
                                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400">
                                                    <i class="fa-regular fa-circle-question"></i>
                                                </span>
                                            <p class="text-sm font-medium">
                                                No roles found
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs">
                                                Create roles to group permissions and control access to different areas of your application.
                                            </p>
                                            <a
                                                href="{{ route('system.roles.create') }}"
                                                class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                                            >
                                                <i class="fa-solid fa-plus h-3 w-3"></i>
                                                Create first role
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (optional if you paginate roles) --}}
                    @if(method_exists($roles, 'hasPages') && $roles->hasPages())
                        <div class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/70 px-4 py-3">
                            <div class="flex items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                                <span>
                                    Showing
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                                        {{ $roles->firstItem() }}–{{ $roles->lastItem() }}
                                    </span>
                                    of
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                                        {{ $roles->total() }}
                                    </span>
                                </span>
                                <div>
                                    {{ $roles->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
