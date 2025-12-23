<x-layouts.app :title="__('Users Management')">
    <div class="h-full w-full rounded-xl">
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4 sm:p-6"
        >
            {{-- Header --}}
            <header class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[11px] font-medium uppercase tracking-[0.12em] text-slate-600 dark:text-slate-300">
                            Users Management
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-100">
                        Users
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        View, create and manage user accounts.
                    </p>
                </div>

                <div class="w-full sm:w-auto">
                    <a
                        href="{{ route('system.users.create') }}"
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500
                               focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                    >
                        <span class="text-base leading-none">＋</span>
                        <span>Create user</span>
                    </a>
                </div>
            </header>

            {{-- Content --}}
            <div class="mt-2">
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
                    <div class="p-4 sm:p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800/80">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                                        #
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                                        Name
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                                        Email
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                                        Role
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                                        Actions
                                    </th>
                                </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                                        {{-- # --}}
                                        <td class="px-4 py-3 align-middle text-slate-700 dark:text-slate-200">
                                            {{ $loop->iteration }}
                                        </td>

                                        {{-- Name --}}
                                        <td class="px-4 py-3 align-middle">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                    {{ $user->name }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-4 py-3 align-middle">
                                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                                {{ $user->email }}
                                            </span>
                                        </td>

                                        {{-- Role --}}
                                        <td class="px-4 py-3 align-middle">
                                            @if ($user->roles->isNotEmpty())
                                                <span
                                                    class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-500/15 px-2.5 py-0.5 text-xs font-medium text-indigo-700 dark:text-indigo-300"
                                                >
                                                    {{ $user->roles->first()->name }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-xs font-medium text-slate-500 dark:text-slate-400"
                                                >
                                                    No role
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-4 py-3 align-middle text-center">
                                            <div class="inline-flex items-center gap-2">
                                                {{-- Edit --}}
                                                <a
                                                    href="{{ route('system.users.edit', $user) }}"
                                                    class="inline-flex items-center rounded-md border border-indigo-500 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-300
                                                           hover:bg-indigo-50 dark:hover:bg-indigo-500/10
                                                           focus:outline-none focus:ring-2 focus:ring-indigo-500
                                                           focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                                                >
                                                    Edit
                                                </a>

                                                {{-- Delete --}}
                                                <form
                                                    action="{{ route('system.users.destroy', $user) }}"
                                                    method="POST"
                                                    class="inline-flex"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center rounded-md border border-red-500 px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400
                                                               hover:bg-red-50 dark:hover:bg-red-500/10
                                                               focus:outline-none focus:ring-2 focus:ring-red-500
                                                               focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="5"
                                            class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400"
                                        >
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
