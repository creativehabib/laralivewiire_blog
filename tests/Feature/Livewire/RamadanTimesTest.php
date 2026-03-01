<?php

use App\Livewire\RamadanTimes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

it('uses south asia calculation method for bangladesh ramadan times', function () {
    Cache::flush();

    Http::fake([
        'api.aladhan.com/*' => Http::response([
            'data' => [
                'timings' => [
                    'Imsak' => '04:40',
                    'Fajr' => '04:45',
                    'Maghrib' => '18:05',
                ],
                'date' => [
                    'hijri' => [
                        'day' => '10',
                    ],
                ],
            ],
        ], 200),
    ]);

    Livewire::test(RamadanTimes::class)
        ->call('loadTimes');

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'api.aladhan.com/v1/timingsByCity/')
            && $request['country'] === 'Bangladesh'
            && (int) $request['method'] === 1;
    });
});

it('prefers imsak as sehri end time and falls back to fajr', function () {
    $component = new RamadanTimes();

    $component->times = [
        'timings' => [
            'Imsak' => '04:40',
            'Fajr' => '04:45',
        ],
    ];

    expect($component->sehriTime())->toBe('04:40');

    $component->times = [
        'timings' => [
            'Fajr' => '04:45',
        ],
    ];

    expect($component->sehriTime())->toBe('04:45');
});
