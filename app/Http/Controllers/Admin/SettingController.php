<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function permalinksSetting(){
        return view('backend.pages.settings.permalinks');
    }

    public function generalSettings()
    {
        return view('backend.pages.settings.general');
    }

    public function cacheManagement()
    {
        return view('backend.pages.settings.cacheManagement');
    }

    public function systemInformation(Request $request)
    {
        $connection = config('database.default');
        $connectionConfig = config("database.connections.{$connection}", []);

        $systemEnvironment = [
            'Application Environment' => app()->environment(),
            'App Debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'App URL' => config('app.url'),
            'Timezone' => config('app.timezone'),
            'Locale' => config('app.locale'),
            'Framework Version' => app()->version(),
        ];

        $serverEnvironment = [
            'Server Software' => $request->server('SERVER_SOFTWARE') ?? 'N/A',
            'Server OS' => trim(php_uname('s') . ' ' . php_uname('r')),
            'PHP SAPI' => PHP_SAPI,
            'Host' => $request->getHost(),
            'Server IP' => $request->server('SERVER_ADDR') ?? 'N/A',
        ];

        $databaseVersion = 'N/A';
        try {
            $databaseVersion = DB::selectOne('select version() as version')->version ?? 'N/A';
        } catch (\Throwable $exception) {
            $databaseVersion = 'N/A';
        }

        $databaseCharset = $connectionConfig['charset'] ?? 'N/A';

        $databaseInformation = [
            'Connection' => $connection,
            'Driver' => $connectionConfig['driver'] ?? 'N/A',
            'Host' => $connectionConfig['host'] ?? 'N/A',
            'Port' => $connectionConfig['port'] ?? 'N/A',
            'Database' => $connectionConfig['database'] ?? 'N/A',
            'Database Version' => $databaseVersion,
            'Character Set' => $databaseCharset,
        ];

        $phpConfiguration = [
            'PHP Version' => PHP_VERSION,
            'Memory Limit' => ini_get('memory_limit'),
            'Max Execution Time' => ini_get('max_execution_time') . 's',
            'Upload Max Filesize' => ini_get('upload_max_filesize'),
            'Post Max Size' => ini_get('post_max_size'),
        ];

        return view('backend.pages.settings.system-information', [
            'systemEnvironment' => $systemEnvironment,
            'serverEnvironment' => $serverEnvironment,
            'databaseInformation' => $databaseInformation,
            'phpConfiguration' => $phpConfiguration,
        ]);
    }

    public function sitemapSettings()
    {

    }
}
