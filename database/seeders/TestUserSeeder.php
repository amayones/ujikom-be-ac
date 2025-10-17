<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users if they don't exist
        $testUsers = [
            [
                'name' => 'Test Customer',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer'
            ],
            [
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Test Owner',
                'email' => 'owner@example.com',
                'password' => Hash::make('password123'),
                'role' => 'owner'
            ],
            [
                'name' => 'Test Cashier',
                'email' => 'cashier@example.com',
                'password' => Hash::make('password123'),
                'role' => 'cashier'
            ]
        ];

        foreach ($testUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}