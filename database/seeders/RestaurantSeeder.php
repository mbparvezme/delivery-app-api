<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\V1\Restaurant;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurant::insert([
            [
                'id' => 1,
                'user_id' => 2,
                'name' => 'Kacchi Bhai Gulshan',
                'address' => 'Plot 7, Road 45, Gulshan 2, Dhaka',
                'latitude' => 23.7925,
                'longitude' => 90.4125,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'name' => 'Sultan\'s Dine Dhanmondi',
                'address' => 'House 1, Road 11, Dhanmondi, Dhaka',
                'latitude' => 23.7461,
                'longitude' => 90.3742,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'name' => 'Pizza Guy Banani',
                'address' => 'House 22, Road 17, Banani, Dhaka',
                'latitude' => 23.7949,
                'longitude' => 90.4069,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'user_id' => 3,
                'name' => 'Tarka Uttara',
                'address' => 'House 5, Road 1, Sector 6, Uttara, Dhaka',
                'latitude' => 23.8759,
                'longitude' => 90.3979,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
