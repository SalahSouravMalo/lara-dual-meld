<?php

namespace App\Actions;

use App\Exceptions\UnableToGenerateRoomCodeException;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateRoomAction
{
    public function execute(int $createdBy): Room
    {
        return DB::transaction(function () use ($createdBy) {
            $room = $this->createRoom($createdBy);

            $room->players()->create([
                'player_id' => $createdBy,
                'joined_at' => now(),
            ]);

            return $room;
        });
    }

    private function createRoom(int $createdBy): Room
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $code = $this->generateCode();

            if (! Room::where('code', $code)->exists()) {
                return Room::create([
                    'code' => $code,
                    'starts_at' => now()->startOfMinute()->addMinutes(2),
                    'created_by' => $createdBy,
                ]);
            }
        }

        throw new UnableToGenerateRoomCodeException(
            __('Unable to generate a unique room code.')
        );
    }

    private function generateCode(): string
    {
        return Str::upper(Str::random(6));
    }
}
