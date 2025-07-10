<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\RestaurantController;


Route::prefix('v1')->group(function () {
    // Public routes: Just dor demo purpose
    Route::get('restaurants', [RestaurantController::class, 'index']);
    Route::get('restaurants/{restaurant}', [RestaurantController::class, 'show']);

    // Authenticated routes
    Route::post('restaurants', [RestaurantController::class, 'store']);
    Route::put('restaurants/{restaurant}', [RestaurantController::class, 'update']);
    Route::delete('restaurants/{restaurant}', [RestaurantController::class, 'destroy']);
    Route::middleware('auth:sanctum')->group(function () {
    });
});