<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk setup database production
Route::get('/setup-db', function () {
    try {
        // Cek apakah user test sudah ada
        $existingUser = User::where('email', 'test@test.com')->first();
        
        if ($existingUser) {
            return response()->json([
                'message' => 'User test sudah ada',
                'user' => $existingUser->only(['nama', 'email', 'role'])
            ]);
        }
        
        // Buat user test
        $user = User::create([
            'nama' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('test123'),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'role' => 'customer'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User test berhasil dibuat',
            'user' => $user->only(['nama', 'email', 'role']),
            'credentials' => [
                'email' => 'test@test.com',
                'password' => 'test123'
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Route untuk cek database structure
Route::get('/check-db', function () {
    try {
        $users = User::select(['id', 'nama', 'email', 'role'])->limit(5)->get();
        
        return response()->json([
            'success' => true,
            'users_count' => User::count(),
            'sample_users' => $users,
            'table_columns' => \Schema::getColumnListing('users')
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});