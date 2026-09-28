<?php

namespace App\Http\Controllers;

use App\Actions\JoinRoomAction;
use App\Http\Requests\JoinRoomRequest;
use Illuminate\Http\RedirectResponse;

class RoomJoinController extends Controller
{
    public function __invoke(JoinRoomRequest $request, JoinRoomAction $action): RedirectResponse
    {
        $room = $request->validated()['room'];

        $action->execute($room, $request->user());

        return redirect()->route('rooms.show', $room);
    }
}
