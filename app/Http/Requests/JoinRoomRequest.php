<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class JoinRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_code_or_url' => ['required', 'string', 'max:2048'],
        ];
    }

    public function validated($key = null, mixed $default = null): array
    {
        $validated = parent::validated($key, $default);

        $validated['room'] = $this->resolveRoom($validated['room_code_or_url']);

        return $validated;
    }

    private function resolveRoom(string $value): Room
    {
        $value = trim((string) $this->input('room_code_or_url'));

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            try {
                $route = Route::getRoutes()->match(Request::create($value, 'GET'));

                if ($route->getName() === 'rooms.show') {
                    $value = (string) $route->parameter('room');
                }
            } catch (RouteNotFoundException) {
                // If the URL doesn't match any route, we can ignore it and treat it as a room code.
            }
        }

        $room = Room::where('code', $value)->first();

        if ($room === null) {
            throw ValidationException::withMessages([
                'room_code_or_url' => __('The selected room does not exist.'),
            ]);
        }

        return $room;
    }
}
