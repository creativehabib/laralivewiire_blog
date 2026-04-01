<?php

namespace App\Livewire\Admin\Settings\Theme;

use App\Support\ThemeManager;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ThemesManager extends Component
{
    use WithFileUploads;

    public $themeZip;

    public function mount(): void
    {
        abort_unless(Gate::allows('setting.view'), 403);
    }

    public function installTheme(): void
    {
        $this->validate([
            'themeZip' => ['required', 'file', 'mimes:zip'],
        ]);

        $slug = ThemeManager::installFromUpload($this->themeZip);

        $this->themeZip = null;
        $this->dispatch('media-toast', type: 'success', message: "Theme [{$slug}] installed successfully.");
    }

    public function activateTheme(string $theme): void
    {
        ThemeManager::activate($theme);

        $this->dispatch('media-toast', type: 'success', message: "Theme [{$theme}] activated.");
    }

    public function deactivateTheme(string $theme): void
    {
        ThemeManager::deactivate($theme);

        $this->dispatch('media-toast', type: 'success', message: "Theme [{$theme}] deactivated.");
    }

    public function deleteTheme(string $theme): void
    {
        ThemeManager::delete($theme);

        $this->dispatch('media-toast', type: 'success', message: "Theme [{$theme}] deleted.");
    }

    public function getThemesProperty(): array
    {
        return ThemeManager::all();
    }

    public function render()
    {
        return view('livewire.admin.settings.theme.themes-manager', [
            'themes' => $this->themes,
            'defaultTheme' => config('themes.default', 'default'),
            'activeTheme' => ThemeManager::activeTheme(),
        ])->layout('components.layouts.app', ['title' => 'Themes']);
    }
}
