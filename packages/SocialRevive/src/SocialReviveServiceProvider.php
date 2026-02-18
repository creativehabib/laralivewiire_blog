<?php

namespace Habib\SocialRevive;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SocialReviveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/social-revive.php',
            'social-revive'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/social-revive.php' => config_path('social-revive.php'),
        ], 'social-revive-config');

        $this->applyDatabaseOverrides();
    }

    protected function applyDatabaseOverrides(): void
    {
        if (! class_exists(\App\Models\Setting::class) || ! Schema::hasTable('settings')) {
            return;
        }

        config()->set('social-revive.queue_connection', setting('social_revive_queue_connection', config('social-revive.queue_connection')));

        config()->set('social-revive.utm.enabled', (bool) setting('social_revive_utm_enabled', config('social-revive.utm.enabled', true)));
        config()->set('social-revive.utm.source', setting('social_revive_utm_source', config('social-revive.utm.source', 'social')));
        config()->set('social-revive.utm.medium', setting('social_revive_utm_medium', config('social-revive.utm.medium', 'revive')));

        config()->set('social-revive.ai.enabled', (bool) setting('social_revive_ai_enabled', config('social-revive.ai.enabled', true)));
        config()->set('social-revive.ai.provider', setting('social_revive_ai_provider', config('social-revive.ai.provider', 'openai')));
        config()->set('social-revive.ai.model', setting('social_revive_ai_model', config('social-revive.ai.model', 'gpt-4o-mini')));
        config()->set('social-revive.ai.api_key', setting('social_revive_ai_api_key', config('social-revive.ai.api_key')));
    }
}
