<?php

namespace App\Http\Controllers;

use App\Actions\CreateRoomAction;
use App\Actions\JoinRoomAction;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function store(Request $request, CreateRoomAction $action): RedirectResponse
    {
        $room = $action->execute($request->user()->id);

        return redirect()->route('rooms.show', $room);
    }

    public function show(Request $request, Room $room, JoinRoomAction $action): View
    {
        $action->execute($room, $request->user());

        $room->load('players.player');

        return view('rooms.show', compact('room'));
    }
}
