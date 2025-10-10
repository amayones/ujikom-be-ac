<?php

namespace Database\Seeders;

use App\Models\Studio;
use App\Models\Price;
use App\Models\StudioSeat;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    public function run(): void
    {
        // Create Studios
        $studio1 = Studio::create([
            'name' => 'Studio 1',
            'capacity' => 80,
            'created_by' => 1
        ]);

        $studio2 = Studio::create([
            'name' => 'Studio 2',
            'capacity' => 60,
            'created_by' => 1
        ]);

        // Create Seats for Studio 1 (80 seats in 8x10 grid)
        for ($row = 1; $row <= 8; $row++) {
            for ($col = 1; $col <= 10; $col++) {
                StudioSeat::create([
                    'studio_id' => $studio1->id,
                    'seat_code' => chr(64 + $row) . $col
                ]);
            }
        }

        // Create Seats for Studio 2 (60 seats in 6x10 grid)
        for ($row = 1; $row <= 6; $row++) {
            for ($col = 1; $col <= 10; $col++) {
                StudioSeat::create([
                    'studio_id' => $studio2->id,
                    'seat_code' => chr(64 + $row) . $col
                ]);
            }
        }

        // Create Prices
        Price::create([
            'type' => 'Hari Kerja',
            'price' => 35000,
            'created_by' => 1 
        ]);

        Price::create([
            'type' => 'Akhir Pekan',
            'price' => 45000, 
            'created_by' => 1
        ]);

        // Films will be created by FilmSeeder
    }
}