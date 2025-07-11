<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Zone\StoreDeliveryZoneRequest;
use App\Http\Requests\V1\Zone\UpdateDeliveryZoneRequest;
use App\Models\V1\DeliveryZone;
use App\Models\V1\Restaurant;
use App\Services\V1\DeliveryZoneService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{

    public function __construct(protected DeliveryZoneService $deliveryZoneService){}

    /**
     * Display a listing of the delivery zones for a restaurant.
     */
    public function index(Restaurant $restaurant): JsonResponse
    {
        // Gate::authorize('viewAny', $restaurant);
        return response()->json($restaurant->deliveryZones);
    }

    /**
     * Store a newly created delivery zone for a restaurant.
     */
    public function store(StoreDeliveryZoneRequest $request, Restaurant $restaurant): JsonResponse
    {
        // Gate::authorize('create', $restaurant);
        $validatedData = $request->validated();
        $deliveryZone = $this->deliveryZoneService->createZone($restaurant, $validatedData);
        return response()->json($deliveryZone, 201); // 201 Created
    }

    /**
     * Display the specified delivery zone.
     */
    public function show(Request $request, Restaurant $restaurant, DeliveryZone $zone): JsonResponse
    {
        // Gate::authorize('view', [$restaurant, $zone]);
        return response()->json($zone);
    }

    /**
     * Update the specified delivery zone in storage.
     */
    public function update(UpdateDeliveryZoneRequest $request, Restaurant $restaurant, DeliveryZone $zone): JsonResponse
    {
        // Gate::authorize('update', [$restaurant, $zone]);
        $validatedData = $request->validated();
        $updatedZone = $this->deliveryZoneService->updateZone($zone, $validatedData);
        return response()->json($updatedZone);
    }

    /**
     * Remove the specified delivery zone from storage.
     */
    public function destroy(Request $request, Restaurant $restaurant, DeliveryZone $zone): JsonResponse
    {
        // Gate::authorize('delete', [$restaurant, $zone]);
        $this->deliveryZoneService->deleteZone($zone);
        return response()->json(null, 204);
    }

}