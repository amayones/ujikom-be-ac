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
            'nama' => 'Admin Cinema',
            'email' => 'admin@cinema.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Admin No. 1',
            'role' => 'admin'
        ]);

        // Create Owner
        User::create([
            'nama' => 'Owner Cinema',
            'email' => 'owner@cinema.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Owner No. 1',
            'role' => 'owner'
        ]);

        // Create Kasir
        User::create([
            'nama' => 'Kasir Cinema',
            'email' => 'kasir@cinema.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Kasir No. 1',
            'role' => 'kasir'
        ]);

        // Create Pelanggan
        User::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567893',
            'alamat' => 'Jl. Pelanggan No. 1',
            'role' => 'pelanggan'
        ]);
    }
}