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
            'nama' => 'Admin Bioskop',
            'email' => 'admin@cinema.com',
            'password' => Hash::make('Admin@2024!'),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Admin No. 123, Jakarta',
            'role' => 'admin'
        ]);

        // Create Owner
        User::create([
            'nama' => 'Pemilik Bioskop',
            'email' => 'owner@cinema.com',
            'password' => Hash::make('Owner@2024!'),
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Pemilik No. 456, Jakarta',
            'role' => 'owner'
        ]);

        // Create Cashier
        User::create([
            'nama' => 'Kasir Bioskop',
            'email' => 'cashier@cinema.com',
            'password' => Hash::make('Cashier@2024!'),
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Kasir No. 789, Jakarta',
            'role' => 'cashier'
        ]);

        // Create Customer
        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('Customer@2024!'),
            'no_hp' => '081234567893',
            'alamat' => 'Jl. Pelanggan No. 321, Jakarta',
            'role' => 'customer'
        ]);
        
        // Create Test User for easy login
        User::create([
            'nama' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('test123'),
            'no_hp' => '081234567894',
            'alamat' => 'Jl. Test No. 999, Jakarta',
            'role' => 'customer'
        ]);
    }
}