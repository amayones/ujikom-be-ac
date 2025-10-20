<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Film;
use App\Models\Studio;
use App\Models\StudioSeat;
use App\Models\Price;
use App\Models\Schedule;

class OptimizedSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $users = [
            ['name' => 'Owner Cinema', 'email' => 'owner@cinema.com', 'password' => Hash::make('password'), 'role' => 'owner'],
            ['name' => 'Admin Cinema', 'email' => 'admin@cinema.com', 'password' => Hash::make('password'), 'role' => 'admin'],
            ['name' => 'Kasir 1', 'email' => 'kasir1@cinema.com', 'password' => Hash::make('password'), 'role' => 'cashier'],
            ['name' => 'Kasir 2', 'email' => 'kasir2@cinema.com', 'password' => Hash::make('password'), 'role' => 'cashier'],
            ['name' => 'Customer Test', 'email' => 'customer@test.com', 'password' => Hash::make('password'), 'role' => 'customer'],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // Films
        $films = [
            ['title' => 'Spider-Man: No Way Home', 'genre' => 'Action, Adventure', 'duration' => 148, 'description' => 'Peter Parker fights multiverse villains', 'status' => 'now_playing', 'director' => 'Jon Watts', 'release_date' => '2021-12-15'],
            ['title' => 'The Batman', 'genre' => 'Action, Crime', 'duration' => 176, 'description' => 'Batman investigates corruption in Gotham', 'status' => 'now_playing', 'director' => 'Matt Reeves', 'release_date' => '2022-03-04'],
            ['title' => 'Avatar 2', 'genre' => 'Action, Adventure', 'duration' => 192, 'description' => 'Jake Sully returns to Pandora', 'status' => 'coming_soon', 'director' => 'James Cameron', 'release_date' => '2024-12-16'],
        ];

        foreach ($films as $filmData) {
            Film::firstOrCreate(['title' => $filmData['title']], $filmData);
        }

        // Studios
        $studios = [
            ['name' => 'Studio 1', 'capacity' => 50, 'created_by' => 1],
            ['name' => 'Studio 2', 'capacity' => 45, 'created_by' => 1],
            ['name' => 'Studio 3', 'capacity' => 60, 'created_by' => 1],
        ];

        foreach ($studios as $studioData) {
            $studio = Studio::firstOrCreate(['name' => $studioData['name']], $studioData);
            
            // Create seats for each studio
            for ($i = 1; $i <= $studio->capacity; $i++) {
                $row = chr(65 + floor(($i - 1) / 10)); // A, B, C, etc.
                $number = (($i - 1) % 10) + 1;
                $seatCode = $row . $number;
                
                StudioSeat::firstOrCreate([
                    'studio_id' => $studio->id,
                    'seat_code' => $seatCode
                ]);
            }
        }

        // Prices
        $prices = [
            ['type' => 'regular', 'price' => 45000, 'day_type' => 'weekday', 'description' => 'Regular weekday'],
            ['type' => 'regular', 'price' => 55000, 'day_type' => 'weekend', 'description' => 'Regular weekend'],
            ['type' => 'premium', 'price' => 65000, 'day_type' => 'weekday', 'description' => 'Premium weekday'],
            ['type' => 'premium', 'price' => 75000, 'day_type' => 'weekend', 'description' => 'Premium weekend'],
        ];

        foreach ($prices as $priceData) {
            Price::firstOrCreate(['type' => $priceData['type'], 'day_type' => $priceData['day_type']], $priceData);
        }

        // Schedules
        $films = Film::where('status', 'now_playing')->get();
        $studios = Studio::all();
        $prices = Price::all();
        $times = ['10:00', '13:00', '16:00', '19:00'];

        foreach ($films as $film) {
            foreach ($times as $time) {
                Schedule::firstOrCreate([
                    'film_id' => $film->id,
                    'studio_id' => $studios->random()->id,
                    'date' => now()->addDays(rand(0, 7))->format('Y-m-d'),
                    'time' => $time,
                    'price_id' => $prices->random()->id,
                    'created_by' => 1
                ]);
            }
        }
    }
}