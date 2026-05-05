<?php

use App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::post('register', [Api\Auth\RegisteredUserController::class, 'store']);
Route::post('login', [Api\Auth\AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [Api\Auth\AuthenticatedSessionController::class, 'destroy']);

    Route::get('user/{user}', fn (User $user) => $user->toArray());
});
