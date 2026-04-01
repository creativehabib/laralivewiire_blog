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
                $name = basename($path);
                $meta = self::metadata($name);

                return [
                    'slug' => $name,
                    'name' => $meta['name'] ?? Str::headline($name),
                    'version' => $meta['version'] ?? null,
                    'author' => $meta['author'] ?? null,
                    'description' => $meta['description'] ?? null,
                    'active' => self::activeTheme() === $name,
                ];
            })
            ->values()
            ->all();
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
        $themeSlug = basename($themeDirectory);
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

        if ($directories === []) {
            throw new RuntimeException('Theme ZIP is empty.');
        }

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
}
