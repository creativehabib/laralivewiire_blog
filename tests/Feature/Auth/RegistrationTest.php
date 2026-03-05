<?php

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'username' => 'john-doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('newly registered users are assigned the user role', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Jane Doe',
        'username' => 'jane-doe',
        'email' => 'jane@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $registeredUser = \App\Models\User::where('email', 'jane@example.com')->first();

    expect($registeredUser)->not->toBeNull();
    expect($registeredUser->hasRole('user'))->toBeTrue();

    $this->assertDatabaseHas('roles', [
        'name' => 'user',
        'guard_name' => 'web',
    ]);
});

test('registration screen is not available when disabled from settings', function () {
    set_setting('user_registration_enabled', false, 'general');

    $response = $this->get(route('register'));

    $response->assertNotFound();
});

test('new users cannot register when disabled from settings', function () {
    set_setting('user_registration_enabled', false, 'general');

    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'username' => 'john-doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();

    $this->assertGuest();
});


test('username is required to register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('username');

    $this->assertGuest();
});
