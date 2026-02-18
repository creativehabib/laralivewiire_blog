<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class SocialReviveSettings extends Component
{
    public string $queue_connection = 'redis';

    public bool $utm_enabled = true;
    public string $utm_source = 'social';
    public string $utm_medium = 'revive';

    public bool $ai_enabled = true;
    public string $ai_provider = 'openai';
    public string $ai_model = 'gpt-4o-mini';
    public string $ai_api_key = '';

    public int $default_min_days_old = 30;
    public int $default_interval_minutes = 60;
    public int $default_avoid_repeat_days = 7;
    public string $default_template = '{title} {url}';
    public string $default_timezone = 'UTC';
    public bool $default_ai_caption = false;
    public bool $default_auto_hashtag = false;

    public function mount(): void
    {
        $this->queue_connection = (string) setting('social_revive_queue_connection', config('social-revive.queue_connection', 'redis'));

        $this->utm_enabled = (bool) setting('social_revive_utm_enabled', config('social-revive.utm.enabled', true));
        $this->utm_source = (string) setting('social_revive_utm_source', config('social-revive.utm.source', 'social'));
        $this->utm_medium = (string) setting('social_revive_utm_medium', config('social-revive.utm.medium', 'revive'));

        $this->ai_enabled = (bool) setting('social_revive_ai_enabled', config('social-revive.ai.enabled', true));
        $this->ai_provider = (string) setting('social_revive_ai_provider', config('social-revive.ai.provider', 'openai'));
        $this->ai_model = (string) setting('social_revive_ai_model', config('social-revive.ai.model', 'gpt-4o-mini'));
        $this->ai_api_key = (string) setting('social_revive_ai_api_key', config('social-revive.ai.api_key', ''));

        // Default automation rule values (used by package rule create flow)
        $this->default_min_days_old = (int) setting('social_revive_default_min_days_old', 30);
        $this->default_interval_minutes = (int) setting('social_revive_default_interval_minutes', 60);
        $this->default_avoid_repeat_days = (int) setting('social_revive_default_avoid_repeat_days', 7);
        $this->default_template = (string) setting('social_revive_default_template', '{title} {url}');
        $this->default_timezone = (string) setting('social_revive_default_timezone', 'UTC');
        $this->default_ai_caption = (bool) setting('social_revive_default_ai_caption', false);
        $this->default_auto_hashtag = (bool) setting('social_revive_default_auto_hashtag', false);
    }

    public function save(): void
    {
        $this->validate([
            'queue_connection' => ['required', 'in:sync,database,redis,sqs'],
            'utm_source' => ['required', 'string', 'max:100'],
            'utm_medium' => ['required', 'string', 'max:100'],
            'ai_provider' => ['required', 'in:openai,gemini,anthropic,none'],
            'ai_model' => ['required', 'string', 'max:120'],
            'ai_api_key' => ['nullable', 'string', 'max:255'],
            'default_min_days_old' => ['required', 'integer', 'min:0', 'max:3650'],
            'default_interval_minutes' => ['required', 'integer', 'min:5', 'max:10080'],
            'default_avoid_repeat_days' => ['required', 'integer', 'min:0', 'max:3650'],
            'default_template' => ['required', 'string', 'max:500'],
            'default_timezone' => ['required', 'timezone'],
        ]);

        // Package config override keys
        set_setting('social_revive_queue_connection', $this->queue_connection, 'social-revive');
        set_setting('social_revive_utm_enabled', $this->utm_enabled, 'social-revive');
        set_setting('social_revive_utm_source', trim($this->utm_source), 'social-revive');
        set_setting('social_revive_utm_medium', trim($this->utm_medium), 'social-revive');

        set_setting('social_revive_ai_enabled', $this->ai_enabled, 'social-revive');
        set_setting('social_revive_ai_provider', $this->ai_provider, 'social-revive');
        set_setting('social_revive_ai_model', trim($this->ai_model), 'social-revive');
        set_setting('social_revive_ai_api_key', trim($this->ai_api_key), 'social-revive');

        // Package default automation rule values
        set_setting('social_revive_default_min_days_old', $this->default_min_days_old, 'social-revive');
        set_setting('social_revive_default_interval_minutes', $this->default_interval_minutes, 'social-revive');
        set_setting('social_revive_default_avoid_repeat_days', $this->default_avoid_repeat_days, 'social-revive');
        set_setting('social_revive_default_template', trim($this->default_template), 'social-revive');
        set_setting('social_revive_default_timezone', trim($this->default_timezone), 'social-revive');
        set_setting('social_revive_default_ai_caption', $this->default_ai_caption, 'social-revive');
        set_setting('social_revive_default_auto_hashtag', $this->default_auto_hashtag, 'social-revive');

        $this->dispatch('media-toast', type: 'success', message: 'Social Revive package settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings.social-revive-settings')
            ->layout('components.layouts.app', ['title' => 'Social Revive Settings']);
    }
}
