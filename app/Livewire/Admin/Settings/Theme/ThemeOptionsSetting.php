<?php

namespace App\Livewire\Admin\Settings\Theme;

use Livewire\Component;

class ThemeOptionsSetting extends Component
{
    public function render()
    {
        return view('livewire.admin.settings.theme.theme-options-setting')->layout('components.layouts.app', [
            'title' => 'Theme Options',
        ]);
    }
}
