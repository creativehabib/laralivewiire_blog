<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class Homepage extends Component
{
//    public function render()
//    {
//        return view('livewire.frontend.homepage');
//    }

    public function render()
    {
        return view('livewire.frontend.homepage')
            ->layout('components.layouts.frontend.app', ['title' => 'বাংলাদেশী নিউজ পোর্টাল - হোম']);
    }
}
