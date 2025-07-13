<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Order\StoreOrderRequest;
use App\Models\User;
use App\Models\V1\Order;
use App\Services\V1\OrderPlacementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{

    public function __construct(protected OrderPlacementService $orderPlacementService) {}

    /**
     * Display a listing of the authenticated user's orders.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()->orders()->latest()->paginate(15);
        return response()->json($orders);
    }

    /**
     * Store new order.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = config('app.without_auth') ? User::find(1) : $request->user();
        $order = $this->orderPlacementService->placeOrder($user, $validatedData);

        if (!$order) {
            return response()->json([
                'message' => 'This location is outside our delivery area.'
            ], 422);
        }
        return response()->json($order, 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        Gate::authorize('view', $order);
        return response()->json($order);
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order): JsonResponse
    {
        Gate::authorize('cancel', $order);
        $cancelledOrder = $this->orderPlacementService->cancelOrder($order);
        return response()->json($cancelledOrder);
    }


    /**
     * Get the current location of the delivery person assigned to an order.
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function getDeliveryStatus(Order $order): JsonResponse
    {
        Gate::authorize('trackDelivery', $order);

        if (!$order->deliveryMan) {
            return response()->json(['message' => 'No delivery person has been assigned to this order yet.'], 404);
        }

        return response()->json([
            'current_latitude' => $order->deliveryMan->current_latitude,
            'current_longitude' => $order->deliveryMan->current_longitude,
            'location_updated_at' => $order->deliveryMan->location_updated_at,
        ]);
    }
}
