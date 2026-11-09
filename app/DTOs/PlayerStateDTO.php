<?php

namespace App\DTOs;

class PlayerStateDTO
{
    public function __construct(
        public int $playerId,
        public string $name,
        public int $turnOrder
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            playerId: $data['playerId'],
            name: $data['name'],
            turnOrder: $data['turnOrder'],
        );
    }

    public function toArray(): array
    {
        return [
            'playerId' => $this->playerId,
            'name' => $this->name,
            'turnOrder' => $this->turnOrder,
        ];
    }
}
