<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Horror',
            'Romance',
            'Sci-Fi',
            'Thriller',
            'Fantasy',
            'Animation',
            'Crime',
            'Mystery',
            'War',
            'Western',
            'Musical',
            'Documentary',
            'Biography',
            'Family',
            'Sport',
            'History'
        ];

        foreach ($genres as $genre) {
            Genre::create(['name' => $genre]);
        }
    }
}