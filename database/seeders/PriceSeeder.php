<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Price;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [
            ['type' => 'regular', 'price' => 45000, 'day_type' => 'weekday', 'description' => 'Harga regular hari biasa', 'created_by' => 1],
            ['type' => 'regular', 'price' => 55000, 'day_type' => 'weekend', 'description' => 'Harga regular akhir pekan', 'created_by' => 1],
            ['type' => 'premium', 'price' => 65000, 'day_type' => 'weekday', 'description' => 'Harga premium hari biasa', 'created_by' => 1],
            ['type' => 'premium', 'price' => 75000, 'day_type' => 'weekend', 'description' => 'Harga premium akhir pekan', 'created_by' => 1],
            ['type' => 'vip', 'price' => 85000, 'day_type' => 'weekday', 'description' => 'Harga VIP hari biasa', 'created_by' => 1],
            ['type' => 'vip', 'price' => 100000, 'day_type' => 'weekend', 'description' => 'Harga VIP akhir pekan', 'created_by' => 1]
        ];

        foreach ($prices as $priceData) {
            Price::firstOrCreate([
                'type' => $priceData['type'],
                'day_type' => $priceData['day_type']
            ], $priceData);
        }
    }
}