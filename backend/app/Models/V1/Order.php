<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BaseModel;
use App\Models\User;

class Order extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'delivery_man_id',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'status',
        'total_amount',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the customer who placed the order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the restaurant for the order.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the delivery man assigned to the order.
     */
    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class);
    }

    /**
     * Get the assignment attempts for this order.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(OrderAssignment::class);
    }
}