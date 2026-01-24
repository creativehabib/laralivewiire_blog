<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('formats the gregorian date using the configured timezone', function () {
    config()->set('app.timezone', 'Asia/Dhaka');

    $utcDate = Carbon::create(2025, 12, 17, 20, 0, 0, 'UTC');

    $gregorian = frontend_bangla_gregorian_date($utcDate);
    $formatted = frontend_bangla_date($utcDate);

    expect($gregorian)
        ->toContain('১৮ ডিসেম্বর ২০২৫')
        ->not()->toContain('১৭ ডিসেম্বর ২০২৫');

    expect($formatted)->toContain($gregorian);
});

it('formats the bangla calendar date using the configured timezone', function () {
    config()->set('app.timezone', 'Asia/Dhaka');

    $utcDate = Carbon::create(2025, 12, 17, 20, 0, 0, 'UTC');

    $banglaCalendarDate = frontend_bangla_calendar_date($utcDate);

    expect($banglaCalendarDate)->not()->toBe('');
});

it('returns the bangla day digits for the configured timezone', function () {
    config()->set('app.timezone', 'Asia/Dhaka');

    $utcDate = Carbon::create(2025, 12, 17, 20, 0, 0, 'UTC');

    $day = frontend_bangla_day($utcDate);

    expect($day)->toBe('১৮');
});
