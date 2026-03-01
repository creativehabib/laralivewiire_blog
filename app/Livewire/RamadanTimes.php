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
    public int $hijriAdjustment = -1;
    public int $calculationMethod = 1;

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

    private array $apiCityMap = [
        'Dhaka' => 'Dhaka',
        'Chattogram' => 'Chittagong',
        'Rajshahi' => 'Rajshahi',
        'Khulna' => 'Khulna',
        'Barishal' => 'Barisal',
        'Sylhet' => 'Sylhet',
        'Rangpur' => 'Rangpur',
        'Mymensingh' => 'Mymensingh',
    ];

    public function loadTimes(): void
    {
        $this->loading = true;

        $date = now('Asia/Dhaka')->format('d-m-Y');
        $cacheKey = "ramadan_times_v2_{$this->selectedDivision}_{$date}";
        $lastGoodKey = "ramadan_times_v2_{$this->selectedDivision}";
        $apiCity = $this->apiCityMap[$this->selectedDivision] ?? $this->selectedDivision;

        $cachedTimes = Cache::get($cacheKey);
        if (is_array($cachedTimes)) {
            $this->times = $cachedTimes;
            $this->loading = false;

            return;
        }

        try {
            $response = Http::timeout(10)->retry(2, 300)->get('https://api.aladhan.com/v1/timingsByCity/' . $date, [
                'city' => $apiCity,
                'country' => 'Bangladesh',
                // Bangladesh follows Hanafi jurisprudence and Islamic Foundation schedules
                // are typically aligned with the South-Asia (Karachi) method.
                'method' => $this->calculationMethod,
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


    public function toBanglaNumber(string|int $value): string
    {
        return strtr((string) $value, [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯',
        ]);
    }

    public function formatTime(?string $time24): string
    {
        if (! $time24) {
            return '--:--';
        }

        $time = trim(explode(' ', $time24)[0]);

        if (! preg_match('/^(\d{1,2}):(\d{2})$/', $time, $matches)) {
            return '--:--';
        }

        $hours = (int) $matches[1];
        $minutes = $matches[2];
        $hour12 = $hours % 12 ?: 12;
        $period = $hours >= 12 ? 'PM' : 'AM';

        return $this->toBanglaNumber(str_pad((string) $hour12, 2, '0', STR_PAD_LEFT) . ':' . $minutes);
    }

    public function ramadanDay(): string
    {
        $day = (int) data_get($this->times, 'date.hijri.day', 0);

        if ($day <= 0) {
            return '--';
        }

        $adjusted = $day + $this->hijriAdjustment;

        return $adjusted > 0 ? $this->toBanglaNumber($adjusted) : '--';
    }

    public function render()
    {
        return view('livewire.ramadan-times');
    }
}
