<?php

use App\Http\Controllers\GoogleAuthCallbackController;
use App\Http\Controllers\GoogleAuthRedirectController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');

    Route::prefix('auth/google')->name('auth.google.')->group(function () {
        Route::get('/redirect', GoogleAuthRedirectController::class)->name('redirect');
        Route::get('/callback', GoogleAuthCallbackController::class)->name('callback');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return 'logged in..';
    })->name('dashboard');
});
