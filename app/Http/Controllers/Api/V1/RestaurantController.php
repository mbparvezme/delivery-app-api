<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\V1\Restaurant;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        $restaurants = Restaurant::latest()->paginate(15);
        return response()->json($restaurants);
    }

    public function store(StoreRestaurantRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $restaurant = Restaurant::create($validatedData);
        return response()->json($restaurant, 201);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        return response()->json($restaurant);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): JsonResponse
    {
        if ($request->user()->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $validatedData = $request->validated();
        $restaurant->update($validatedData);
        return response()->json($restaurant);
    }

    public function destroy(Request $request, Restaurant $restaurant): JsonResponse
    {
        if ($request->user()->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $restaurant->delete();
        return response()->json(null, 204);
    }

}
