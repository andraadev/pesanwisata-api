<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/users', UserController::class);
Route::apiResource('/destinations', DestinationController::class);
Route::apiResource('admin/booking', BookingController::class);
