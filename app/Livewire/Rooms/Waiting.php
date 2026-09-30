<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Waiting extends Component
{
    public Room $room;

    public function mount(Room $room): void
    {
        $this->room = $room->load('players.player');
    }

    #[On('echo-private:rooms.{room.id},.RoomPlayerJoined')]
    public function playerJoined(): void
    {
        $this->room->load('players.player');
    }

    public function getPlayersProperty(): Collection
    {
        return $this->room->players->map(fn ($roomPlayer) => $roomPlayer->player)->values();
    }

    public function render()
    {
        return view('livewire.rooms.waiting');
    }
}
