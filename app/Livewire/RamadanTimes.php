<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class RamadanTimes extends Component
{
    public $selectedDivision = 'Dhaka';
    public $times = null;

    public $divisions = [
        'Dhaka' => 'ঢাকা',
        'Chattogram' => 'চট্টগ্রাম',
        'Rajshahi' => 'রাজশাহী',
        'Khulna' => 'খুলনা',
        'Barishal' => 'বরিশাল',
        'Sylhet' => 'সিলেট',
        'Rangpur' => 'রংপুর',
        'Mymensingh' => 'ময়মনসিংহ',
    ];

    public function mount()
    {
        $this->fetchTimes();
    }

    public function updatedSelectedDivision()
    {
        $this->fetchTimes();
    }

    public function fetchTimes()
    {
        $date = now('Asia/Dhaka')->format('d-m-Y');
        $cacheKey = "ramadan_times_{$this->selectedDivision}_{$date}";

        $this->times = Cache::remember($cacheKey, 3600, function () use ($date) {
            $response = Http::get('https://api.aladhan.com/v1/timingsByCity/' . $date, [
                'city' => $this->selectedDivision,
                'country' => 'Bangladesh',
                'method' => 1,
            ]);

            return $response->json('data');
        });
    }

    public function render()
    {
        return view('livewire.ramadan-times');
    }
}
