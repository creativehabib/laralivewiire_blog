<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class CustomCssSettings extends Component
{
    public $custom_css;

    public function mount()
    {
        // ডাটাবেজ থেকে CSS লোড করা
        $this->custom_css = setting('custom_css');
    }

    public function save()
    {
        // ডাটাবেজে সেভ করা
        set_setting('custom_css', $this->custom_css, 'appearance'); // Group: appearance দিলাম

        // ক্যাশ ক্লিয়ার
        \Cache::forget('general_settings');

        $this->dispatch('media-toast', type: 'success', message: 'Custom CSS updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.custom-css-settings')
            ->layout('components.layouts.app', ['title' => 'Custom CSS']);
    }
}
