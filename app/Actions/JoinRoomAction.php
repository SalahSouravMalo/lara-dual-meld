<?php

namespace App\Actions;

use App\DTOs\GameSnapshotDTO;
use App\DTOs\PlayerDTO;
use App\Enums\CacheKeys;
use App\Enums\RoomStatus;
use App\Events\RoomPlayerJoined;
use App\Exceptions\RoomAlreadyStartedException;
use App\Exceptions\RoomFullException;
use App\Models\Room;
use App\Models\User;
use App\Repositories\GameSnapshotRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JoinRoomAction
{
    public function __construct(
        private readonly GameSnapshotRepository $gameSnapshotRepository,
    ) {}

    public function execute(Room $room, User $player): void
    {
        $lockKey = CacheKeys::roomJoin($room->id);

        Cache::lock($lockKey, 3)->block(3, function () use ($room, $player) {
            $snapshot = $this->gameSnapshotRepository->get($room->id)
                ?? GameSnapshotDTO::fromArray($room->game_snapshot)
                ?? throw new \RuntimeException(
                    __('Snapshot missing for room :roomId', ['roomId' => $room->id])
                );

            if (\in_array($player->id, $snapshot->playerIds(), true)) {
                return;
            }

            if ($room->status !== RoomStatus::Waiting) {
                throw new RoomAlreadyStartedException(__('This room has already started.'));
            }

            if (\count($snapshot->players) >= 4) {
                throw new RoomFullException(__('This room is full.'));
            }

            DB::transaction(function () use ($room, $player, $snapshot): void {
                $joinedPlayer = $room->players()->create([
                    'player_id' => $player->id,
                    'joined_at' => now(),
                    'turn_order' => $snapshot->maxTurnOrder() + 1,
                ]);

                $snapshot->version++;
                $snapshot->players[] = new PlayerDTO(
                    playerId: $joinedPlayer->player_id,
                    turnOrder: $joinedPlayer->turn_order,
                    turnType: null,
                    hand: [],
                );

                $room->update(['game_snapshot' => $snapshot->toArray()]);

                DB::afterCommit(function () use ($room, $snapshot) {
                    $this->gameSnapshotRepository->set($room->id, $snapshot);

                    RoomPlayerJoined::dispatch($room->id);
                });
            });
        });
    }
}
