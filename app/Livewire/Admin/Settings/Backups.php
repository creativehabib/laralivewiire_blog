<?php

namespace App\Livewire\Admin\Settings;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;
use Symfony\Component\Process\Process;

class Backups extends Component
{
    private string $backupFolder = 'backups/database';

    public function generateBackup(): void
    {
        [$connection, $config] = $this->databaseConfig();

        if (! $config) {
            $this->dispatch('media-toast', type: 'error', message: 'Database connection is not configured.');
            return;
        }

        File::ensureDirectoryExists($this->backupDirectory());

        $filename = sprintf(
            '%s-%s.sql',
            Str::slug($connection),
            now()->format('Ymd_His')
        );

        $fullPath = $this->backupDirectory() . DIRECTORY_SEPARATOR . $filename;

        $process = $this->buildBackupProcess($config, $fullPath);

        if (! $process) {
            return;
        }

        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->dispatch(
                'media-toast',
                type: 'error',
                message: 'Backup failed. ' . trim($process->getErrorOutput())
            );
            return;
        }

        if (($config['driver'] ?? '') === 'sqlite') {
            File::put($fullPath, $process->getOutput());
        }

        $this->dispatch('media-toast', type: 'success', message: 'Backup created successfully.');
    }

    public function restoreBackup(string $backup): void
    {
        $backupPath = $this->backupPath($backup);

        if (! File::exists($backupPath)) {
            $this->dispatch('media-toast', type: 'error', message: 'Backup file not found.');
            return;
        }

        [, $config] = $this->databaseConfig();

        if (! $config) {
            $this->dispatch('media-toast', type: 'error', message: 'Database connection is not configured.');
            return;
        }

        $process = $this->buildRestoreProcess($config, $backupPath);

        if (! $process) {
            return;
        }

        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->dispatch(
                'media-toast',
                type: 'error',
                message: 'Restore failed. ' . trim($process->getErrorOutput())
            );
            return;
        }

        $this->dispatch('media-toast', type: 'success', message: 'Backup restored successfully.');
    }

    public function downloadBackup(string $backup)
    {
        $backupPath = $this->backupPath($backup);

        if (! File::exists($backupPath)) {
            $this->dispatch('media-toast', type: 'error', message: 'Backup file not found.');
            return null;
        }

        return response()->download($backupPath);
    }

    public function deleteBackup(string $backup): void
    {
        $backupPath = $this->backupPath($backup);

        if (! File::exists($backupPath)) {
            $this->dispatch('media-toast', type: 'error', message: 'Backup file not found.');
            return;
        }

        File::delete($backupPath);
        $this->dispatch('media-toast', type: 'success', message: 'Backup deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings.backups', [
            'backups' => $this->listBackups(),
        ]);
    }

    private function backupDirectory(): string
    {
        return storage_path('app' . DIRECTORY_SEPARATOR . $this->backupFolder);
    }

    private function backupPath(string $backup): string
    {
        return $this->backupDirectory() . DIRECTORY_SEPARATOR . basename($backup);
    }

    private function listBackups(): array
    {
        if (! File::exists($this->backupDirectory())) {
            return [];
        }

        $files = File::files($this->backupDirectory());

        return collect($files)
            ->filter(fn (\SplFileInfo $file) => $file->isFile() && str_ends_with($file->getFilename(), '.sql'))
            ->map(function (\SplFileInfo $file) {
                return [
                    'name' => $file->getFilename(),
                    'description' => 'Database dump',
                    'size' => $this->readableSize($file->getSize()),
                    'created_at' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
                ];
            })
            ->sortByDesc('created_at')
            ->values()
            ->all();
    }

    private function readableSize(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = 0;

        while ($size >= 1024 && $index < count($units) - 1) {
            $size /= 1024;
            $index++;
        }

        return sprintf('%.2f %s', $size, $units[$index]);
    }

    private function databaseConfig(): array
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        return [$connection, $config];
    }

    private function buildBackupProcess(array $config, string $fullPath): ?Process
    {
        $driver = $config['driver'] ?? '';

        if ($driver === 'mysql') {
            if (! $this->ensureBinaryAvailable('mysqldump', 'MySQL dump')) {
                return null;
            }

            $process = new Process([
                'mysqldump',
                '--user=' . ($config['username'] ?? ''),
                '--host=' . ($config['host'] ?? '127.0.0.1'),
                '--port=' . ($config['port'] ?? 3306),
                '--databases',
                $config['database'] ?? '',
                '--result-file=' . $fullPath,
            ]);

            if (! empty($config['password'])) {
                $process->setEnv(['MYSQL_PWD' => $config['password']]);
            }

            return $process;
        }

        if ($driver === 'pgsql') {
            if (! $this->ensureBinaryAvailable('pg_dump', 'PostgreSQL dump')) {
                return null;
            }

            $process = new Process([
                'pg_dump',
                '--file',
                $fullPath,
                '--dbname',
                $config['database'] ?? '',
                '--host',
                $config['host'] ?? '127.0.0.1',
                '--port',
                (string) ($config['port'] ?? 5432),
                '--username',
                $config['username'] ?? '',
            ]);

            if (! empty($config['password'])) {
                $process->setEnv(['PGPASSWORD' => $config['password']]);
            }

            return $process;
        }

        if ($driver === 'sqlite') {
            if (! $this->ensureBinaryAvailable('sqlite3', 'SQLite dump')) {
                return null;
            }

            $database = $config['database'] ?? '';

            if ($database === ':memory:' || empty($database)) {
                $this->dispatch('media-toast', type: 'error', message: 'SQLite in-memory databases cannot be backed up.');
                return null;
            }

            return new Process(['sqlite3', $database, '.dump']);
        }

        $this->dispatch('media-toast', type: 'error', message: 'Unsupported database driver for backup.');

        return null;
    }

    private function buildRestoreProcess(array $config, string $backupPath): ?Process
    {
        $driver = $config['driver'] ?? '';

        if ($driver === 'mysql') {
            if (! $this->ensureBinaryAvailable('mysql', 'MySQL restore')) {
                return null;
            }

            $process = new Process([
                'mysql',
                '--user=' . ($config['username'] ?? ''),
                '--host=' . ($config['host'] ?? '127.0.0.1'),
                '--port=' . ($config['port'] ?? 3306),
                $config['database'] ?? '',
            ]);

            if (! empty($config['password'])) {
                $process->setEnv(['MYSQL_PWD' => $config['password']]);
            }

            $process->setInput(File::get($backupPath));
            return $process;
        }

        if ($driver === 'pgsql') {
            if (! $this->ensureBinaryAvailable('psql', 'PostgreSQL restore')) {
                return null;
            }

            $process = new Process([
                'psql',
                '--dbname',
                $config['database'] ?? '',
                '--host',
                $config['host'] ?? '127.0.0.1',
                '--port',
                (string) ($config['port'] ?? 5432),
                '--username',
                $config['username'] ?? '',
            ]);

            if (! empty($config['password'])) {
                $process->setEnv(['PGPASSWORD' => $config['password']]);
            }

            $process->setInput(File::get($backupPath));
            return $process;
        }

        if ($driver === 'sqlite') {
            if (! $this->ensureBinaryAvailable('sqlite3', 'SQLite restore')) {
                return null;
            }

            $database = $config['database'] ?? '';

            if ($database === ':memory:' || empty($database)) {
                $this->dispatch('media-toast', type: 'error', message: 'SQLite in-memory databases cannot be restored.');
                return null;
            }

            $process = new Process(['sqlite3', $database]);
            $process->setInput(File::get($backupPath));
            return $process;
        }

        $this->dispatch('media-toast', type: 'error', message: 'Unsupported database driver for restore.');

        return null;
    }

    private function ensureBinaryAvailable(string $binary, string $label): bool
    {
        $process = new Process(['which', $binary]);
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        $this->dispatch(
            'media-toast',
            type: 'error',
            message: "{$label} command is not available on this server."
        );

        return false;
    }
}
