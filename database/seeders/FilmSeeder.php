<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        $films = [
            [
                'title' => 'Spider-Man: No Way Home',
                'description' => 'Peter Parker\'s secret identity is revealed to the entire world. Desperate for help, Peter turns to Doctor Strange to make the world forget that he is Spider-Man.',
                'genre' => 'Action, Adventure, Sci-Fi',
                'duration' => 148,
                'status' => 'now_playing',
                'poster' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Spider-Man',
                'director' => 'Jon Watts',
                'release_date' => '2021-12-15',
                'created_by' => 1
            ],
            [
                'title' => 'The Batman',
                'description' => 'Batman ventures into Gotham City\'s underworld when a sadistic killer leaves behind a trail of cryptic clues.',
                'genre' => 'Action, Crime, Drama',
                'duration' => 176,
                'status' => 'now_playing',
                'poster' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Batman',
                'director' => 'Matt Reeves',
                'release_date' => '2022-03-04',
                'created_by' => 1
            ],
            [
                'title' => 'Top Gun: Maverick',
                'description' => 'After thirty years, Maverick is still pushing the envelope as a top naval aviator.',
                'genre' => 'Action, Drama',
                'duration' => 130,
                'status' => 'now_playing',
                'poster' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Top+Gun',
                'director' => 'Joseph Kosinski',
                'release_date' => '2022-05-27',
                'created_by' => 1
            ],
            [
                'title' => 'Doctor Strange 2',
                'description' => 'Doctor Strange unleashes an unspeakable evil while trying to protect America Chavez.',
                'genre' => 'Action, Fantasy, Horror',
                'duration' => 126,
                'status' => 'coming_soon',
                'poster' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Dr+Strange',
                'director' => 'Sam Raimi',
                'release_date' => '2024-05-06',
                'created_by' => 1
            ],
            [
                'title' => 'Jurassic World Dominion',
                'description' => 'Dinosaurs now live alongside humans across the world. This fragile balance will reshape the future.',
                'genre' => 'Action, Adventure, Sci-Fi',
                'duration' => 147,
                'status' => 'coming_soon',
                'poster' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Jurassic',
                'director' => 'Colin Trevorrow',
                'release_date' => '2024-06-10',
                'created_by' => 1
            ],
            [
                'title' => 'Avatar: The Way of Water',
                'description' => 'Jake Sully lives with his newfound family formed on the extrasolar moon Pandora.',
                'genre' => 'Action, Adventure, Drama',
                'duration' => 192,
                'status' => 'coming_soon',
                'poster' => 'https://via.placeholder.com/300x450/1f2937/ffffff?text=Avatar',
                'director' => 'James Cameron',
                'release_date' => '2024-12-16',
                'created_by' => 1
            ]
        ];

        foreach ($films as $filmData) {
            Film::updateOrCreate(
                ['title' => $filmData['title']], 
                $filmData
            );
        }
    }
}