@php
    use App\Enums\RoomStatus;
@endphp

<div>
    @if ($room->status === RoomStatus::Waiting)
        @include('livewire.room.waiting')
    @elseif($room->status === RoomStatus::InProgress)
        <p>Game in progress...</p>
    @elseif($room->status === RoomStatus::Completed)
        <p>Game completed!</p>
    @endif
</div>
