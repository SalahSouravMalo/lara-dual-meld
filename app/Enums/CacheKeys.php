<?php

namespace App\Enums;

enum CacheKeys: string
{
    case GuestAccountAllocation = 'guest-account-allocation';
    case RoomJoin = 'room-join';

    public static function roomJoin(int $roomId): string
    {
        return self::RoomJoin->value.":{$roomId}";
    }
}
