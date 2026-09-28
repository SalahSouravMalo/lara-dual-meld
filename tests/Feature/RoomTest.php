<?php

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an authenticated user can create a room', function () {
    $creator = User::factory()->create();

    $response = $this->actingAs($creator)->post(route('rooms.store'));

    $room = Room::query()->latest('id')->firstOrFail();

    $response->assertRedirect(route('rooms.show', $room));
    expect($room)->not->toBeNull()
        ->and($room->code)->toHaveLength(6)
        ->and($room->starts_at->isFuture())->toBeTrue();

    $this->assertDatabaseHas('rooms', [
        'id' => $room->id,
        'created_by' => $creator->id,
    ]);
    $this->assertDatabaseHas('room_players', [
        'room_id' => $room->id,
        'player_id' => $creator->id,
    ]);
});
