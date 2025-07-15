<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeliveryPersonController;
use App\Http\Controllers\Api\V1\DeliveryZoneController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\RestaurantController;
use App\Http\Controllers\Api\V1\DeliveryAssignmentController;

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::get('restaurants', [RestaurantController::class, 'index']);
    Route::get('restaurants/{restaurant}', [RestaurantController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('restaurants', [RestaurantController::class, 'store']);
        Route::put('restaurants/{restaurant}', [RestaurantController::class, 'update']);
        Route::delete('restaurants/{restaurant}', [RestaurantController::class, 'destroy']);

        Route::prefix('restaurants/{restaurant}')->as('restaurants.')->group(function () {
            Route::apiResource('zones', DeliveryZoneController::class);
        });

        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);

        Route::put('delivery-person/status', [DeliveryPersonController::class, 'updateStatus']);

        Route::post('orders/{order}/assign', [DeliveryAssignmentController::class, 'assign']);

        Route::prefix('assignments/{order_assignment}')->as('assignments.')->group(function () {
            Route::post('accept', [DeliveryAssignmentController::class, 'accept'])->name('accept');
            Route::post('reject', [DeliveryAssignmentController::class, 'reject'])->name('reject');
        });

        Route::get('orders/{order}/delivery-status', [OrderController::class, 'getDeliveryStatus']);

        Route::post('logout', [AuthController::class, 'logout']);

    });
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
});