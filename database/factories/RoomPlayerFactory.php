<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomPlayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomPlayer>
 */
class RoomPlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'player_id' => User::factory(),
            'joined_at' => now(),
        ];
    }
}
