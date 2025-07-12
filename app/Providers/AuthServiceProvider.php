<?php

namespace App\Providers;

use App\Models\V1\DeliveryZone;
use App\Policies\V1\DeliveryZonePolicy;
use App\Models\V1\Order;
use App\Policies\V1\OrderPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        DeliveryZone::class => DeliveryZonePolicy::class,
        Order::class => OrderPolicy::class,
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
