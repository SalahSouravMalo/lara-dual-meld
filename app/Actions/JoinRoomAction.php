<?php

namespace App\Actions;

use App\Enums\CacheKeys;
use App\Exceptions\RoomAlreadyStartedException;
use App\Exceptions\RoomFullException;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class JoinRoomAction
{
    public function execute(Room $room, User $player): void
    {
        $lockKey = CacheKeys::roomJoin($room->id);

        Cache::lock($lockKey, 3)->block(3, function () use ($room, $player) {
            if ($room->starts_at->isPast()) {
                throw new RoomAlreadyStartedException(__('This room has already started.'));
            }

            if ($room->players()->where('player_id', $player->id)->exists()) {
                return;
            }

            if ($room->players()->count() >= 4) {
                throw new RoomFullException(__('This room is full.'));
            }

            $room->players()->create([
                'player_id' => $player->id,
                'joined_at' => now(),
            ]);
        });
    }
}
