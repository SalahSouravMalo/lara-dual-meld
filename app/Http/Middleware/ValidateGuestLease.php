<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateGuestLease
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_guest_account) {
            return $next($request);
        }

        $sessionToken = $request->session()
            ->get('guest_lease_token');

        $tokenIsValid = $sessionToken
            && hash_equals(
                $user->guest_lease_token ?? '',
                hash('sha256', $sessionToken)
            );

        $leaseExpired = ! $user->guest_lease_expires_at
            || $user->guest_lease_expires_at->isPast();

        if (! $tokenIsValid || $leaseExpired) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('login')->with(
                'error',
                __('Your guest session has expired.')
            );
        }

        $shouldRefresh = ! $user->last_activity_at
            || $user->last_activity_at->lt(now()->subMinutes(5));

        if ($shouldRefresh) {
            $user->update([
                'last_activity_at' => now(),
                'guest_lease_expires_at' => now()->addMinutes(30),
            ]);
        }

        return $next($request);
    }
}
