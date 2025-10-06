<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Bioskop',
            'email' => 'admin@cinema.com',
            'password' => Hash::make('Admin@2024!'),
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 123, Jakarta',
            'role' => 'admin'
        ]);

        // Create Owner
        User::create([
            'name' => 'Pemilik Bioskop',
            'email' => 'owner@cinema.com',
            'password' => Hash::make('Owner@2024!'),
            'phone' => '081234567891',
            'address' => 'Jl. Pemilik No. 456, Jakarta',
            'role' => 'owner'
        ]);

        // Create Cashier
        User::create([
            'name' => 'Kasir Bioskop',
            'email' => 'cashier@cinema.com',
            'password' => Hash::make('Cashier@2024!'),
            'phone' => '081234567892',
            'address' => 'Jl. Kasir No. 789, Jakarta',
            'role' => 'cashier'
        ]);

        // Create Customer
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('Customer@2024!'),
            'phone' => '081234567893',
            'address' => 'Jl. Pelanggan No. 321, Jakarta',
            'role' => 'customer'
        ]);
    }
}