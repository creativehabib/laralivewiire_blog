<?php

namespace App\Livewire\Admin\Settings;

use App\Services\DatabaseBackup;
use App\Services\GoogleDriveBackup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;
use Throwable;

class Backups extends Component
{
    use WithFileUploads;

    public bool $automatic = false;
    public string $backupTime = '02:00';
    public ?string $driveFolderId = null;
    public string $driveClientEmail = '';
    public string $drivePrivateKey = '';
    public $credentialsUpload;
    public $backupUpload;

    public function mount(): void
    {
        $this->automatic = (bool) setting('backup_automatic', false);
        $this->backupTime = (string) setting('backup_time', '02:00');
        $this->driveFolderId = setting('backup_drive_folder_id', config('backup.google_drive.folder_id'));
        $this->driveClientEmail = (string) setting('backup_drive_client_email', config('backup.google_drive.client_email'));
    }

    public function saveSettings(): void
    {
        $this->validate([
            'backupTime' => ['required', 'date_format:H:i'],
            'driveClientEmail' => ['nullable', 'email', 'max:255'],
            'drivePrivateKey' => ['nullable', 'string'],
            'driveFolderId' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->automatic && blank($this->driveFolderId)) {
            $this->addError('driveFolderId', 'Automatic Google Drive backup-এর জন্য folder ID প্রয়োজন।');

            return;
        }

        if ($this->automatic && (blank($this->driveClientEmail) || ! $this->hasPrivateKey())) {
            $this->addError('drivePrivateKey', 'Automatic backup চালু করতে service-account email ও private key প্রয়োজন।');

            return;
        }
        set_setting('backup_automatic', $this->automatic, 'backup');
        set_setting('backup_time', $this->backupTime, 'backup');
        set_setting('backup_drive_folder_id', $this->driveFolderId, 'backup');
        set_setting('backup_drive_client_email', trim($this->driveClientEmail), 'backup');
        if (filled($this->drivePrivateKey)) {
            set_setting('backup_drive_private_key', Crypt::encryptString(trim($this->drivePrivateKey)), 'backup');
            $this->drivePrivateKey = '';
        }
        $this->toast('success', 'Backup schedule saved successfully.');
    }

    public function testDriveConnection(GoogleDriveBackup $drive): void
    {
        try {
            $drive->testConnection();
            $this->toast('success', 'Google Drive connection is working.');
        } catch (Throwable $exception) {
            $this->toast('error', $exception->getMessage());
        }
    }

    public function importGoogleCredentials(): void
    {
        $this->validate([
            'credentialsUpload' => ['required', 'file', 'extensions:json', 'max:100'],
        ]);

        $credentials = json_decode(File::get($this->credentialsUpload->getRealPath()), true);

        if (! is_array($credentials)
            || ($credentials['type'] ?? null) !== 'service_account'
            || blank($credentials['client_email'] ?? null)
            || blank($credentials['private_key'] ?? null)) {
            $this->addError('credentialsUpload', 'সঠিক Google service-account JSON key নির্বাচন করুন।');

            return;
        }

        $this->driveClientEmail = trim($credentials['client_email']);
        set_setting('backup_drive_client_email', $this->driveClientEmail, 'backup');
        set_setting('backup_drive_private_key', Crypt::encryptString(trim($credentials['private_key'])), 'backup');
        $this->reset('credentialsUpload', 'drivePrivateKey');
        $this->toast('success', 'Google service-account credentials import হয়েছে। এখন folder ID দিয়ে settings save ও connection test করুন।');
    }

    public function generateBackup(DatabaseBackup $backups, GoogleDriveBackup $drive): void
    {
        try {
            $path = $backups->create();
            if ($drive->configured()) {
                $drive->upload($path, $this->driveFolderId);
                $this->toast('success', 'Backup created and uploaded to Google Drive.');
                return;
            }
            $this->toast('success', 'Local backup created successfully.');
        } catch (Throwable $exception) {
            $this->toast('error', $exception->getMessage());
        }
    }

    public function importBackup(DatabaseBackup $backups): void
    {
        $this->validate(['backupUpload' => ['required', 'file', 'extensions:sql,txt', 'max:512000']]);
        File::ensureDirectoryExists($backups->directory());
        File::copy(
            $this->backupUpload->getRealPath(),
            $backups->directory().DIRECTORY_SEPARATOR.basename($this->backupUpload->getClientOriginalName())
        );
        $this->reset('backupUpload');
        $this->toast('success', 'Backup file uploaded. You can now restore it.');
    }

    public function uploadToDrive(string $backup, DatabaseBackup $backups, GoogleDriveBackup $drive): void
    {
        try {
            $drive->upload($this->backupPath($backup, $backups), $this->driveFolderId);
            $this->toast('success', 'Backup uploaded to Google Drive.');
        } catch (Throwable $exception) {
            $this->toast('error', $exception->getMessage());
        }
    }

    public function restoreBackup(string $backup, DatabaseBackup $backups): void
    {
        try {
            $backups->restore($this->backupPath($backup, $backups));
            $this->toast('success', 'Backup restored successfully.');
        } catch (Throwable $exception) {
            $this->toast('error', $exception->getMessage());
        }
    }

    public function downloadBackup(string $backup, DatabaseBackup $backups)
    {
        $path = $this->backupPath($backup, $backups);
        return File::exists($path) ? response()->download($path) : null;
    }

    public function deleteBackup(string $backup, DatabaseBackup $backups): void
    {
        File::delete($this->backupPath($backup, $backups));
        $this->toast('success', 'Backup deleted successfully.');
    }

    public function render(DatabaseBackup $backups, GoogleDriveBackup $drive)
    {
        return view('livewire.admin.settings.backups', [
            'backups' => $this->listBackups($backups),
            'driveConfigured' => $drive->configured(),
        ]);
    }

    private function backupPath(string $backup, DatabaseBackup $backups): string
    {
        $path = $backups->directory().DIRECTORY_SEPARATOR.basename($backup);
        if (! File::exists($path)) {
            throw new RuntimeException('Backup file not found.');
        }
        return $path;
    }

    private function listBackups(DatabaseBackup $backups): array
    {
        if (! File::isDirectory($backups->directory())) return [];
        return collect(File::files($backups->directory()))
            ->filter(fn (\SplFileInfo $file) => $file->isFile() && str_ends_with(strtolower($file->getFilename()), '.sql'))
            ->map(fn (\SplFileInfo $file) => [
                'name' => $file->getFilename(),
                'description' => 'Database dump',
                'size' => number_format($file->getSize() / 1024 / 1024, 2).' MB',
                'created_at' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
            ])->sortByDesc('created_at')->values()->all();
    }

    private function toast(string $type, string $message): void
    {
        $this->dispatch('media-toast', type: $type, message: $message);
    }

    private function hasPrivateKey(): bool
    {
        return filled($this->drivePrivateKey)
            || filled(setting('backup_drive_private_key'))
            || filled(config('backup.google_drive.private_key'));
    }
}
