<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class CustomHtmlSettings extends Component
{
    public $custom_header_html;
    public $custom_body_html;
    public $custom_footer_html;

    public function mount()
    {
        $this->custom_header_html = setting('custom_header_html');
        $this->custom_body_html = setting('custom_body_html');
        $this->custom_footer_html = setting('custom_footer_html');
    }

    public function save()
    {
        set_setting('custom_header_html', $this->custom_header_html, 'appearance');
        set_setting('custom_body_html', $this->custom_body_html, 'appearance');
        set_setting('custom_footer_html', $this->custom_footer_html, 'appearance');

        \Cache::forget('general_settings');

        $this->dispatch('media-toast', type: 'success', message: 'Custom HTML updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.custom-html-settings')
            ->layout('components.layouts.app', ['title' => 'Custom HTML']);
    }
}
