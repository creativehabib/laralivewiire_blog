<?php

namespace App\Livewire;

use Illuminate\Http\Client\ConnectionException;
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
        $lastGoodKey = "ramadan_times_last_good_{$this->selectedDivision}";

        $cachedTimes = Cache::get($cacheKey);
        if (is_array($cachedTimes)) {
            $this->times = $cachedTimes;
            $this->loading = false;

            return;
        }

        try {
            $response = Http::timeout(10)->retry(2, 300)->get('https://api.aladhan.com/v1/timingsByCity/' . $date, [
                'city' => $this->selectedDivision,
                'country' => 'Bangladesh',
                'method' => 13,
            ]);

            if ($response->successful() && is_array($response->json('data'))) {
                $this->times = $response->json('data');

                Cache::put($cacheKey, $this->times, now()->addHour());
                Cache::put($lastGoodKey, $this->times, now()->addDay());
            } else {
                $this->times = Cache::get($lastGoodKey);
            }
        } catch (ConnectionException) {
            $this->times = Cache::get($lastGoodKey);
        }

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
