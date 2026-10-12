<?php

namespace App\Repositories;

use App\DTOs\GameSnapshotDTO;
use App\Enums\CacheKeys;
use Illuminate\Support\Facades\Cache;

class GameSnapshotRepository
{
    public function get(int $roomId): ?GameSnapshotDTO
    {
        $snapshot = Cache::get(CacheKeys::GameSnapshot($roomId));

        return \is_array($snapshot) ? GameSnapshotDTO::fromArray($snapshot) : null;
    }

    public function set(int $roomId, GameSnapshotDTO $snapshot): void
    {
        Cache::put(
            CacheKeys::GameSnapshot($roomId),
            $snapshot->toArray(),
            now()->addHour()
        );
    }
}
