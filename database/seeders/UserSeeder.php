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
            [
                'name' => 'Admin Bioskop',
                'email' => 'admin@cinema.com',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 123, Jakarta',
                'role' => 'admin'
            ],
            [
                'name' => 'Pemilik Bioskop',
                'email' => 'owner@cinema.com',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'address' => 'Jl. Pemilik No. 456, Jakarta',
                'role' => 'owner'
            ],
            [
                'name' => 'Kasir Bioskop',
                'email' => 'cashier@cinema.com',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'address' => 'Jl. Kasir No. 789, Jakarta',
                'role' => 'cashier'
            ],
            [
                'name' => 'Kasir Dua',
                'email' => 'kasir2@cinema.com',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
                'address' => 'Jl. Kasir No. 790, Jakarta',
                'role' => 'cashier'
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'address' => 'Jl. Pelanggan No. 321, Jakarta',
                'role' => 'customer'
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567895',
                'address' => 'Jl. Customer No. 111, Jakarta',
                'role' => 'customer'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'phone' => '081234567896',
                'address' => 'Jl. Customer No. 222, Jakarta',
                'role' => 'customer'
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }
    }
}