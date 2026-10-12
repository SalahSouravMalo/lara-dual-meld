<?php

namespace App\DTOs;

use App\Models\Room;
use App\Models\RoomPlayer;

class GameSnapshotDTO
{
    public function __construct(
        public int $version,
        public array $players,
        public array $deck,
        public array $discardPile,
        public int $turnOrder,
        public ?int $turnStartedAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            version: $data['version'],
            players: array_map(
                static fn (array $player): PlayerDTO => PlayerDTO::fromArray($player),
                $data['players'],
            ),
            deck: $data['deck'],
            discardPile: $data['discardPile'],
            turnOrder: $data['turnOrder'],
            turnStartedAt: $data['turnStartedAt'],
        );
    }

    public function toArray(): array
    {
        return [
            'version' => $this->version,
            'players' => array_map(
                static fn (PlayerDTO $player): array => $player->toArray(),
                $this->players,
            ),
            'deck' => $this->deck,
            'discardPile' => $this->discardPile,
            'turnOrder' => $this->turnOrder,
            'turnStartedAt' => $this->turnStartedAt,
        ];
    }

    public function playerIds(): array
    {
        return array_map(
            static fn (PlayerDTO $player): int => $player->id,
            $this->players,
        );
    }

    public function maxTurnOrder(): int
    {
        if ($this->players === []) {
            return 0;
        }

        return max(array_map(
            static fn (PlayerDTO $player): int => $player->turnOrder,
            $this->players,
        ));
    }

    public static function initial(Room $room, RoomPlayer $creator): self
    {
        return new self(
            version: 1,
            players: [
                new PlayerDTO(
                    playerId: $creator->player_id,
                    turnOrder: $creator->turn_order,
                    turnType: null,
                    hand: [],
                ),
            ],
            deck: [],
            discardPile: [],
            turnOrder: 1,
            turnStartedAt: null,
        );
    }
}
