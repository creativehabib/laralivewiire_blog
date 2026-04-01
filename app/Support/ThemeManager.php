<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class ThemeManager
{
    public static function activeTheme(): string
    {
        return (string) setting('active_theme', config('themes.default', 'default'));
    }

    public static function activate(string $theme): void
    {
        if (! self::exists($theme)) {
            throw new RuntimeException("Theme [{$theme}] not found.");
        }

        set_setting('active_theme', $theme, 'theme-options');
        self::flushViewFinderCache();
    }

    public static function deactivate(string $theme): void
    {
        if (self::activeTheme() !== $theme) {
            return;
        }

        set_setting('active_theme', config('themes.default', 'default'), 'theme-options');
        self::flushViewFinderCache();
    }

    public static function exists(string $theme): bool
    {
        return File::isDirectory(self::themePath($theme));
    }

    public static function themePath(string $theme): string
    {
        return resource_path('views/themes/'.$theme);
    }

    public static function all(): array
    {
        $themesRoot = resource_path('views/themes');

        if (! File::isDirectory($themesRoot)) {
            return [];
        }

        return collect(File::directories($themesRoot))
            ->map(function (string $path) {
                $slug = basename($path);
                $meta = self::metadata($slug);

                return [
                    'slug' => $slug,
                    'name' => $meta['name'] ?? Str::headline($slug),
                    'version' => $meta['version'] ?? null,
                    'author' => $meta['author'] ?? null,
                    'description' => $meta['description'] ?? null,
                    'active' => self::activeTheme() === $slug,
                ];
            })
            ->values()
            ->all();
    }

    public static function delete(string $theme): void
    {
        if (! self::exists($theme)) {
            throw new RuntimeException("Theme [{$theme}] not found.");
        }

        if ($theme === config('themes.default', 'default')) {
            throw new RuntimeException('Default theme cannot be deleted.');
        }

        if (self::activeTheme() === $theme) {
            throw new RuntimeException('Active theme cannot be deleted. Deactivate first.');
        }

        File::deleteDirectory(self::themePath($theme));
    }

    public static function metadata(string $theme): array
    {
        $manifest = self::themePath($theme).'/theme.json';

        if (! File::exists($manifest)) {
            return [];
        }

        $contents = File::get($manifest);
        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }


    public static function resolveLayout(string $layout): string
    {
        $activeTheme = self::activeTheme();
        $themedLayout = "themes.{$activeTheme}.layouts.frontend.{$layout}";

        return view()->exists($themedLayout) ? $themedLayout : 'components.layouts.frontend.'.$layout;
    }

    public static function resolveView(string $view): string
    {
        $activeTheme = self::activeTheme();
        $themedView = "themes.{$activeTheme}.{$view}";

        return view()->exists($themedView) ? $themedView : $view;
    }

    public static function installFromZipPath(string $zipPath): string
    {
        $zip = new ZipArchive();
        $opened = $zip->open($zipPath);

        if ($opened !== true) {
            throw new RuntimeException('Could not open theme ZIP archive.');
        }

        $extractBase = storage_path('app/tmp/theme-install-'.Str::uuid());
        File::ensureDirectoryExists($extractBase);

        if (! $zip->extractTo($extractBase)) {
            $zip->close();
            File::deleteDirectory($extractBase);
            throw new RuntimeException('Could not extract theme ZIP archive.');
        }

        $zip->close();

        $themeDirectory = self::detectThemeDirectory($extractBase);
        $themeSlug = self::resolveThemeSlug($themeDirectory);
        $destination = self::themePath($themeSlug);

        File::ensureDirectoryExists(dirname($destination));
        File::deleteDirectory($destination);
        File::copyDirectory($themeDirectory, $destination);
        File::deleteDirectory($extractBase);

        return $themeSlug;
    }

    public static function installFromUpload(UploadedFile $file): string
    {
        return self::installFromZipPath($file->getRealPath());
    }

    protected static function detectThemeDirectory(string $extractBase): string
    {
        $directories = File::directories($extractBase);

        foreach ($directories as $directory) {
            if (File::exists($directory.'/theme.json')) {
                return $directory;
            }
        }

        if (File::exists($extractBase.'/theme.json')) {
            return $extractBase;
        }

        throw new RuntimeException('theme.json not found. Invalid theme package.');
    }

    protected static function flushViewFinderCache(): void
    {
        $finder = app('view.finder');

        if (method_exists($finder, 'flush')) {
            $finder->flush();
        }
    }

    protected static function resolveThemeSlug(string $themeDirectory): string
    {
        $manifestPath = $themeDirectory.'/theme.json';
        $raw = [];

        if (File::exists($manifestPath)) {
            $decoded = json_decode(File::get($manifestPath), true);
            $raw = is_array($decoded) ? $decoded : [];
        }

        $candidate = $raw['slug'] ?? basename($themeDirectory);
        $slug = Str::slug((string) $candidate);

        if ($slug === '') {
            throw new RuntimeException('Invalid theme slug. Provide a valid "slug" in theme.json.');
        }

        return $slug;
    }
}
