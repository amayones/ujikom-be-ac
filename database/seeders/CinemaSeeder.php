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

        // Create Films
        Film::create([
            'title' => 'Pengabdi Setan 2: Communion',
            'genre' => 'Horror, Thriller',
            'duration' => 119,
            'description' => 'Keluarga Suwono kembali dihadapkan dengan teror yang lebih mengerikan',
            'status' => 'play_now',
            'poster' => 'pengabdi-setan-2.jpg',
            'created_by' => 1
        ]);

        Film::create([
            'title' => 'KKN di Desa Penari',
            'genre' => 'Horror, Mystery',
            'duration' => 175,
            'description' => 'Enam mahasiswa KKN mengalami teror mistis di desa terpencil',
            'status' => 'play_now',
            'poster' => 'kkn-desa-penari.jpg',
            'created_by' => 1
        ]);

        Film::create([
            'title' => 'Dilan 1991',
            'genre' => 'Romance, Drama',
            'duration' => 121,
            'description' => 'Kelanjutan kisah cinta Dilan dan Milea di tahun 1991',
            'status' => 'coming_soon',
            'poster' => 'dilan-1991.jpg',
            'created_by' => 1
        ]);
    }
}