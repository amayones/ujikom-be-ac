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
            'name' => 'Admin Cinema',
            'email' => 'admin@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'address' => '123 Admin Street',
            'role' => 'admin'
        ]);

        // Create Owner
        User::create([
            'name' => 'Owner Cinema',
            'email' => 'owner@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'address' => '123 Owner Street',
            'role' => 'owner'
        ]);

        // Create Cashier
        User::create([
            'name' => 'Cashier Cinema',
            'email' => 'cashier@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'address' => '123 Cashier Street',
            'role' => 'cashier'
        ]);

        // Create Customer
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567893',
            'address' => '123 Customer Street',
            'role' => 'customer'
        ]);
    }
}