<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\Order;
use App\Models\V1\OrderAssignment;
use App\Services\V1\DeliveryAssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class DeliveryAssignmentController extends Controller
{

    public function __construct(protected DeliveryAssignmentService $assignmentService) {}


    public function assign(Order $order): JsonResponse
    {

        // return response()->json($order->restaurant);

        Gate::authorize('assign', $order->restaurant);

        $assignment = $this->assignmentService->assignOrderToNearest($order);
        if (!$assignment) {
            return response()->json([
                'message' => 'No available delivery personnel found within the search radius.'
            ], 404);
        }

        return response()->json([
            'message' => 'Order has been assigned to the nearest delivery person.',
            'assignment' => $assignment
        ], 200);
    }

    /**
     * Handle a delivery person accepting an assignment.
     *
     * @param OrderAssignment $order_assignment
     * @return JsonResponse
     */
    public function accept(OrderAssignment $order_assignment): JsonResponse
    {
        Gate::authorize('respond', $order_assignment);

        $this->assignmentService->acceptAssignment($order_assignment);
        return response()->json(['message' => 'Assignment accepted successfully.']);
    }

    /**
     * Handle a delivery person rejecting an assignment.
     *
     * @param OrderAssignment $order_assignment
     * @return JsonResponse
     */
    public function reject(OrderAssignment $order_assignment): JsonResponse
    {
        Gate::authorize('respond', $order_assignment);

        $nextAssignment = $this->assignmentService->rejectAssignment($order_assignment);
        if (!$nextAssignment) {
            return response()->json(['message' => 'Assignment rejected. No other delivery personnel found.']);
        }
        return response()->json(['message' => 'Assignment rejected. Searching for the next delivery person.']);
    }

}
