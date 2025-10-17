<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Film;
use App\Models\Studio;
use App\Models\Schedule;
use App\Models\Price;

class CompleteDataSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@cinema.com', 'password' => Hash::make('password'), 'role' => 'admin'],
            ['name' => 'Owner User', 'email' => 'owner@cinema.com', 'password' => Hash::make('password'), 'role' => 'owner'],
            ['name' => 'Cashier User', 'email' => 'cashier@cinema.com', 'password' => Hash::make('password'), 'role' => 'cashier'],
            ['name' => 'Customer One', 'email' => 'customer1@cinema.com', 'password' => Hash::make('password'), 'role' => 'customer'],
            ['name' => 'Customer Two', 'email' => 'customer2@cinema.com', 'password' => Hash::make('password'), 'role' => 'customer'],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // Films
        $films = [
            [
                'title' => 'Spider-Man: No Way Home',
                'description' => 'Peter Parker\'s secret identity is revealed to the entire world.',
                'genre' => 'Action, Adventure',
                'duration' => 148,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Spider-Man',
                'price' => 50000
            ],
            [
                'title' => 'The Batman',
                'description' => 'Batman ventures into Gotham City\'s underworld.',
                'genre' => 'Action, Crime',
                'duration' => 176,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Batman',
                'price' => 55000
            ],
            [
                'title' => 'Doctor Strange 2',
                'description' => 'Doctor Strange unleashes an unspeakable evil.',
                'genre' => 'Action, Fantasy',
                'duration' => 126,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Dr+Strange',
                'price' => 60000
            ],
            [
                'title' => 'Top Gun: Maverick',
                'description' => 'After thirty years, Maverick is still pushing the envelope.',
                'genre' => 'Action, Drama',
                'duration' => 130,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Top+Gun',
                'price' => 58000
            ],
            [
                'title' => 'Jurassic World Dominion',
                'description' => 'Dinosaurs now live alongside humans across the world.',
                'genre' => 'Action, Adventure',
                'duration' => 147,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Jurassic',
                'price' => 52000
            ]
        ];

        foreach ($films as $filmData) {
            Film::firstOrCreate(['title' => $filmData['title']], $filmData);
        }

        // Studios
        $studios = [
            ['name' => 'Studio 1', 'capacity' => 50, 'type' => 'Regular'],
            ['name' => 'Studio 2', 'capacity' => 45, 'type' => 'Regular'],
            ['name' => 'Studio 3', 'capacity' => 60, 'type' => 'Premium'],
            ['name' => 'Studio 4', 'capacity' => 40, 'type' => 'VIP'],
        ];

        foreach ($studios as $studioData) {
            Studio::firstOrCreate(['name' => $studioData['name']], $studioData);
        }

        // Prices
        $prices = [
            ['type' => 'Regular', 'price' => 50000, 'day_type' => 'weekday'],
            ['type' => 'Regular', 'price' => 60000, 'day_type' => 'weekend'],
            ['type' => 'Premium', 'price' => 75000, 'day_type' => 'weekday'],
            ['type' => 'Premium', 'price' => 85000, 'day_type' => 'weekend'],
            ['type' => 'VIP', 'price' => 100000, 'day_type' => 'weekday'],
            ['type' => 'VIP', 'price' => 120000, 'day_type' => 'weekend'],
        ];

        foreach ($prices as $priceData) {
            Price::firstOrCreate([
                'type' => $priceData['type'],
                'day_type' => $priceData['day_type']
            ], $priceData);
        }

        // Schedules
        $films = Film::all();
        $studios = Studio::all();
        $times = ['10:00', '13:00', '16:00', '19:00', '21:30'];

        foreach ($films->take(3) as $film) {
            foreach ($times as $time) {
                $studio = $studios->random();
                Schedule::firstOrCreate([
                    'film_id' => $film->id,
                    'studio_id' => $studio->id,
                    'show_date' => now()->addDays(rand(0, 7))->format('Y-m-d'),
                    'show_time' => $time
                ], [
                    'film_id' => $film->id,
                    'studio_id' => $studio->id,
                    'show_date' => now()->addDays(rand(0, 7))->format('Y-m-d'),
                    'show_time' => $time,
                    'price' => $film->price,
                    'available_seats' => $studio->capacity - rand(5, 15)
                ]);
            }
        }
    }
}