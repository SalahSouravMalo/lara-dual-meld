<?php

namespace Database\Factories;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->regexify('[A-Z0-9]{6}'),
            'starts_at' => now()->startOfMinute()->addMinutes(2),
            'created_by' => User::factory(),
            'status' => RoomStatus::Waiting,
        ];
    }

    public function started(): static
    {
        return $this->state([
            'starts_at' => now()->subMinute(),
        ]);
    }
}
