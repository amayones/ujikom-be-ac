<?php

namespace Database\Seeders;

use App\Models\Film;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        $films = [
            [
                'title' => 'Avengers: Endgame',
                'genre' => 'Action, Adventure, Drama',
                'duration' => 181,
                'description' => 'After the devastating events of Avengers: Infinity War, the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to reverse Thanos\' actions and restore balance to the universe.',
                'status' => 'play_now',
                'poster' => '/ac.jpg',
                'director' => 'Anthony Russo, Joe Russo',
                'release_date' => '2019-04-26',
                'created_by' => 1
            ],
            [
                'title' => 'Spider-Man: No Way Home',
                'genre' => 'Action, Adventure, Sci-Fi',
                'duration' => 148,
                'description' => 'With Spider-Man\'s identity now revealed, Peter asks Doctor Strange for help. When a spell goes wrong, dangerous foes from other worlds start to appear, forcing Peter to discover what it truly means to be Spider-Man.',
                'status' => 'play_now',
                'poster' => '/ac.jpg',
                'director' => 'Jon Watts',
                'release_date' => '2021-12-17',
                'created_by' => 1
            ],
            [
                'title' => 'Top Gun: Maverick',
                'genre' => 'Action, Drama',
                'duration' => 130,
                'description' => 'After thirty years, Maverick is still pushing the envelope as a top naval aviator, but must confront ghosts of his past when he leads TOP GUN\'s elite graduates on a mission that demands the ultimate sacrifice from those chosen to fly it.',
                'status' => 'play_now',
                'poster' => '/ac.jpg',
                'director' => 'Joseph Kosinski',
                'release_date' => '2022-05-27',
                'created_by' => 1
            ],
            [
                'title' => 'Avatar: The Way of Water',
                'genre' => 'Action, Adventure, Drama',
                'duration' => 192,
                'description' => 'Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri and the army of the Na\'vi race to protect their home.',
                'status' => 'coming_soon',
                'poster' => '/ac.jpg',
                'director' => 'James Cameron',
                'release_date' => '2024-12-16',
                'created_by' => 1
            ],
            [
                'title' => 'Black Panther: Wakanda Forever',
                'genre' => 'Action, Adventure, Drama',
                'duration' => 161,
                'description' => 'The people of Wakanda fight to protect their home from intervening world powers as they mourn the death of King T\'Challa.',
                'status' => 'coming_soon',
                'poster' => '/ac.jpg',
                'director' => 'Ryan Coogler',
                'release_date' => '2025-01-15',
                'created_by' => 1
            ]
        ];

        foreach ($films as $film) {
            Film::create($film);
        }
    }
}