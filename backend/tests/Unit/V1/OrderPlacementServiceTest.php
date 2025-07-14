<?php

namespace Tests\Unit\Services\V1;

use App\Models\V1\Order;
use App\Models\V1\Restaurant;
use App\Models\User;
use App\Services\V1\DeliveryZoneService;
use App\Services\V1\OrderPlacementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrderPlacementServiceTest extends TestCase
{
    // This trait resets the database after each test, so we start fresh.
    use RefreshDatabase;

    /** @test */
    public function it_successfully_places_an_order_when_location_is_serviceable(): void
    {
        // 1. Arrange
        // Create a mock of the DeliveryZoneService.
        $mockZoneService = Mockery::mock(DeliveryZoneService::class);
        $mockZoneService->shouldReceive('isLocationInServiceableArea')->once()->andReturn(true);

        // **FIX:** Bind the mock into Laravel's service container.
        $this->instance(DeliveryZoneService::class, $mockZoneService);

        // Create a user and a restaurant in the database.
        $customer = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $orderData = [
            'restaurant_id' => $restaurant->id,
            'delivery_address' => '123 Test St',
            'delivery_latitude' => 23.8,
            'delivery_longitude' => 90.4,
            'total_amount' => 99.99,
        ];

        // **FIX:** Resolve the service from the container. Laravel will inject our mock.
        $orderPlacementService = $this->app->make(OrderPlacementService::class);

        // 2. Act
        $result = $orderPlacementService->placeOrder($customer, $orderData);

        // 3. Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_returns_null_when_location_is_not_serviceable(): void
    {
        // 1. Arrange
        $mockZoneService = Mockery::mock(DeliveryZoneService::class);
        $mockZoneService->shouldReceive('isLocationInServiceableArea')->once()->andReturn(false);

        // **FIX:** Bind the mock into Laravel's service container.
        $this->instance(DeliveryZoneService::class, $mockZoneService);

        $customer = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $orderData = [
            'restaurant_id' => $restaurant->id,
            'delivery_address' => '456 Far Away Rd',
            'delivery_latitude' => 50.0,
            'delivery_longitude' => 100.0,
            'total_amount' => 50.00,
        ];

        // **FIX:** Resolve the service from the container.
        $orderPlacementService = $this->app->make(OrderPlacementService::class);

        // 2. Act
        $result = $orderPlacementService->placeOrder($customer, $orderData);

        // 3. Assert
        $this->assertNull($result);
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function it_successfully_cancels_an_order(): void
    {
        // 1. Arrange
        // We don't need a mock for this test, so we can resolve the service directly.
        $orderPlacementService = $this->app->make(OrderPlacementService::class);

        // Create an order with 'pending' status.
        $order = Order::factory()->create(['status' => 'pending']);

        // 2. Act
        $result = $orderPlacementService->cancelOrder($order);

        // 3. Assert
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals('cancelled', $result->status);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
