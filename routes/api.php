<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\DeliveryPersonController;
use App\Http\Controllers\Api\V1\DeliveryZoneController;
use App\Http\Controllers\Api\V1\RestaurantController;
use App\Http\Controllers\Api\V1\DeliveryAssignmentController;


Route::prefix('v1')->group(function () {
    // Public routes: Just dor demo purpose
    Route::get('restaurants', [RestaurantController::class, 'index']);
    Route::get('restaurants/{restaurant}', [RestaurantController::class, 'show']);

    // Authenticated routes
    // Route::middleware('auth:sanctum')->group(function () {
        Route::post('restaurants', [RestaurantController::class, 'store']);
        Route::put('restaurants/{restaurant}', [RestaurantController::class, 'update']);
        Route::delete('restaurants/{restaurant}', [RestaurantController::class, 'destroy']);

        Route::prefix('restaurants/{restaurant}')->as('restaurants.')->group(function () {
            Route::apiResource('zones', DeliveryZoneController::class);
        });

        Route::put('delivery-person/status', [DeliveryPersonController::class, 'updateStatus']);

        Route::post('orders/{order}/assign', [DeliveryAssignmentController::class, 'assign']);

        Route::prefix('assignments/{order_assignment}')->as('assignments.')->group(function () {
            Route::post('accept', [DeliveryAssignmentController::class, 'accept'])->name('accept');
            Route::post('reject', [DeliveryAssignmentController::class, 'reject'])->name('reject');
        });

    // });
});