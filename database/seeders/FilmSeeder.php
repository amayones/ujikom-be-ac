<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        $films = [
            // Now Playing
            [
                'title' => 'Spider-Man: No Way Home',
                'description' => 'Peter Parker\'s secret identity is revealed to the entire world. Desperate for help, Peter turns to Doctor Strange to make the world forget that he is Spider-Man.',
                'genre' => 'Action, Adventure, Sci-Fi',
                'duration' => 148,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Spider-Man',
                'price' => 50000,
                'status' => 'now_playing'
            ],
            [
                'title' => 'The Batman',
                'description' => 'Batman ventures into Gotham City\'s underworld when a sadistic killer leaves behind a trail of cryptic clues.',
                'genre' => 'Action, Crime, Drama',
                'duration' => 176,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Batman',
                'price' => 55000,
                'status' => 'now_playing'
            ],
            [
                'title' => 'Top Gun: Maverick',
                'description' => 'After thirty years, Maverick is still pushing the envelope as a top naval aviator.',
                'genre' => 'Action, Drama',
                'duration' => 130,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Top+Gun',
                'price' => 58000,
                'status' => 'now_playing'
            ],
            // Coming Soon
            [
                'title' => 'Doctor Strange 2',
                'description' => 'Doctor Strange unleashes an unspeakable evil while trying to protect America Chavez.',
                'genre' => 'Action, Fantasy, Horror',
                'duration' => 126,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Dr+Strange',
                'price' => 60000,
                'status' => 'coming_soon'
            ],
            [
                'title' => 'Jurassic World Dominion',
                'description' => 'Dinosaurs now live alongside humans across the world. This fragile balance will reshape the future.',
                'genre' => 'Action, Adventure, Sci-Fi',
                'duration' => 147,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Jurassic',
                'price' => 52000,
                'status' => 'coming_soon'
            ],
            [
                'title' => 'Avatar: The Way of Water',
                'description' => 'Jake Sully lives with his newfound family formed on the extrasolar moon Pandora.',
                'genre' => 'Action, Adventure, Drama',
                'duration' => 192,
                'rating' => 'PG-13',
                'poster_url' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Avatar',
                'price' => 65000,
                'status' => 'coming_soon'
            ]
        ];

        foreach ($films as $filmData) {
            Film::firstOrCreate(['title' => $filmData['title']], $filmData);
        }
    }
}