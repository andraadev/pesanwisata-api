<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('admin/users', UserController::class);
    Route::apiResource('admin/destinations', DestinationController::class);
    Route::get('admin/destination/{slug}', [DestinationController::class, 'show']);
    Route::apiResource('admin/booking', BookingController::class);
});
