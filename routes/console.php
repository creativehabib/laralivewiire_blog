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
