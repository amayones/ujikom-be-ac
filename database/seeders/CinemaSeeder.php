<?php

namespace Database\Seeders;

use App\Models\Film;
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
            'capacity' => 50,
            'created_by' => 1
        ]);

        $studio2 = Studio::create([
            'name' => 'Studio 2',
            'capacity' => 30,
            'created_by' => 1
        ]);

        // Create Seats for Studio 1
        for ($i = 1; $i <= 50; $i++) {
            StudioSeat::create([
                'studio_id' => $studio1->id,
                'seat_code' => 'A' . $i
            ]);
        }

        // Create Seats for Studio 2
        for ($i = 1; $i <= 30; $i++) {
            StudioSeat::create([
                'studio_id' => $studio2->id,
                'seat_code' => 'B' . $i
            ]);
        }

        // Create Prices
        Price::create([
            'type' => 'Weekday',
            'price' => 35000,
            'created_by' => 1
        ]);

        Price::create([
            'type' => 'Weekend',
            'price' => 45000,
            'created_by' => 1
        ]);

        // Create Films
        Film::create([
            'title' => 'Avengers: Endgame',
            'genre' => 'Action, Adventure',
            'duration' => 181,
            'description' => 'The epic conclusion to the Infinity Saga',
            'status' => 'play_now',
            'poster' => 'avengers-endgame.jpg',
            'created_by' => 1
        ]);

        Film::create([
            'title' => 'Spider-Man: No Way Home',
            'genre' => 'Action, Adventure',
            'duration' => 148,
            'description' => 'Spider-Man faces his greatest challenge',
            'status' => 'coming_soon',
            'poster' => 'spiderman-nwh.jpg',
            'created_by' => 1
        ]);
    }
}