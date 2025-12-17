<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('formats the gregorian date using the configured timezone', function () {
    config()->set('app.timezone', 'Asia/Dhaka');

    $utcDate = Carbon::create(2025, 12, 17, 20, 0, 0, 'UTC');

    $formatted = frontend_bangla_date($utcDate);

    expect($formatted)
        ->toContain('১৮ ডিসেম্বর ২০২৫')
        ->not()->toContain('১৭ ডিসেম্বর ২০২৫');
});
