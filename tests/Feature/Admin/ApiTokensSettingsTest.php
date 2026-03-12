<?php

use App\Livewire\Admin\Settings\ApiTokensSettings;
use App\Models\User;
use Livewire\Livewire;

test('authenticated users can generate api token from developer settings', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ApiTokensSettings::class)
        ->set('token_name', 'mobile-app')
        ->set('expires_in_days', 15)
        ->call('generateToken')
        ->assertSet('token_name', 'dashboard-token')
        ->assertSet('expires_in_days', 15)
        ->assertSet('plain_text_token', fn (?string $token) => filled($token));

    expect($user->fresh()->apiTokens()->where('name', 'mobile-app')->exists())->toBeTrue();
});


test('users can revoke their own api token from developer settings', function () {
    $user = User::factory()->create();

    $token = $user->apiTokens()->create([
        'name' => 'integration',
        'token_hash' => hash('sha256', 'sample-token'),
    ]);

    $this->actingAs($user);

    Livewire::test(ApiTokensSettings::class)
        ->call('revokeToken', $token->id);

    expect($user->fresh()->apiTokens()->whereKey($token->id)->exists())->toBeFalse();
});
