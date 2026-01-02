<div class="p-6">
    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center text-sm text-slate-500 uppercase font-semibold tracking-wider">
        <span class="text-blue-600">Dashboard</span>
        <span class="mx-2">/</span>
        <span class="text-slate-500">Platform Administration</span>
        <span class="mx-2">/</span>
        <span class="text-slate-800">Activity Logs</span>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        {{-- Settings --}}
        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Activity Log Settings</h2>
            <form wire:submit.prevent="saveSettings" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model="showActivity" class="rounded border-slate-300 focus:ring-blue-500">
                            Show Activity
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model="showIp" class="rounded border-slate-300 focus:ring-blue-500">
                            Show IP Address
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model="showBrowser" class="rounded border-slate-300 focus:ring-blue-500">
                            Show Browser
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model="showOs" class="rounded border-slate-300 focus:ring-blue-500">
                            Show OS
                        </label>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Retention Days</label>
                        <input
                            type="number"
                            min="0"
                            wire:model="retentionDays"
                            class="mt-2 w-full border border-slate-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="15"
                        >
                        <p class="mt-2 text-xs text-slate-500">
                            Set how many days to keep activity logs. Use 0 to disable automatic deletion.
                        </p>
                        @error('retentionDays')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-medium hover:bg-blue-700">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        {{-- Toolbar --}}
        <div class="p-4 flex flex-col sm:flex-row justify-between gap-4 border-b border-slate-200 dark:border-slate-700">
            <div class="flex gap-2">
                {{-- Bulk Actions --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-4 py-2 bg-white border border-slate-300 rounded text-slate-600 text-sm font-medium hover:bg-slate-50 flex items-center gap-2">
                        Bulk Actions <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-48 bg-white rounded shadow-lg border border-slate-200 py-1" style="display: none;">
                        <button wire:click="deleteSelected" wire:confirm="Are you sure?" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-slate-50">
                            Delete Selected
                        </button>
                    </div>
                </div>

                {{-- Search --}}
                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Search..." class="pl-3 pr-10 py-2 border border-slate-300 rounded text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute right-3 top-3 text-slate-400"></i>
                </div>
            </div>

            <div class="flex gap-2">
                <button wire:click="deleteAll" wire:confirm="Are you sure you want to delete ALL records?" class="px-4 py-2 bg-white border border-rose-300 text-rose-600 rounded text-sm font-medium hover:bg-rose-50 flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> Delete all records
                </button>
                <button wire:click="$refresh" class="px-4 py-2 bg-white border border-slate-300 text-slate-600 rounded text-sm font-medium hover:bg-slate-50 flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Reload
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 text-xs uppercase text-slate-500 font-semibold">
                    <th class="p-4 w-10">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-slate-300 focus:ring-blue-500">
                    </th>
                    <th class="p-4 w-16">ID <i class="fas fa-sort text-slate-300 ml-1"></i></th>
                    @if($showActivity)
                        <th class="p-4">Activity</th>
                    @endif
                    @if($showIp)
                        <th class="p-4">IP</th>
                    @endif
                    @if($showBrowser)
                        <th class="p-4">Browser</th>
                    @endif
                    @if($showOs)
                        <th class="p-4">OS</th>
                    @endif
                    <th class="p-4 text-right">Operations</th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="p-4">
                            <input type="checkbox" wire:model.live="selected" value="{{ $log->id }}" class="rounded border-slate-300 focus:ring-blue-500">
                        </td>
                        <td class="p-4 text-slate-500">{{ $log->id }}</td>
                        @if($showActivity)
                            <td class="p-4">
                                <div class="flex items-start gap-3">
                                    {{-- User Icon --}}
                                    <div class="w-10 h-10 rounded bg-slate-200 flex items-center justify-center text-slate-500 flex-shrink-0">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div>
                                        <div class="flex flex-wrap items-center gap-1.5 text-slate-800 dark:text-slate-200">
                                            {{-- User Name --}}
                                            <a href="#" class="font-semibold text-blue-600 hover:underline">
                                                {{ $log->causer?->name ?? 'System' }}
                                            </a>

                                            {{-- Role Badge (Demo logic) --}}
                                            @if($log->causer)
                                                <span class="bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded font-bold">admin</span>
                                            @endif

                                            <span>{{ $log->description }}</span>
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500 flex flex-wrap items-center gap-2">
                                            <span>{{ $log->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endif
                        @if($showIp)
                            <td class="p-4 text-slate-600">
                                {{ $log->properties['ip'] ?? '—' }}
                            </td>
                        @endif
                        @if($showBrowser)
                            <td class="p-4 text-slate-600">
                                {{ $this->resolveBrowser($log->properties['user_agent'] ?? null) }}
                            </td>
                        @endif
                        @if($showOs)
                            <td class="p-4 text-slate-600">
                                {{ $this->resolveOs($log->properties['user_agent'] ?? null) }}
                            </td>
                        @endif
                        <td class="p-4 text-right">
                            <button wire:click="delete({{ $log->id }})" wire:confirm="Delete this log?" class="w-8 h-8 rounded bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors flex items-center justify-center ml-auto">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    @php
                        $columnCount = 3;
                        $columnCount += $showActivity ? 1 : 0;
                        $columnCount += $showIp ? 1 : 0;
                        $columnCount += $showBrowser ? 1 : 0;
                        $columnCount += $showOs ? 1 : 0;
                    @endphp
                    <tr>
                        <td colspan="{{ $columnCount }}" class="p-8 text-center text-slate-500">
                            No activity logs found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer / Pagination --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex items-center justify-between text-sm text-slate-500">
            <div class="flex items-center gap-1">
                <i class="fas fa-globe"></i>
                Show from {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} in {{ $logs->total() }} records
            </div>

            {{-- Simple Pagination Links --}}
            <div>
                {{ $logs->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
