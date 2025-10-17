<?php

use App\Http\Controllers\CashierController;
use Illuminate\Support\Facades\Route;

// Cashier routes (NO MIDDLEWARE)
Route::get('/dashboard', [CashierController::class, 'dashboard']);
Route::post('/offline-booking', [CashierController::class, 'offlineBooking']);
Route::get('/print-ticket/{id}', [CashierController::class, 'printTicket']);
Route::post('/process-ticket', [CashierController::class, 'processOnlineTicket']);

// Recent transactions
Route::get('/transactions', function () {
    return response()->json([
        'success' => true,
        'data' => [
            ['id' => 'TXN001', 'movie' => 'Spider-Man', 'seats' => 'A1,A2', 'amount' => 100000, 'time' => '14:30'],
            ['id' => 'TXN002', 'movie' => 'Batman', 'seats' => 'B5', 'amount' => 55000, 'time' => '14:25']
        ]
    ]);
});