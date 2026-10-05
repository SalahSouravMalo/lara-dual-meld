<?php

namespace App\Enums;

enum RoomStatus: string
{
    case Waiting = 'waiting';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case TimedOut = 'timed_out';
    case CancelledBySystem = 'cancelled_by_system';
}
