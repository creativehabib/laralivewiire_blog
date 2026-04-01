<?php

use App\Services\WordPressImporter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('wordpress:import
    {source=https://ebdresults.com : WordPress site URL}
    {--per-page=50 : API per_page value (max 100)}
    {--max-pages=0 : 0 means all pages}
    {--import-media=1 : 1 হলে image download হবে}
    {--author-id= : Local author user id}
    {--status=published : Imported post status}', function (WordPressImporter $importer) {
    $this->info('WordPress import started...');

    $summary = $importer->import((string) $this->argument('source'), [
        'per_page' => (int) $this->option('per-page'),
        'max_pages' => (int) $this->option('max-pages'),
        'import_media' => (string) $this->option('import-media') === '1',
        'author_id' => $this->option('author-id'),
        'default_status' => (string) $this->option('status'),
    ]);

    $this->newLine();
    $this->table(['Type', 'Count'], [
        ['Categories', $summary['categories']],
        ['Tags', $summary['tags']],
        ['Posts', $summary['posts']],
        ['Media Downloaded', $summary['media_downloaded']],
    ]);

    $this->info('Import finished.');
})->purpose('Import posts, categories, tags and media from a WordPress site');

use App\Support\ThemeManager;

Artisan::command('theme:list', function () {
    $themes = ThemeManager::all();

    if ($themes === []) {
        $this->warn('No themes found in resources/views/themes');
        return;
    }

    $this->table(['Slug', 'Name', 'Version', 'Author', 'Active'], collect($themes)->map(fn (array $theme) => [
        $theme['slug'],
        $theme['name'],
        $theme['version'] ?? '-',
        $theme['author'] ?? '-',
        $theme['active'] ? 'yes' : 'no',
    ]));
})->purpose('Show installed themes');

Artisan::command('theme:activate {theme : Theme slug}', function (string $theme) {
    ThemeManager::activate($theme);
    $this->info("Theme [{$theme}] activated successfully.");
})->purpose('Activate a theme');

Artisan::command('theme:install {zip : Absolute/relative path to a zip file}', function (string $zip) {
    $zipPath = base_path($zip);

    if (! str_starts_with($zip, '/') && file_exists($zipPath)) {
        $target = $zipPath;
    } else {
        $target = $zip;
    }

    if (! file_exists($target)) {
        $this->error("ZIP file not found: {$zip}");
        return 1;
    }

    $themeSlug = ThemeManager::installFromZipPath($target);

    $this->info("Theme [{$themeSlug}] installed successfully.");
    $this->line("Run <comment>php artisan theme:activate {$themeSlug}</comment> to use it.");

    return 0;
})->purpose('Install a theme from a ZIP package');
