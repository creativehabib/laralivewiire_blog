<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Volt::test('settings.profile')
        ->set('name', 'Test User')
        ->set('username', 'test-user')
        ->set('email', 'test@example.com')
        ->set('website', 'https://example.com')
        ->set('bio', 'This is a test bio.')
        ->set('avatar', '/storage/avatars/test-avatar.jpg')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->username)->toEqual('test-user');
    expect($user->email)->toEqual('test@example.com');
    expect($user->website)->toEqual('https://example.com');
    expect($user->bio)->toEqual('This is a test bio.');
    expect($user->avatar)->toEqual('avatars/test-avatar.jpg');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Volt::test('settings.profile')
        ->set('name', 'Test User')
        ->set('username', $user->username)
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});


test('avatar URL is normalized to storage-relative path when updating profile', function () {
    $user = User::factory()->create(['avatar' => null]);

    $this->actingAs($user);

    $response = Volt::test('settings.profile')
        ->set('name', 'Test User')
        ->set('username', $user->username)
        ->set('email', $user->email)
        ->set('avatar', url('/storage/avatars/absolute-avatar.jpg'))
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->avatar)->toEqual('avatars/absolute-avatar.jpg');
});

test('username must be unique when updating profile', function () {
    $existingUser = User::factory()->create(['username' => 'existing-user']);
    $user = User::factory()->create(['username' => 'current-user']);

    $this->actingAs($user);

    $response = Volt::test('settings.profile')
        ->set('name', 'Test User')
        ->set('username', $existingUser->username)
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasErrors(['username']);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Volt::test('settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Volt::test('settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
