<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\GeneralSetting as GeneralSettingModel;

class GeneralSetting extends Component
{
    public array $availableRoles = [];
    public string $cacheSize = '0 B';
    //General settings form properties
    public $site_title,
        $site_email,
        $site_description,
        $site_phone,
        $site_meta_keywords,
        $site_meta_description,
        $site_logo,
        $site_favicon,
        $site_copyright;


    public function mount(Request $request)
    {
        // Populate General Settings
        $settings = GeneralSettingModel::query()->first();
        if (! is_null($settings)) {
            $this->site_title           = $settings->site_title;
            $this->site_email           = $settings->site_email;
            $this->site_description     = $settings->site_description;
            $this->site_phone           = $settings->site_phone;
            $this->site_meta_keywords   = $settings->site_meta_keywords;
            $this->site_meta_description= $settings->site_meta_description;
            $this->site_logo            = $settings->site_logo;
            $this->site_favicon         = $settings->site_favicon;
            $this->site_copyright       = $settings->site_copyright;
        }

        $this->availableRoles = Role::query()->pluck('name')->sort()->values()->toArray();
        $this->availableRoles = array_unique($this->availableRoles);

        $this->refreshCacheStatistics();
    }
    public function updateSiteInfo()
    {
        $this->validate([
            'site_title'           => 'required|string|max:255',
            'site_email'           => 'required|email|max:255',
            'site_description'     => 'nullable|string|max:500',
            'site_phone'           => 'nullable|string|max:50',
            'site_meta_keywords'   => 'nullable|string|max:255',
            'site_meta_description'=> 'nullable|string|max:500',
            'site_copyright'       => 'nullable|string|max:255',
        ]);

        $settings = GeneralSettingModel::first();

        $data = [
            'site_title'            => $this->site_title,
            'site_email'            => $this->site_email,
            'site_description'      => $this->site_description,
            'site_phone'            => $this->site_phone,
            'site_meta_keywords'    => $this->site_meta_keywords,
            'site_meta_description' => $this->site_meta_description,
            'site_copyright'        => $this->site_copyright,
        ];

        $query = $settings
            ? $settings->update($data)
            : GeneralSettingModel::create($data);

        if ($query) {
            Cache::forget('general_settings');
            $this->dispatch('media-toast', type: 'success', message: 'General Setting Updated Successfully.');
        } else {
            $this->dispatch('media-toast', type: 'error', message: 'General Setting Not Updated');
        }
    }


    public function updateBranding()
    {
        // Media Manager থেকে URL/Path আসবে, তাই string validation
        $this->validate([
            'site_logo'    => 'nullable|string|max:500',
            'site_favicon' => 'nullable|string|max:500',
        ], [
            'site_logo.string'    => 'Site logo must be a valid path/URL string.',
            'site_favicon.string' => 'Site favicon must be a valid path/URL string.',
        ]);

        $settings = GeneralSettingModel::first();

        if (! $settings) {
            $settings = GeneralSettingModel::create([]);
        }

        $settings->update([
            'site_logo'    => $this->site_logo,
            'site_favicon' => $this->site_favicon,
        ]);

        Cache::forget('general_settings');

        $this->dispatch('media-toast', type: 'success', message: 'Branding Updated Successfully.');
    }

    protected function refreshCacheStatistics(): void
    {
        $this->cacheSize = $this->calculateCacheSize();
    }

    protected function calculateCacheSize(): string
    {
        $paths = [
            storage_path('framework/cache'),
            storage_path('framework/views'),
            base_path('bootstrap/cache'),
            storage_path('logs'),
        ];

        $total = 0;

        foreach ($paths as $path) {
            $total += $this->directorySize($path);
        }

        return $this->formatBytes($total);
    }

    protected function directorySize(string $path): int
    {
        if (! File::exists($path)) {
            return 0;
        }

        return collect(File::allFiles($path))->sum(static function ($file) {
            /** @var \SplFileInfo $file */
            return $file->getSize();
        });
    }
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        $value = $bytes / (1024 ** $power);

        return number_format($value, $precision) . ' ' . $units[$power];
    }
    public function render()
    {
        return view('livewire.admin.general-setting');
    }
}
