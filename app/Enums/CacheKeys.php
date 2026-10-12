<?php

namespace App\Enums;

enum CacheKeys: string
{
    case GuestAccountAllocation = 'guest-account-allocation';
    case RoomJoin = 'room-join';
    case GameSnapshot = 'game-snapshot';
    case GameSnapshotLock = 'game-snapshot-lock';

    public static function roomJoin(int $roomId): string
    {
        return self::RoomJoin->value.":{$roomId}";
    }

    public static function GameSnapshot(int $roomId): string
    {
        return self::GameSnapshot->value.":{$roomId}";
    }

    public static function GameSnapshotLock(int $roomId): string
    {
        return self::GameSnapshotLock->value.":{$roomId}";
    }
}
