<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['room_id', 'player_id', 'joined_at'])]
class RoomPlayer extends Model
{
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_id', 'id');
    }
}
