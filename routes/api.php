<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('register', [Api\Auth\RegisteredUserController::class, 'store']);

    Route::post('login', [Api\Auth\AuthenticatedSessionController::class, 'store']);
});
