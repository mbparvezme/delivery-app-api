<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Models\V1\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'restaurant_id' => Restaurant::factory(),
            'delivery_address' => fake()->address(),
            'delivery_latitude' => fake()->latitude(),
            'delivery_longitude' => fake()->longitude(),
            'status' => 'pending',
            'total_amount' => fake()->randomFloat(2, 10, 200),
        ];
    }
}
