<?php

namespace App\DTOs;

class RoomStateDTO
{
    public function __construct(
        public int $version,
        public array $players,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            version: $data['version'],
            players: array_map(
                static fn (array $player): PlayerStateDTO => PlayerStateDTO::fromArray($player),
                $data['players'],
            ),
        );
    }

    public function toArray(): array
    {
        return [
            'version' => $this->version,
            'players' => array_map(
                static fn (PlayerStateDTO $player): array => $player->toArray(),
                $this->players,
            ),
        ];
    }
}
