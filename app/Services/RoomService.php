<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Support\Facades\Log;

class RoomService
{
    public function start(Room $room): void
    {
        Log::info("Room {$room->code} started.");
    }
}
