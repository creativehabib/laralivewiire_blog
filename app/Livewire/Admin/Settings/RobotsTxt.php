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

    /**
     * ফাইল আপলোড হওয়ার সাথে সাথে এই ফাংশনটি কল হবে।
     * এটি ফাইলের কন্টেন্ট পড়ে এডিটরে পাঠিয়ে দিবে।
     */
    public function updatedUploadedFile(): void
    {
        $this->validate([
            'uploadedFile' => ['required', 'file', 'mimes:txt', 'max:100'], // max 100KB
        ]);

        // ১. আপলোড করা ফাইলের কন্টেন্ট পড়া
        $content = File::get($this->uploadedFile->getRealPath());

        // ২. PHP ভেরিয়েবল আপডেট করা
        $this->robotsContent = $content;

        // ৩. [গুরুত্বপূর্ণ] CodeMirror এডিটর আপডেট করার জন্য ইভেন্ট পাঠানো
        $this->dispatch('robots-content-updated', content: $this->robotsContent);

        // ৪. ইউজারকে জানানো
        $this->dispatch('media-toast', type: 'info', message: 'File content loaded into editor.');
    }

    public function save(): void
    {
        $this->validate([
            'robotsContent' => ['required', 'string'],
        ]);

        // public/robots.txt ফাইলে কন্টেন্ট রাইট করা
        File::put($this->robotsFilePath(), $this->robotsContent);

        // আপলোড ফাইল ভেরিয়েবল রিসেট করা
        $this->uploadedFile = null;

        $this->dispatch('media-toast', type: 'success', message: 'robots.txt updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.robots-txt', [
            'robotsUrl' => url('robots.txt'),
        ])->layout('components.layouts.app', [
            'title' => 'Robots.txt Settings',
        ]);
    }

    private function loadRobotsContent(): string
    {
        if (File::exists($this->robotsFilePath())) {
            return File::get($this->robotsFilePath());
        }

        // ডিফল্ট কন্টেন্ট যদি ফাইল না থাকে
        return "User-agent: *\nAllow: /\nSitemap: " . url('sitemap.xml');
    }

    private function robotsFilePath(): string
    {
        return public_path('robots.txt');
    }
}
