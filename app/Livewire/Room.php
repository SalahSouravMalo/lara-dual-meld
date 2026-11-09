<?php

namespace App\Livewire;

use App\Models\Room as RoomModel;
use App\Repositories\GameSnapshotRepository;
use Livewire\Attributes\On;
use Livewire\Component;

class Room extends Component
{
    public RoomModel $room;

    public array $state;

    #[On('echo-private:rooms.{room.id},.RoomPlayerJoined')]
    public function playerJoined(): void
    {
        $this->refreshState();
    }

    public function mount(): void
    {
        $this->refreshState();
    }

    public function render()
    {
        return view('livewire.room.show', [
            'room' => $this->room,
            'state' => $this->state,
        ]);
    }

    private function refreshState(): void
    {
        $this->state = app(GameSnapshotRepository::class)
            ->get($this->room->id)
            ?->forPlayer()
            ->toArray();
    }
}
