<x-layouts.app :title="__('User Sessions')">
    <div class="h-full w-full rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4 sm:p-6">
            <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[11px] font-medium uppercase tracking-[0.12em] text-slate-600 dark:text-slate-300">User Tracking</span>
                    </div>
                    <h1 class="mt-3 text-xl font-semibold text-slate-900 dark:text-slate-100 sm:text-2xl">
                        Active Sessions: {{ $user->name }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Track devices, browsers, IP/location details and log out sessions from other devices.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('system.users.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800">
                        Back to users
                    </a>
                    <form method="POST" action="{{ route('system.users.sessions.others.destroy', $user) }}" data-confirm="Log out all other sessions for this user?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            Logout other devices
                        </button>
                    </form>
                </div>
            </header>

            <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-sm dark:border-slate-700">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Device</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Browser / OS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">IP & Location</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Last Active</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse ($sessions as $session)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60">
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-200">
                                <div class="font-medium">{{ $session->device_name }}</div>
                                @if ($session->id === $currentSessionId)
                                    <span class="mt-1 inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">Current session</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                <div>{{ $session->browser_name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $session->platform_name }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                <div>{{ $session->ip_address ?: 'Unknown IP' }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $session->location ?: 'Unknown location' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                {{ $session->last_seen_at->diffForHumans() }}
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $session->last_seen_at->toDayDateTimeString() }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('system.users.sessions.destroy', [$user, $session]) }}" data-confirm="Log out this session?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-md border border-red-500 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                        Logout
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">No active sessions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $sessions->links() }}</div>
        </div>
    </div>
</x-layouts.app>
