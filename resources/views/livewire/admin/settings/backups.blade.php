<div class="p-6 space-y-6">
    <div class="flex items-center text-sm text-slate-500 uppercase font-semibold tracking-wider">
        <span class="text-blue-600">Dashboard</span><span class="mx-2">/</span><span>Settings</span><span class="mx-2">/</span><span class="text-slate-800 dark:text-slate-200">Backups</span>
    </div>

    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-200">
        <p class="font-semibold">Google Drive setup help</p>
        <p class="mt-1">নিচের ধাপগুলো অনুসরণ করে এই পেজ থেকেই credentials ও backup schedule সংরক্ষণ করুন। সার্ভারে শুধু Laravel scheduler চালু রাখতে হবে: <code>* * * * * php artisan schedule:run</code>.</p>
        <ol class="mt-3 list-inside list-decimal space-y-1">
            <li><a class="font-semibold underline" href="https://console.cloud.google.com/apis/library/drive.googleapis.com" target="_blank" rel="noopener noreferrer">Google Drive API চালু করুন</a>।</li>
            <li><a class="font-semibold underline" href="https://console.cloud.google.com/iam-admin/serviceaccounts/create" target="_blank" rel="noopener noreferrer">Service Account তৈরি করুন</a>।</li>
            <li><a class="font-semibold underline" href="https://cloud.google.com/iam/docs/keys-create-delete#creating" target="_blank" rel="noopener noreferrer">JSON private key তৈরি/ডাউনলোড করুন</a> এবং JSON-এর <code>client_email</code> ও <code>private_key</code> নিচে দিন।</li>
            <li>Drive-এ একটি folder তৈরি করে service-account email-কে Editor access দিন—<a class="font-semibold underline" href="https://support.google.com/drive/answer/7166529" target="_blank" rel="noopener noreferrer">folder sharing help</a>। Folder URL-এর শেষ অংশটি Folder ID।</li>
        </ol>
        <div class="mt-3 rounded border border-amber-300 bg-amber-50 p-3 text-amber-900 dark:border-amber-700 dark:bg-amber-950/40 dark:text-amber-100">
            <strong><code>invalid_grant: account not found</code> দেখালে:</strong>
            সাধারণত ভুল/পরিবর্তিত <code>client_email</code>, deleted service account, অথবা অন্য key-এর email ও private key একসঙ্গে ব্যবহার করলে এই error হয়। Google Cloud থেকে একই service account-এর একটি নতুন JSON key download করে নিচের “Import JSON credentials” ব্যবহার করুন—JSON-এর কোনো value হাতে পরিবর্তন করবেন না।
        </div>
        <p class="mt-2">Status: <span class="font-semibold {{ $driveConfigured ? 'text-emerald-600' : 'text-amber-600' }}">{{ $driveConfigured ? 'Google Drive configured' : 'Credentials not configured (local backup only)' }}</span></p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <form wire:submit="saveSettings" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="mb-4 text-lg font-semibold text-slate-800 dark:text-white">Google Drive ও schedule settings</h2>
            <div class="mb-5 rounded-md border border-dashed border-blue-300 p-4 dark:border-blue-700">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Import JSON credentials (Recommended)</label>
                <p class="mb-2 mt-1 text-xs text-slate-500">Google Cloud থেকে download করা service-account JSON file সরাসরি দিন। এতে email/key copy করার ভুল হবে না।</p>
                <input type="file" wire:model="credentialsUpload" accept=".json,application/json" class="block w-full text-sm text-slate-500 file:mr-3 file:rounded file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-blue-700">
                @error('credentialsUpload') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                <button type="button" wire:click="importGoogleCredentials" wire:loading.attr="disabled" wire:target="credentialsUpload,importGoogleCredentials" class="mt-3 rounded bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-200">Import JSON credentials</button>
            </div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Service account client email</label>
            <input type="email" wire:model="driveClientEmail" autocomplete="off" placeholder="backup@project.iam.gserviceaccount.com" class="mt-1 w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900">
            @error('driveClientEmail') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            <label class="mt-4 block text-sm font-medium text-slate-700 dark:text-slate-200">Service account private key</label>
            <textarea wire:model="drivePrivateKey" rows="5" autocomplete="off" placeholder="Saved key পরিবর্তন করতে JSON file-এর -----BEGIN PRIVATE KEY----- থেকে সম্পূর্ণ private_key paste করুন" class="mt-1 w-full rounded-md border-slate-300 font-mono text-xs dark:border-slate-600 dark:bg-slate-900"></textarea>
            <p class="mt-1 text-xs text-slate-500">নিরাপত্তার জন্য saved key আর দেখানো হয় না এবং database-এ encrypted অবস্থায় থাকে। খালি রাখলে আগের key অপরিবর্তিত থাকবে।</p>
            @error('drivePrivateKey') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            <label class="mt-4 block text-sm font-medium text-slate-700 dark:text-slate-200">Google Drive folder ID</label>
            <input type="text" wire:model="driveFolderId" placeholder="1AbCdEf..." class="mt-1 w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900">
            @error('driveFolderId') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            <label class="mb-4 flex items-center gap-3 text-sm text-slate-700 dark:text-slate-200">
                <input type="checkbox" wire:model="automatic" class="rounded border-slate-300 text-blue-600">
                Enable automatic Google Drive backup
            </label>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Backup time</label>
            <input type="time" wire:model="backupTime" class="mt-1 w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-900">
            @error('backupTime') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            <div class="mt-4 flex flex-wrap gap-2">
                <button class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500">সব settings সংরক্ষণ করুন</button>
                <button type="button" wire:click="testDriveConnection" class="rounded bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500" {{ $driveConfigured ? '' : 'disabled' }}>Connection test</button>
            </div>
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
