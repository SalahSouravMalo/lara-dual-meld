<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Socialite\Socialite;
use Laravel\Socialite\Two\User;

uses(DatabaseTransactions::class);

test('user is redirected to google', function () {
    Socialite::fake('google');

    $response = $this->get(route('auth.google.redirect'));

    $response->assertRedirect();
});

test('user can login with google', function () {
    Socialite::fake('google', User::fake([
        'id' => 'google-123',
        'name' => 'Jason Beggs',
        'email' => 'jason@example.com',
    ]));

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'name' => 'Jason Beggs',
        'email' => 'jason@example.com',
        'google_id' => 'google-123',
    ]);
});
