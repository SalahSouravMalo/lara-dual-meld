<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

/*
|--------------------------------------------------------------------------
| Guest Login Controller Tests
|--------------------------------------------------------------------------
*/

test('a new guest account is created when none are available', function () {
    $response = $this->post(route('guest.login'));

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();

    $user = auth()->user();

    expect($user->is_guest_account)->toBeTrue();
    expect($user->guest_lease_token)->not->toBeNull();
    expect($user->guest_lease_expires_at)->not->toBeNull();
});

test('an idle guest account is reused instead of creating a new one', function () {
    $idleGuest = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_expires_at' => now()->subMinute(), // expired lease
        'last_activity_at' => now()->subHour(),
    ]);

    $this->post(route('guest.login'));

    $this->assertAuthenticatedAs($idleGuest->fresh());

    expect(User::guestAccount()->count())->toBe(1);
});

test('the oldest idle guest account is chosen when multiple are available', function () {
    $older = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_expires_at' => now()->subMinute(),
        'last_activity_at' => now()->subHours(2),
    ]);

    $newer = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_expires_at' => now()->subMinute(),
        'last_activity_at' => now()->subMinutes(30),
    ]);

    $this->post(route('guest.login'));

    $this->assertAuthenticatedAs($older->fresh());
});

test('an actively leased guest account is not reassigned', function () {
    $activeGuest = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_expires_at' => now()->addMinutes(30), // still valid
        'last_activity_at' => now(),
    ]);

    $this->post(route('guest.login'));

    // A new account should be created instead, since the only guest is still leased
    expect(User::guestAccount()->count())->toBe(2);
    $this->assertAuthenticatedAs(
        User::guestAccount()->whereNot('id', $activeGuest->id)->first()
    );
});

test('login is rejected once the guest account cap is reached', function () {
    User::factory()->count(User::MAX_GUEST_ACCOUNTS)->create([
        'is_guest_account' => true,
        'guest_lease_expires_at' => now()->addMinutes(30), // all actively leased
    ]);

    $response = $this->post(route('guest.login'));

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertGuest();
});

test('guest lease token is stored hashed, not in plaintext', function () {
    $this->post(route('guest.login'));

    $user = auth()->user();
    $rawToken = session('guest_lease_token');

    expect($user->guest_lease_token)->not->toBe($rawToken);
    expect($user->guest_lease_token)->toBe(hash('sha256', $rawToken));
});

/*
|--------------------------------------------------------------------------
| ValidateGuestLease Middleware Tests
|--------------------------------------------------------------------------
*/
test('a guest with a valid lease passes through untouched', function () {
    $token = 'raw-token-123';

    $user = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_token' => hash('sha256', $token),
        'guest_lease_expires_at' => now()->addMinutes(30),
        'last_activity_at' => now(),
    ]);

    $this->actingAs($user);
    session(['guest_lease_token' => $token]);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $this->assertAuthenticatedAs($user);
});

test('a guest with an expired lease is logged out', function () {
    $token = 'raw-token-123';

    $user = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_token' => hash('sha256', $token),
        'guest_lease_expires_at' => now()->subMinute(), // expired
        'last_activity_at' => now()->subHour(),
    ]);

    $this->actingAs($user);
    session(['guest_lease_token' => $token]);

    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('a guest with a mismatched session token is logged out', function () {
    $user = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_token' => hash('sha256', 'correct-token'),
        'guest_lease_expires_at' => now()->addMinutes(30),
    ]);

    $this->actingAs($user);
    session(['guest_lease_token' => 'wrong-token']); // tampered/stale

    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('a guest with no session token at all is logged out', function () {
    $user = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_token' => hash('sha256', 'some-token'),
        'guest_lease_expires_at' => now()->addMinutes(30),
    ]);

    $this->actingAs($user); // note: no session token set

    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('last_activity_at and lease expiry refresh after 5 minutes of staleness', function () {
    $token = 'raw-token-123';

    $user = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_token' => hash('sha256', $token),
        'guest_lease_expires_at' => now()->addMinutes(10),
        'last_activity_at' => now()->subMinutes(10), // stale beyond 5-min threshold
    ]);

    $this->actingAs($user);
    session(['guest_lease_token' => $token]);

    $this->get(route('dashboard'));

    $user->refresh();

    expect($user->last_activity_at->diffInSeconds(now()))->toBeLessThan(5);
    expect(now()->diffInSeconds($user->guest_lease_expires_at))->toBeGreaterThanOrEqual(1795);
});

test('last_activity_at is not refreshed if updated recently', function () {
    $token = 'raw-token-123';
    $recentActivity = now()->subMinutes(2);

    $user = User::factory()->create([
        'is_guest_account' => true,
        'guest_lease_token' => hash('sha256', $token),
        'guest_lease_expires_at' => now()->addMinutes(10),
        'last_activity_at' => $recentActivity,
    ]);

    $this->actingAs($user);
    session(['guest_lease_token' => $token]);

    $this->get(route('dashboard'));

    $user->refresh();

    expect($user->last_activity_at->diffInSeconds($recentActivity))->toBeLessThan(1);
});

test('non-guest users are unaffected by the lease middleware', function () {
    $user = User::factory()->create(['is_guest_account' => false]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $this->assertAuthenticatedAs($user);
});
