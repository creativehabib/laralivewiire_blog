<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Spatie\Activitylog\Models\Activity;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('activity-logs:prune', function () {
    $days = (int) setting('activity_logs_retention_days', 15);

    if ($days <= 0) {
        $this->info('Activity log retention disabled.');
        return;
    }

    $deleted = Activity::where('created_at', '<', now()->subDays($days))->delete();

    $this->info("Deleted {$deleted} activity logs older than {$days} days.");
})->purpose('Prune activity logs based on retention settings');

app()->booted(function () {
    $schedule = app(Schedule::class);
    $schedule->command('activity-logs:prune')->daily();
});
