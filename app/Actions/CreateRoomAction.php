<?php

namespace App\Actions;

use App\DTOs\GameSnapshotDTO;
use App\Enums\RoomStatus;
use App\Exceptions\UnableToGenerateRoomCodeException;
use App\Models\Room;
use App\Models\User;
use App\Repositories\GameSnapshotRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateRoomAction
{
    public function __construct(
        private readonly GameSnapshotRepository $gameSnapshotRepository,
    ) {}

    public function execute(User $user): Room
    {
        return DB::transaction(function () use ($user) {
            $room = $this->createRoom($user->id);

            $creator = $room->players()->create([
                'player_id' => $user->id,
                'joined_at' => now(),
                'turn_order' => 1,
            ]);

            $initialSnapshot = GameSnapshotDTO::initial(
                $creator,
                $user->name,
            );

            $room->update([
                'game_snapshot' => $initialSnapshot->toArray(),
            ]);

            DB::afterCommit(function () use ($room, $initialSnapshot) {
                $this->gameSnapshotRepository->set(
                    $room->id,
                    $initialSnapshot
                );
            });

            return $room;
        });
    }

    private function createRoom(int $creatorId): Room
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $code = $this->generateCode();

            if (! Room::where('code', $code)->exists()) {
                return Room::create([
                    'code' => $code,
                    'starts_at' => now()->startOfMinute()->addMinutes(2),
                    'created_by' => $creatorId,
                    'status' => RoomStatus::Waiting,
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
