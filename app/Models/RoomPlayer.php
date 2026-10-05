<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['room_id', 'player_id', 'joined_at', 'seat_number', 'left_at'])]
class RoomPlayer extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'left_at' => 'datetime',
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

    public function activities(): HasMany
    {
        return $this->hasMany(RoomActivity::class);
    }
}
