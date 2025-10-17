<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        $studios = [
            [
                'name' => 'Studio 1',
                'capacity' => 50,
                'type' => 'Regular',
                'description' => 'Studio regular dengan sound system standar'
            ],
            [
                'name' => 'Studio 2', 
                'capacity' => 45,
                'type' => 'Regular',
                'description' => 'Studio regular dengan layar besar'
            ],
            [
                'name' => 'Studio 3',
                'capacity' => 60,
                'type' => 'Premium',
                'description' => 'Studio premium dengan Dolby Atmos'
            ],
            [
                'name' => 'Studio 4',
                'capacity' => 40,
                'type' => 'VIP',
                'description' => 'Studio VIP dengan kursi recliner'
            ],
            [
                'name' => 'Studio 5',
                'capacity' => 35,
                'type' => 'VIP',
                'description' => 'Studio VIP dengan layanan premium'
            ]
        ];

        foreach ($studios as $studioData) {
            Studio::firstOrCreate(['name' => $studioData['name']], $studioData);
        }
    }
}