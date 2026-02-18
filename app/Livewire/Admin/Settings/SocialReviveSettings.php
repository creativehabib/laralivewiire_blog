<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class SocialReviveSettings extends Component
{
    public bool $enabled = false;
    public bool $debug_mode = false;
    public bool $share_old_posts = true;

    public int $minimum_post_age = 30;
    public int $maximum_post_age = 365;

    public int $share_interval_value = 4;
    public string $share_interval_unit = 'hours';
    public int $posts_per_run = 1;

    public string $post_types = 'post';
    public string $include_categories = '';
    public string $exclude_categories = '';

    public string $hashtags_mode = 'post_tags';
    public string $custom_hashtags = '';
    public string $post_template = '{title} {url}';

    public bool $url_shortener_enabled = false;
    public string $url_shortener_provider = 'none';
    public string $url_shortener_api_key = '';

    public bool $utm_enabled = false;
    public string $utm_source = 'social';
    public string $utm_medium = 'revive';
    public string $utm_campaign = 'auto-share';

    public function mount(): void
    {
        $this->enabled = (bool) setting('social_revive_enabled', false);
        $this->debug_mode = (bool) setting('social_revive_debug_mode', false);
        $this->share_old_posts = (bool) setting('social_revive_share_old_posts', true);

        $this->minimum_post_age = (int) setting('social_revive_minimum_post_age', 30);
        $this->maximum_post_age = (int) setting('social_revive_maximum_post_age', 365);

        $this->share_interval_value = (int) setting('social_revive_share_interval_value', 4);
        $this->share_interval_unit = (string) setting('social_revive_share_interval_unit', 'hours');
        $this->posts_per_run = (int) setting('social_revive_posts_per_run', 1);

        $this->post_types = (string) setting('social_revive_post_types', 'post');
        $this->include_categories = (string) setting('social_revive_include_categories', '');
        $this->exclude_categories = (string) setting('social_revive_exclude_categories', '');

        $this->hashtags_mode = (string) setting('social_revive_hashtags_mode', 'post_tags');
        $this->custom_hashtags = (string) setting('social_revive_custom_hashtags', '');
        $this->post_template = (string) setting('social_revive_post_template', '{title} {url}');

        $this->url_shortener_enabled = (bool) setting('social_revive_url_shortener_enabled', false);
        $this->url_shortener_provider = (string) setting('social_revive_url_shortener_provider', 'none');
        $this->url_shortener_api_key = (string) setting('social_revive_url_shortener_api_key', '');

        $this->utm_enabled = (bool) setting('social_revive_utm_enabled', false);
        $this->utm_source = (string) setting('social_revive_utm_source', 'social');
        $this->utm_medium = (string) setting('social_revive_utm_medium', 'revive');
        $this->utm_campaign = (string) setting('social_revive_utm_campaign', 'auto-share');
    }

    public function save(): void
    {
        $this->validate([
            'minimum_post_age' => ['required', 'integer', 'min:0', 'max:3650'],
            'maximum_post_age' => ['required', 'integer', 'gte:minimum_post_age', 'max:36500'],
            'share_interval_value' => ['required', 'integer', 'min:1', 'max:1440'],
            'share_interval_unit' => ['required', 'in:minutes,hours,days'],
            'posts_per_run' => ['required', 'integer', 'min:1', 'max:50'],
            'post_types' => ['required', 'string', 'max:255'],
            'hashtags_mode' => ['required', 'in:none,post_tags,post_categories,custom'],
            'post_template' => ['required', 'string', 'max:500'],
            'url_shortener_provider' => ['required', 'in:none,bitly,rebrandly'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:150'],
        ]);

        set_setting('social_revive_enabled', $this->enabled, 'social-revive');
        set_setting('social_revive_debug_mode', $this->debug_mode, 'social-revive');
        set_setting('social_revive_share_old_posts', $this->share_old_posts, 'social-revive');

        set_setting('social_revive_minimum_post_age', $this->minimum_post_age, 'social-revive');
        set_setting('social_revive_maximum_post_age', $this->maximum_post_age, 'social-revive');

        set_setting('social_revive_share_interval_value', $this->share_interval_value, 'social-revive');
        set_setting('social_revive_share_interval_unit', $this->share_interval_unit, 'social-revive');
        set_setting('social_revive_posts_per_run', $this->posts_per_run, 'social-revive');

        set_setting('social_revive_post_types', trim($this->post_types), 'social-revive');
        set_setting('social_revive_include_categories', trim($this->include_categories), 'social-revive');
        set_setting('social_revive_exclude_categories', trim($this->exclude_categories), 'social-revive');

        set_setting('social_revive_hashtags_mode', $this->hashtags_mode, 'social-revive');
        set_setting('social_revive_custom_hashtags', trim($this->custom_hashtags), 'social-revive');
        set_setting('social_revive_post_template', trim($this->post_template), 'social-revive');

        set_setting('social_revive_url_shortener_enabled', $this->url_shortener_enabled, 'social-revive');
        set_setting('social_revive_url_shortener_provider', $this->url_shortener_provider, 'social-revive');
        set_setting('social_revive_url_shortener_api_key', trim($this->url_shortener_api_key), 'social-revive');

        set_setting('social_revive_utm_enabled', $this->utm_enabled, 'social-revive');
        set_setting('social_revive_utm_source', trim($this->utm_source), 'social-revive');
        set_setting('social_revive_utm_medium', trim($this->utm_medium), 'social-revive');
        set_setting('social_revive_utm_campaign', trim($this->utm_campaign), 'social-revive');

        $this->dispatch('media-toast', type: 'success', message: 'Social Revive settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.social-revive-settings')
            ->layout('components.layouts.app', ['title' => 'Social Revive Settings']);
    }
}
