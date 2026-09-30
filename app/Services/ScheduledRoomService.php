<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Support\Carbon;

class ScheduledRoomService
{
    public function __construct(
        private readonly RoomService $roomService,
    ) {}

    public function init(): void
    {
        $startsAt = Carbon::now()->startOfMinute();

        Room::query()
            ->where('starts_at', $startsAt)
            ->each(fn (Room $room) => $this->roomService->start($room));
    }
}
