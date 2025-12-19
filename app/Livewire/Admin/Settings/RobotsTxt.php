<?php

namespace App\Livewire\Admin\Settings;

use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class RobotsTxt extends Component
{
    use WithFileUploads;

    public string $robotsContent = '';

    public $uploadedFile;

    public function mount(): void
    {
        $this->robotsContent = $this->loadRobotsContent();
    }

    public function updatedUploadedFile(): void
    {
        $this->validate([
            'uploadedFile' => ['file', 'mimes:txt', 'max:1024'],
        ]);

        $this->robotsContent = File::get($this->uploadedFile->getRealPath());
    }

    public function save(): void
    {
        $this->validate([
            'robotsContent' => ['required', 'string'],
            'uploadedFile' => ['nullable', 'file', 'mimes:txt', 'max:1024'],
        ]);

        $content = $this->robotsContent;

        if ($this->uploadedFile) {
            $content = File::get($this->uploadedFile->getRealPath());
            $this->robotsContent = $content;
        }

        File::put($this->robotsFilePath(), $content);

        $this->uploadedFile = null;

        $this->dispatch('media-toast', type: 'success', message: 'robots.txt updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.robots-txt', [
            'robotsUrl' => url('robots.txt'),
        ])->layout('components.layouts.app', [
            'title' => 'robots.txt - Settings',
        ]);
    }

    private function loadRobotsContent(): string
    {
        if (File::exists($this->robotsFilePath())) {
            return File::get($this->robotsFilePath());
        }

        return "User-agent: *\nAllow: /\nSitemap: " . url('sitemap.xml');
    }

    private function robotsFilePath(): string
    {
        return public_path('robots.txt');
    }
}
