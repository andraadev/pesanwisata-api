<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::apiResource('/users', UserController::class)->only('index');

    Route::apiResource('/destinations', DestinationController::class)
        ->only(['index', 'show']);
});


Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
});

Route::middleware(['auth:sanctum', 'role:Admin', 'throttle:admin'])->group(function () {
    Route::apiResource('admin/users', UserController::class)->missing(function () {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    });
    Route::apiResource('admin/destinations', DestinationController::class)->missing(function () {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    });;
    // Route::get('admin/destination/{slug}', [DestinationController::class, 'show']);
    Route::apiResource('admin/booking', BookingController::class);
});

Route::middleware([
    'auth:sanctum',
    'role:User',
    'throttle:api',
])->group(function () {
    Route::apiResource('/booking', BookingController::class)->only(['index', 'store']);
});
