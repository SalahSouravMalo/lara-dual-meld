<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['room_player_id', 'sequence', 'action', 'card_value'])]
class RoomActivity extends Model
{
    use HasFactory;

    public function roomPlayer(): BelongsTo
    {
        return $this->belongsTo(RoomPlayer::class);
    }
}
