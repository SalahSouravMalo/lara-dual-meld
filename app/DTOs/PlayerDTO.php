<?php

namespace App\DTOs;

use App\Enums\TurnType;

class PlayerDTO
{
    public function __construct(
        public int $playerId,
        public int $turnOrder,
        public ?TurnType $turnType,
        public array $hand,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            playerId: $data['player_id'],
            turnOrder: $data['turnOrder'],
            turnType: isset($data['turnType']) ? TurnType::from($data['turnType']) : null,
            hand: $data['hand'],
        );
    }

    public function toArray(): array
    {
        return [
            'playerId' => $this->playerId,
            'turnOrder' => $this->turnOrder,
            'turnType' => $this->turnType->value,
            'hand' => $this->hand,
        ];
    }
}
