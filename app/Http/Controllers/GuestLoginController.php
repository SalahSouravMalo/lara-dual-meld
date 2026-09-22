<?php

namespace App\Http\Controllers;

use App\Enums\CacheKeys;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GuestLoginController extends Controller
{
    public function __invoke(Request $request)
    {
        [$user, $token] = Cache::lock(CacheKeys::GuestAccountAllocation->value, 3)
            ->block(3, function () {
                $user = User::query()
                    ->guestAccount()
                    ->where(function ($query) {
                        $query
                            ->whereNull('guest_lease_expires_at')
                            ->orWhere(
                                'guest_lease_expires_at',
                                '<=',
                                now()
                            );
                    })
                    ->oldest('last_activity_at')
                    ->first();

                if (! $user) {
                    $guestAccountsCount = User::query()->guestAccount()->count();

                    if ($guestAccountsCount >= User::MAX_GUEST_ACCOUNTS) {
                        return null;
                    }

                    $user = User::create([
                        'name' => 'Guest User '.$guestAccountsCount + 1,
                        'email' => 'guest-'.$guestAccountsCount + 1 .'@example.com',
                        'is_guest_account' => true,
                    ]);
                }

                $token = Str::random(64);

                $user->update([
                    'guest_lease_token' => hash('sha256', $token),
                    'guest_lease_expires_at' => now()->addMinutes(30),
                    'last_activity_at' => now(),
                ]);

                return [$user, $token];
            });

        if (! $user) {
            return back()->with(
                'error',
                __('No guest accounts are currently available. Please try again later.')
            );
        }

        Auth::login($user);

        $request->session()->regenerate();

        $request->session()->put('guest_lease_token', $token);

        return to_route('dashboard');
    }
}
