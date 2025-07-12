<?php

namespace App\Providers;

use App\Models\DeliveryZone;
use App\Policies\V1\DeliveryZonePolicy;

use App\Models\OrderAssignment;
use App\Policies\V1\OrderAssignmentPolicy;

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
