<?php

namespace App\Livewire\Admin\Settings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;
// use App\Models\GeneralSetting; // এটি আর প্রয়োজন নেই

class SitemapSettings extends Component
{
    // Settings Group
    public $group = 'seo';

    // Sitemap Properties
    public $sitemap_enabled = true;
    public $sitemap_post_types = ['post', 'page', 'category'];
    public $sitemap_frequency = 'daily';
    public $sitemap_priority = '0.8';
    public $sitemap_include_images = true;
    public $sitemap_items_per_page = 1000;

    // IndexNow Properties
    public $indexnow_key;

    public function mount()
    {
        // ডাটাবেজ থেকে সেটিংস লোড করা
        $this->sitemap_enabled = (bool) setting('sitemap_enabled', true);

        // JSON ডিকোড করার সময় সতর্কতা অবলম্বন করা ভালো
        $types = setting('sitemap_post_types');
        $this->sitemap_post_types = is_string($types)
            ? json_decode($types, true)
            : ($types ?? ['post', 'page', 'category']);

        $this->sitemap_frequency = setting('sitemap_frequency', 'daily');
        $this->sitemap_priority = setting('sitemap_priority', '0.8');
        $this->sitemap_include_images = (bool) setting('sitemap_include_images', true);
        $this->sitemap_items_per_page = (int) setting('sitemap_items_per_page', 1000);

        $this->indexnow_key = setting('indexnow_key', Str::random(32));
    }

    public function generateIndexNowKey()
    {
        $this->indexnow_key = Str::random(32);
        $this->dispatch('media-toast', type: 'info', message: 'New API Key Generated. Don\'t forget to save.');
    }

    public function save()
    {
        $settings = [
            'sitemap_enabled' => $this->sitemap_enabled,
            'sitemap_post_types' => json_encode($this->sitemap_post_types),
            'sitemap_frequency' => $this->sitemap_frequency,
            'sitemap_priority' => $this->sitemap_priority,
            'sitemap_include_images' => $this->sitemap_include_images,
            'sitemap_items_per_page' => $this->sitemap_items_per_page,
            'indexnow_key' => $this->indexnow_key,
        ];

        // GeneralSetting মডেল বাদ দিয়ে হেল্পার ফাংশন ব্যবহার করা হলো
        foreach ($settings as $key => $value) {
            set_setting($key, $value, $this->group);
        }

        // যদি আপনার set_setting() ফাংশন অটোমেটিক ক্যাশ ক্লিয়ার না করে, তবে এটি রাখুন
        Cache::forget('general_settings');

        $this->dispatch('media-toast', type: 'success', message: 'Sitemap settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.sitemap-settings', [
            'sitemapUrl' => url('sitemap.xml'),
            'keyLocation' => url(($this->indexnow_key ?? 'key') . '.txt')
        ])->layout('components.layouts.app', [
            'title' => 'Sitemap Settings - ' . (config("settings.groups.{$this->group}.title") ?? 'Settings'),
        ]);
    }
}
