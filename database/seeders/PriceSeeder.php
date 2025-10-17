<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Price;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [
            // Regular Studio
            [
                'type' => 'Regular',
                'price' => 45000,
                'day_type' => 'weekday',
                'description' => 'Harga regular hari biasa'
            ],
            [
                'type' => 'Regular',
                'price' => 55000,
                'day_type' => 'weekend',
                'description' => 'Harga regular akhir pekan'
            ],
            // Premium Studio
            [
                'type' => 'Premium',
                'price' => 65000,
                'day_type' => 'weekday',
                'description' => 'Harga premium hari biasa'
            ],
            [
                'type' => 'Premium',
                'price' => 75000,
                'day_type' => 'weekend',
                'description' => 'Harga premium akhir pekan'
            ],
            // VIP Studio
            [
                'type' => 'VIP',
                'price' => 85000,
                'day_type' => 'weekday',
                'description' => 'Harga VIP hari biasa'
            ],
            [
                'type' => 'VIP',
                'price' => 100000,
                'day_type' => 'weekend',
                'description' => 'Harga VIP akhir pekan'
            ]
        ];

        foreach ($prices as $priceData) {
            Price::firstOrCreate([
                'type' => $priceData['type'],
                'day_type' => $priceData['day_type']
            ], $priceData);
        }
    }
}