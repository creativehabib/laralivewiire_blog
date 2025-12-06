<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class CacheManagement extends Component
{
    public string $cacheSize = '0 B';

    public function clearAllCache(): void
    {
        Artisan::call('optimize:clear');
        Cache::flush();

        $this->refreshCacheStatistics();

        $this->dispatch('media-toast', type: 'success', message: 'All CMS caches cleared successfully');
    }

    public function cacheViews(): void
    {
        Artisan::call('view:cache');

        $this->refreshCacheStatistics();

        $this->dispatch('media-toast', type: 'success', message: 'View cache generated successfully');
    }

    public function clearCompiledViews(): void
    {
        Artisan::call('view:clear');

        $this->refreshCacheStatistics();

        $this->dispatch('media-toast', type: 'success', message: 'Compiled views refreshed successfully');
    }

    public function clearOptimizationCaches(): void
    {
        Artisan::call('optimize:clear');

        $this->refreshCacheStatistics();

        $this->dispatch('media-toast', type: 'success', message: 'Optimization caches cleared successfully');
    }

    public function clearConfigCache(): void
    {
        Artisan::call('config:clear');

        $this->refreshCacheStatistics();
        $this->dispatch('media-toast', type: 'success', message: 'Configuration cache refreshed successfully');
    }

    public function clearRouteCache(): void
    {
        Artisan::call('route:clear');

        $this->refreshCacheStatistics();
        $this->dispatch('media-toast', type: 'success', message: 'Route cache cleared successfully');
    }

    public function clearLogFiles(): void
    {
        $logPath = storage_path('logs');

        if (File::exists($logPath)) {
            collect(File::files($logPath))->each(static function ($file) {
                /** @var \SplFileInfo $file */
                File::delete($file->getPathname());
            });
        }

        $this->refreshCacheStatistics();

        $this->dispatch('media-toast', type: 'success', message: 'System log files cleared successfully');
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
        return view('livewire.admin.cache-management');
    }
}
