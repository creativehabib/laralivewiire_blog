<?php

use App\Support\CommentConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('returns facebook provider when enabled and configured', function () {
    set_setting('comment_system', 'facebook', 'comments');
    set_setting('comment_facebook_enabled', true, 'comments');
    set_setting('comment_facebook_app_id', '1234567890', 'comments');

    $config = CommentConfig::get();

    expect($config['provider'])->toBe('facebook')
        ->and($config['facebook']['enabled'])->toBeTrue()
        ->and($config['facebook']['app_id'])->toBe('1234567890');

    expect(CommentConfig::facebookSdkUrl($config))->toContain('appId=1234567890');
});

it('falls back to local when facebook is enabled without an app id', function () {
    set_setting('comment_system', 'facebook', 'comments');
    set_setting('comment_facebook_enabled', true, 'comments');
    set_setting('comment_facebook_app_id', null, 'comments');

    $config = CommentConfig::get();

    expect($config['provider'])->toBe('local')
        ->and($config['facebook']['enabled'])->toBeFalse()
        ->and(CommentConfig::facebookSdkUrl($config))->toBeNull();
});

it('returns both provider when enabled and configured for dual mode', function () {
    set_setting('comment_system', 'both', 'comments');
    set_setting('comment_facebook_enabled', true, 'comments');
    set_setting('comment_facebook_app_id', 'fb-app', 'comments');

    $config = CommentConfig::get();

    expect($config['provider'])->toBe('both')
        ->and($config['facebook']['enabled'])->toBeTrue();
});

it('uses local provider when facebook integration is disabled', function () {
    set_setting('comment_system', 'facebook', 'comments');
    set_setting('comment_facebook_enabled', false, 'comments');
    set_setting('comment_facebook_app_id', 'fb-app', 'comments');

    $config = CommentConfig::get();

    expect($config['provider'])->toBe('local')
        ->and($config['facebook']['enabled'])->toBeFalse();
});
