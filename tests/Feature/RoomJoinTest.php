<?php

use App\Exceptions\RoomAlreadyStartedException;
use App\Exceptions\RoomFullException;
use App\Models\Room;
use App\Models\RoomPlayer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an authenticated user can join a room using its code', function () {
    $creator = User::factory()->create();
    $player = User::factory()->create();
    $room = Room::factory()->create([
        'code' => 'ABC123',
        'created_by' => $creator->id,
    ]);

    $response = $this->actingAs($player)->post(route('rooms.join'), [
        'room_code_or_url' => $room->code,
    ]);

    $response->assertRedirect(route('rooms.show', $room));
    $this->assertDatabaseHas('room_players', [
        'room_id' => $room->id,
        'player_id' => $player->id,
    ]);
});

test('an authenticated user can join a room using its full URL', function () {
    $creator = User::factory()->create();
    $player = User::factory()->create();
    $room = Room::factory()->create([
        'code' => 'URL456',
        'created_by' => $creator->id,
    ]);

    $response = $this->actingAs($player)->post(route('rooms.join'), [
        'room_code_or_url' => route('rooms.show', $room),
    ]);

    $response->assertRedirect(route('rooms.show', $room));
    $this->assertDatabaseHas('room_players', [
        'room_id' => $room->id,
        'player_id' => $player->id,
    ]);
});

test('joining an unknown room returns a validation error', function () {
    $player = User::factory()->create();

    $response = $this->from(route('dashboard'))
        ->actingAs($player)
        ->post(route('rooms.join'), [
            'room_code_or_url' => 'UNKNOWN',
        ]);

    $response->assertRedirect(route('dashboard'))
        ->assertSessionHasErrors('room_code_or_url');
});

test('joining the same room more than once does not duplicate the player', function () {
    $creator = User::factory()->create();
    $player = User::factory()->create();
    $room = Room::factory()->create(['created_by' => $creator->id]);

    $this->actingAs($player)->post(route('rooms.join'), [
        'room_code_or_url' => $room->code,
    ]);
    $this->actingAs($player)->post(route('rooms.join'), [
        'room_code_or_url' => $room->code,
    ]);

    $this->assertDatabaseCount('room_players', 1);
    $this->assertDatabaseHas('room_players', [
        'room_id' => $room->id,
        'player_id' => $player->id,
    ]);
});

test('a fifth player cannot join a full room', function () {
    $creator = User::factory()->create();
    $room = Room::factory()->create(['created_by' => $creator->id]);
    RoomPlayer::factory()->count(4)->create(['room_id' => $room->id]);
    $fifthPlayer = User::factory()->create();

    $this->withoutExceptionHandling();

    expect(fn () => $this->actingAs($fifthPlayer)->post(route('rooms.join'), [
        'room_code_or_url' => $room->code,
    ]))->toThrow(RoomFullException::class);

    $this->assertDatabaseCount('room_players', 4);
});

test('a player cannot join a room that has already started', function () {
    $creator = User::factory()->create();
    $room = Room::factory()->started()->create(['created_by' => $creator->id]);
    $player = User::factory()->create();

    $this->withoutExceptionHandling();

    expect(fn () => $this->actingAs($player)->post(route('rooms.join'), [
        'room_code_or_url' => $room->code,
    ]))->toThrow(RoomAlreadyStartedException::class);

    $this->assertDatabaseMissing('room_players', [
        'room_id' => $room->id,
        'player_id' => $player->id,
    ]);
});
