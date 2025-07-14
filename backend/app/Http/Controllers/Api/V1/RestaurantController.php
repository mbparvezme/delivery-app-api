<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\V1\Restaurant\StoreRestaurantRequest;
use App\Http\Requests\V1\Restaurant\UpdateRestaurantRequest;
use Illuminate\Http\Request;
use App\Models\V1\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class RestaurantController extends BaseController
{

    public function index(): JsonResponse
    {
        $restaurants = Restaurant::select(["id", "user_id", "name", "address", "latitude", "longitude"])->latest()->active()->paginate(15);
        return response()->json($restaurants);
    }

    public function show($restaurant): JsonResponse
    {
        $data = Restaurant::select("id", "user_id", "name", "address", "latitude", "longitude")
            ->with(['deliveryZones:id,restaurant_id,name,type,value'])->active()
            ->findOrFail($restaurant);
        return response()->json($data);
    }

    public function store(StoreRestaurantRequest $request): JsonResponse
    {
        Gate::authorize('create', Restaurant::class);
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $restaurant = Restaurant::create($validatedData);
        return response()->json($restaurant, 201);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): JsonResponse
    {
        Gate::authorize('update', $restaurant);

        $validatedData = $request->validated();
        $restaurant->update($validatedData);
        return response()->json($restaurant);
    }

    public function destroy(Request $request, Restaurant $restaurant): JsonResponse
    {
        Gate::authorize('delete', $restaurant);

        $restaurant->deactivate();
        return response()->json(null, 204);
    }

}