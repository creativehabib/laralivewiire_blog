<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class CustomJsSettings extends Component
{
    public $custom_header_js;
    public $custom_body_js;
    public $custom_footer_js;

    public function mount()
    {
        // ডাটাবেজ থেকে JS লোড করা
        $this->custom_header_js = setting('custom_header_js');
        $this->custom_body_js = setting('custom_body_js');
        $this->custom_footer_js = setting('custom_footer_js');
    }

    public function save()
    {
        // ডাটাবেজে সেভ করা
        set_setting('custom_header_js', $this->custom_header_js, 'appearance');
        set_setting('custom_body_js', $this->custom_body_js, 'appearance');
        set_setting('custom_footer_js', $this->custom_footer_js, 'appearance');

        // ক্যাশ ক্লিয়ার
        \Cache::forget('general_settings');

        $this->dispatch('media-toast', type: 'success', message: 'Custom JS updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.custom-js-settings')
            ->layout('components.layouts.app', ['title' => 'Custom JS']);
    }
}
