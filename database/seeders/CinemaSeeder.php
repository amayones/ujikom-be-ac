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
            'nama_studio' => 'Studio 1',
            'kapasitas' => 50,
            'created_by' => 1
        ]);

        $studio2 = Studio::create([
            'nama_studio' => 'Studio 2',
            'kapasitas' => 30,
            'created_by' => 1
        ]);

        // Create Seats for Studio 1
        for ($i = 1; $i <= 50; $i++) {
            StudioSeat::create([
                'studio_id' => $studio1->id,
                'kode_kursi' => 'A' . $i
            ]);
        }

        // Create Seats for Studio 2
        for ($i = 1; $i <= 30; $i++) {
            StudioSeat::create([
                'studio_id' => $studio2->id,
                'kode_kursi' => 'B' . $i
            ]);
        }

        // Create Prices
        Price::create([
            'tipe' => 'Weekday',
            'harga' => 35000,
            'created_by' => 1
        ]);

        Price::create([
            'tipe' => 'Weekend',
            'harga' => 45000,
            'created_by' => 1
        ]);

        // Create Films
        Film::create([
            'judul' => 'Avengers: Endgame',
            'genre' => 'Action, Adventure',
            'durasi' => 181,
            'deskripsi' => 'The epic conclusion to the Infinity Saga',
            'status' => 'play_now',
            'poster' => 'avengers-endgame.jpg',
            'created_by' => 1
        ]);

        Film::create([
            'judul' => 'Spider-Man: No Way Home',
            'genre' => 'Action, Adventure',
            'durasi' => 148,
            'deskripsi' => 'Spider-Man faces his greatest challenge',
            'status' => 'coming_soon',
            'poster' => 'spiderman-nwh.jpg',
            'created_by' => 1
        ]);
    }
}