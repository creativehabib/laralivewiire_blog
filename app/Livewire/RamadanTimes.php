<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RamadanTimes extends Component
{
    public string $selectedDivision = 'Dhaka';
    public ?array $times = null;
    public bool $loading = true;

    public array $divisions = [
        'Dhaka' => 'ঢাকা',
        'Chattogram' => 'চট্টগ্রাম',
        'Rajshahi' => 'রাজশাহী',
        'Khulna' => 'খুলনা',
        'Barishal' => 'বরিশাল',
        'Sylhet' => 'সিলেট',
        'Rangpur' => 'রংপুর',
        'Mymensingh' => 'ময়মনসিংহ',
    ];

    public function loadTimes(): void
    {
        $this->loading = true;

        $date = now('Asia/Dhaka')->format('d-m-Y');
        $cacheKey = "ramadan_times_{$this->selectedDivision}_{$date}";

        $this->times = Cache::remember($cacheKey, now()->addHour(), function () use ($date) {
            $response = Http::timeout(10)->get('https://api.aladhan.com/v1/timingsByCity/' . $date, [
                'city' => $this->selectedDivision,
                'country' => 'Bangladesh',
                'method' => 13,
            ]);

            if (! $response->successful()) {
                return null;
            }

            return $response->json('data');
        });

        $this->loading = false;
    }

    public function updatedSelectedDivision(): void
    {
        $this->loadTimes();
    }

    public function render()
    {
        return view('livewire.ramadan-times');
    }
}
