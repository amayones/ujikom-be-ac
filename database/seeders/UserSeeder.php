<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin
            [
                'name' => 'Admin Cinema',
                'email' => 'admin@cinema.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ],
            // Owner
            [
                'name' => 'Owner Cinema',
                'email' => 'owner@cinema.com',
                'password' => Hash::make('password'),
                'role' => 'owner'
            ],
            // Kasir
            [
                'name' => 'Kasir Satu',
                'email' => 'kasir1@cinema.com',
                'password' => Hash::make('password'),
                'role' => 'cashier'
            ],
            [
                'name' => 'Kasir Dua',
                'email' => 'kasir2@cinema.com',
                'password' => Hash::make('password'),
                'role' => 'cashier'
            ],
            // Pelanggan
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer'
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer'
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }
    }
}