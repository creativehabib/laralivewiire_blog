<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallController extends Controller
{
    public function index()
    {
        return view('install.welcome', [
            'step' => 'welcome',
        ]);
    }

    public function requirements()
    {
        $requirements = [
            'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'BCMath' => extension_loaded('bcmath'),
            'Ctype' => extension_loaded('ctype'),
            'Fileinfo' => extension_loaded('fileinfo'),
            'JSON' => extension_loaded('json'),
            'Mbstring' => extension_loaded('mbstring'),
            'OpenSSL' => extension_loaded('openssl'),
            'PDO' => extension_loaded('pdo'),
            'Tokenizer' => extension_loaded('tokenizer'),
            'XML' => extension_loaded('xml'),
        ];

        return view('install.requirements', [
            'step' => 'requirements',
            'requirements' => $requirements,
        ]);
    }

    public function permissions()
    {
        $paths = [
            storage_path() => is_writable(storage_path()),
            base_path('bootstrap/cache') => is_writable(base_path('bootstrap/cache')),
        ];

        return view('install.permissions', [
            'step' => 'permissions',
            'paths' => $paths,
        ]);
    }

    public function environment()
    {
        return view('install.environment', [
            'step' => 'environment',
            'defaults' => [
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'db_connection' => config('database.default'),
                'db_host' => config('database.connections.mysql.host'),
                'db_port' => config('database.connections.mysql.port'),
                'db_database' => config('database.connections.mysql.database'),
                'db_username' => config('database.connections.mysql.username'),
                'db_password' => config('database.connections.mysql.password'),
            ],
        ]);
    }

    public function saveEnvironment(Request $request)
    {
        $data = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_url' => ['required', 'url'],
            'db_connection' => ['required', 'string', 'max:50'],
            'db_host' => ['required', 'string', 'max:255'],
            'db_port' => ['required', 'string', 'max:10'],
            'db_database' => ['required', 'string', 'max:255'],
            'db_username' => ['required', 'string', 'max:255'],
            'db_password' => ['nullable', 'string', 'max:255'],
        ]);

        $updates = [
            'APP_NAME' => '"' . $data['app_name'] . '"',
            'APP_URL' => $data['app_url'],
            'DB_CONNECTION' => $data['db_connection'],
            'DB_HOST' => $data['db_host'],
            'DB_PORT' => $data['db_port'],
            'DB_DATABASE' => $data['db_database'],
            'DB_USERNAME' => $data['db_username'],
            'DB_PASSWORD' => $data['db_password'] ?? '',
        ];

        $this->updateEnvironmentFile($updates);

        return redirect()->route('install.run');
    }

    public function runInstall()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);

        File::put(storage_path('installed'), now()->toDateTimeString());

        return view('install.final', [
            'step' => 'final',
        ]);
    }

    private function updateEnvironmentFile(array $updates): void
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            File::put($envPath, '');
        }

        $envContents = File::get($envPath);

        foreach ($updates as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $line = $key . '=' . $value;

            if (preg_match($pattern, $envContents)) {
                $envContents = preg_replace($pattern, $line, $envContents);
                continue;
            }

            $envContents = rtrim($envContents) . PHP_EOL . $line . PHP_EOL;
        }

        File::put($envPath, $envContents);

        foreach ($updates as $key => $value) {
            $cleanValue = Str::of($value)->replace('"', '')->toString();
            putenv($key . '=' . $cleanValue);
        }
    }
}
