<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomPlayerJoined implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public int $roomId) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("rooms.{$this->roomId}");
    }

    public function broadcastAs(): string
    {
        return 'RoomPlayerJoined';
    }
}
