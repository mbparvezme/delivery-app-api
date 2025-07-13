<?php

namespace App\Providers;

use App\Models\V1\DeliveryZone;
use App\Policies\V1\DeliveryZonePolicy;

use App\Models\V1\Order;
use App\Policies\V1\OrderPolicy;

use App\Models\V1\OrderAssignment;
use App\Models\V1\Restaurant;
use App\Policies\V1\OrderAssignmentPolicy;
use App\Policies\V1\RestaurantPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Restaurant::class => RestaurantPolicy::class,
        DeliveryZone::class => DeliveryZonePolicy::class,
        Order::class => OrderPolicy::class,
        OrderAssignment::class => OrderAssignmentPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
