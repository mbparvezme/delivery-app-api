<?php

namespace Tests\Unit\V1;

use App\Models\V1\DeliveryZone;
use App\Models\V1\Restaurant;
use App\Services\V1\DeliveryZoneService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class DeliveryZoneServiceTest extends TestCase
{

    private DeliveryZoneService $deliveryZoneService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deliveryZoneService = new DeliveryZoneService();
    }

    /** @test */
    public function deliveryInsideRadiusTest(): void
    {
        // : Set up our test data
        $restaurant = new Restaurant(['latitude' => 23.8103, 'longitude' => 90.4125]);
        $zone = new DeliveryZone(['type' => 'radius', 'value' => 5000]);
        $zone->setRelation('restaurant', $restaurant);

        $zones = new Collection([$zone]);
        $customerLat = 23.8110;
        $customerLng = 90.4130;

        $isInside = $this->deliveryZoneService->isLocationInServiceableArea($customerLat, $customerLng, $zones);
        $this->assertTrue($isInside);
    }

    /** @test */
    public function deliveryOutsideRadiusTest(): void
    {
        $restaurant = new Restaurant(['latitude' => 23.8103, 'longitude' => 90.4125]);
        $zone = new DeliveryZone(['type' => 'radius', 'value' => 5000]);
        $zone->setRelation('restaurant', $restaurant);
        $zones = new Collection([$zone]);
        $customerLat = 24.0; // A point far away
        $customerLng = 91.0;

        $isInside = $this->deliveryZoneService->isLocationInServiceableArea($customerLat, $customerLng, $zones);
        $this->assertFalse($isInside);
    }

    /** @test */
    public function deliveryInsidePolygonTest(): void
    {
        $polygonPoints = [
            ['lat' => 23.80, 'lng' => 90.40],
            ['lat' => 23.85, 'lng' => 90.40],
            ['lat' => 23.85, 'lng' => 90.45],
            ['lat' => 23.80, 'lng' => 90.45],
        ];
        $zone = new DeliveryZone(['type' => 'polygon', 'value' => $polygonPoints]);
        $zones = new Collection([$zone]);
        $customerLat = 23.82;
        $customerLng = 90.42;

        $isInside = $this->deliveryZoneService->isLocationInServiceableArea($customerLat, $customerLng, $zones);
        $this->assertTrue($isInside);
    }

    /** @test */
    public function deliveryOutsidePolygonTest(): void
    {
        $polygonPoints = [
            ['lat' => 23.80, 'lng' => 90.40],
            ['lat' => 23.85, 'lng' => 90.40],
            ['lat' => 23.85, 'lng' => 90.45],
            ['lat' => 23.80, 'lng' => 90.45],
        ];
        $zone = new DeliveryZone(['type' => 'polygon', 'value' => $polygonPoints]);
        $zones = new Collection([$zone]);
        $customerLat = 24.0;
        $customerLng = 91.0;

        $isInside = $this->deliveryZoneService->isLocationInServiceableArea($customerLat, $customerLng, $zones);
        $this->assertFalse($isInside);
    }

}
