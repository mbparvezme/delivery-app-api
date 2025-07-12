<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Delivery\UpdateDeliveryPersonStatusRequest;
use App\Services\V1\DeliveryPersonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryPersonController extends Controller
{

    public function __construct(protected DeliveryPersonService $deliveryPersonService) {}


    /**
     * Handle the request to update a delivery person's status.
     *
     * @param UpdateDeliveryPersonStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(UpdateDeliveryPersonStatusRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $user = $request->user();
        $deliveryMan = $this->deliveryPersonService->updateStatus($user, $validatedData);
        return response()->json($deliveryMan);
    }


}
