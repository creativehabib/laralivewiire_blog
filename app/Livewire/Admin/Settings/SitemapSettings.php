<?php

namespace App\Livewire\Admin\Settings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;

class SitemapSettings extends Component
{
    // Settings Group
    public $group = 'seo';

    // Sitemap Properties
    public $sitemap_enabled = true;
    public $sitemap_post_types = ['post', 'page', 'category'];
    public $sitemap_include_images = true;
    public $sitemap_items_per_page = 1000;

    // Type Specific Settings (Array to store freq/priority for each type)
    public $type_settings = [];

    // IndexNow Properties
    public $indexnow_key;

    public function mount()
    {
        // 1. Basic Settings
        $this->sitemap_enabled = (bool) setting('sitemap_enabled', true);
        $this->sitemap_include_images = (bool) setting('sitemap_include_images', true);
        $this->sitemap_items_per_page = (int) setting('sitemap_items_per_page', 1000);

        // 2. Load Post Types
        $types = setting('sitemap_post_types');
        $this->sitemap_post_types = is_string($types)
            ? json_decode($types, true)
            : ($types ?? ['post', 'page', 'category']);

        // 3. Load Type Specific Settings (Priority & Frequency)
        $savedTypeSettings = setting('sitemap_type_settings');
        $this->type_settings = is_string($savedTypeSettings)
            ? json_decode($savedTypeSettings, true)
            : [];

        // Ensure defaults exist for selected types
        $this->ensureDefaultSettings();

        // 4. IndexNow Key
        $this->indexnow_key = setting('indexnow_key', Str::random(32));
    }

    // যখন ইউজার পোস্ট টাইপ চেঞ্জ করবে, তখন ডিফল্ট ভ্যালু সেট হবে
    public function updatedSitemapPostTypes()
    {
        $this->ensureDefaultSettings();
    }

    private function ensureDefaultSettings()
    {
        $defaults = [
            'post' => ['frequency' => 'daily', 'priority' => '0.8'],
            'page' => ['frequency' => 'monthly', 'priority' => '0.6'],
            'category' => ['frequency' => 'weekly', 'priority' => '0.5'],
            // Fallback for others
            'default' => ['frequency' => 'weekly', 'priority' => '0.5'],
        ];

        foreach ($this->sitemap_post_types as $type) {
            if (!isset($this->type_settings[$type])) {
                $this->type_settings[$type] = $defaults[$type] ?? $defaults['default'];
            }
        }
    }

    public function generateIndexNowKey()
    {
        $this->indexnow_key = Str::random(32);
        $this->dispatch('media-toast', type: 'info', message: 'New API Key Generated. Don\'t forget to save.');
    }

    public function save()
    {
        $previousIndexNowKey = setting('indexnow_key');

        $settings = [
            'sitemap_enabled' => $this->sitemap_enabled,
            'sitemap_post_types' => json_encode($this->sitemap_post_types),
            // আলাদা ফ্রিকোয়েন্সি ও প্রায়োরিটি এখানে JSON আকারে সেভ হবে
            'sitemap_type_settings' => json_encode($this->type_settings),
            'sitemap_include_images' => $this->sitemap_include_images,
            'sitemap_items_per_page' => $this->sitemap_items_per_page,
            'indexnow_key' => $this->indexnow_key,
        ];

        // Save using helper
        foreach ($settings as $key => $value) {
            set_setting($key, $value, $this->group);
        }

        // Handle IndexNow File
        $this->writeIndexNowKeyFile($previousIndexNowKey);

        // Clear Cache
        Cache::forget('general_settings');

        $this->dispatch('media-toast', type: 'success', message: 'Sitemap settings updated successfully!');
    }

    protected function writeIndexNowKeyFile(?string $previousIndexNowKey): void
    {
        if (! $this->indexnow_key) return;

        // Delete old file if key changed
        if ($previousIndexNowKey && $previousIndexNowKey !== $this->indexnow_key) {
            File::delete(public_path($previousIndexNowKey . '.txt'));
        }

        // Create new file
        File::put(public_path($this->indexnow_key . '.txt'), $this->indexnow_key);
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
