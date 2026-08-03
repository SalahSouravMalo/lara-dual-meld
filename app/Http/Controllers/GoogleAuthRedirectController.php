<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Socialite;

class GoogleAuthRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return Socialite::driver('google')->redirect();
    }
}
