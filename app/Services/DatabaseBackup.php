<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class DatabaseBackup
{
    public function create(): string
    {
        [$connection, $config] = $this->databaseConfig();
        File::ensureDirectoryExists($this->directory());
        $path = $this->directory().DIRECTORY_SEPARATOR.Str::slug($connection).'-'.now()->format('Ymd_His').'.sql';
        $process = $this->backupProcess($config, $path);
        $process->setTimeout(300)->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException('Backup failed. '.trim($process->getErrorOutput()));
        }
        if (($config['driver'] ?? '') === 'sqlite') {
            File::put($path, $process->getOutput());
        }

        return $path;
    }

    public function restore(string $path): void
    {
        [, $config] = $this->databaseConfig();
        $driver = $config['driver'] ?? '';
        $commands = [
            'mysql' => ['mysql', '--user='.($config['username'] ?? ''), '--host='.($config['host'] ?? '127.0.0.1'), '--port='.($config['port'] ?? 3306), $config['database'] ?? ''],
            'pgsql' => ['psql', '--dbname', $config['database'] ?? '', '--host', $config['host'] ?? '127.0.0.1', '--port', (string) ($config['port'] ?? 5432), '--username', $config['username'] ?? ''],
            'sqlite' => ['sqlite3', $config['database'] ?? ''],
        ];
        if (! isset($commands[$driver])) {
            throw new RuntimeException('Unsupported database driver for restore.');
        }
        $this->requireBinary($commands[$driver][0]);
        $process = new Process($commands[$driver], null, $this->passwordEnvironment($config));
        $process->setInput(File::get($path));
        $process->setTimeout(300)->run();
        if (! $process->isSuccessful()) {
            throw new RuntimeException('Restore failed. '.trim($process->getErrorOutput()));
        }
    }

    public function directory(): string
    {
        return config('backup.directory');
    }

    private function databaseConfig(): array
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");
        if (! is_array($config)) {
            throw new RuntimeException('Database connection is not configured.');
        }
        return [$connection, $config];
    }

    private function backupProcess(array $config, string $path): Process
    {
        $driver = $config['driver'] ?? '';
        if ($driver === 'mysql') {
            $command = ['mysqldump', '--user='.($config['username'] ?? ''), '--host='.($config['host'] ?? '127.0.0.1'), '--port='.($config['port'] ?? 3306), '--databases', $config['database'] ?? '', '--result-file='.$path];
        } elseif ($driver === 'pgsql') {
            $command = ['pg_dump', '--file', $path, '--dbname', $config['database'] ?? '', '--host', $config['host'] ?? '127.0.0.1', '--port', (string) ($config['port'] ?? 5432), '--username', $config['username'] ?? ''];
        } elseif ($driver === 'sqlite') {
            $command = ['sqlite3', $config['database'] ?? '', '.dump'];
        } else {
            throw new RuntimeException('Unsupported database driver for backup.');
        }
        $this->requireBinary($command[0]);
        return new Process($command, null, $this->passwordEnvironment($config));
    }

    private function passwordEnvironment(array $config): array
    {
        return match ($config['driver'] ?? '') {
            'mysql' => ['MYSQL_PWD' => $config['password'] ?? ''],
            'pgsql' => ['PGPASSWORD' => $config['password'] ?? ''],
            default => [],
        };
    }

    private function requireBinary(string $binary): void
    {
        $process = new Process(['which', $binary]);
        $process->run();
        if (! $process->isSuccessful()) {
            throw new RuntimeException("{$binary} command is not available on this server.");
        }
    }
}
