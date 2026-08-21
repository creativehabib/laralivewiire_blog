<div class="p-6 space-y-6">
    <div class="flex items-center text-sm text-slate-500 uppercase font-semibold tracking-wider">
        <span class="text-blue-600">Dashboard</span><span class="mx-2">/</span><span>Settings</span><span class="mx-2">/</span><span class="text-slate-800 dark:text-slate-200">Backups</span>
    </div>

    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-200">
        <p class="font-semibold">Google Drive setup</p>
        <p class="mt-1">Add the service account email and private key to your <code>.env</code>, then share the destination Drive folder with that service-account email. Keep Laravel's scheduler running with <code>* * * * * php artisan schedule:run</code>.</p>
        <p class="mt-2">Status: <span class="font-semibold {{ $driveConfigured ? 'text-emerald-600' : 'text-amber-600' }}">{{ $driveConfigured ? 'Google Drive configured' : 'Credentials not configured (local backup only)' }}</span></p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <form wire:submit="saveSettings" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="mb-4 text-lg font-semibold text-slate-800 dark:text-white">Daily automatic backup</h2>
            <label class="mb-4 flex items-center gap-3 text-sm text-slate-700 dark:text-slate-200">
                <input type="checkbox" wire:model="automatic" class="rounded border-slate-300 text-blue-600">
                Enable automatic Google Drive backup
            </label>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Backup time</label>
            <input type="time" wire:model="backupTime" class="mt-1 w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900">
            @error('backupTime') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            <label class="mt-4 block text-sm font-medium text-slate-700 dark:text-slate-200">Google Drive folder ID</label>
            <input type="text" wire:model="driveFolderId" placeholder="Optional when configured in .env" class="mt-1 w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900">
            <button class="mt-4 rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500">Save schedule</button>
        </form>

        <form wire:submit="importBackup" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="mb-2 text-lg font-semibold text-slate-800 dark:text-white">Upload a backup</h2>
            <p class="mb-4 text-sm text-slate-500">Upload an SQL dump (maximum 500 MB) to make it available for restore.</p>
            <input type="file" wire:model="backupUpload" accept=".sql,.txt" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded file:border-0 file:bg-slate-100 file:px-4 file:py-2">
            @error('backupUpload') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            <button class="mt-4 rounded bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-600" wire:loading.attr="disabled">Upload backup</button>
        </form>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="flex items-center justify-between border-b border-slate-200 p-4 dark:border-slate-700">
            <h2 class="font-semibold text-slate-800 dark:text-white">Local backups</h2>
            <button wire:click="generateBackup" wire:loading.attr="disabled" class="flex items-center gap-2 rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500"><i class="fas fa-database"></i> Generate & upload</button>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-left">
            <thead><tr class="border-b bg-slate-50 text-xs uppercase text-slate-500 dark:border-slate-700 dark:bg-slate-900/50"><th class="p-4">Name</th><th class="p-4">Size</th><th class="p-4">Created</th><th class="p-4 text-right">Operations</th></tr></thead>
            <tbody class="divide-y text-sm dark:divide-slate-700">
            @forelse($backups as $backup)
                <tr><td class="p-4 font-medium text-slate-800 dark:text-slate-200">{{ $backup['name'] }}</td><td class="p-4 text-slate-500">{{ $backup['size'] }}</td><td class="p-4 text-slate-500">{{ $backup['created_at'] }}</td>
                    <td class="p-4"><div class="flex justify-end gap-2">
                        <button wire:click="uploadToDrive('{{ $backup['name'] }}')" title="Upload to Google Drive" class="h-8 w-8 rounded bg-amber-500 text-white"><i class="fab fa-google-drive"></i></button>
                        <button wire:click="restoreBackup('{{ $backup['name'] }}')" wire:confirm="Restore this backup? Existing data may be overwritten." title="Restore" class="h-8 w-8 rounded bg-emerald-500 text-white"><i class="fas fa-database"></i></button>
                        <button wire:click="downloadBackup('{{ $backup['name'] }}')" title="Download" class="h-8 w-8 rounded bg-blue-600 text-white"><i class="fas fa-download"></i></button>
                        <button wire:click="deleteBackup('{{ $backup['name'] }}')" wire:confirm="Delete this backup?" title="Delete" class="h-8 w-8 rounded bg-rose-500 text-white"><i class="fas fa-trash-alt"></i></button>
                    </div></td></tr>
            @empty <tr><td colspan="4" class="p-12 text-center text-slate-500">No backups available.</td></tr> @endforelse
            </tbody>
        </table></div>
    </div>
</div>
