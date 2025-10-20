<?php

use App\Http\Controllers\CashierController;
use Illuminate\Support\Facades\Route;

// Cashier routes
Route::get('/dashboard', [CashierController::class, 'dashboard']);
Route::post('/offline-booking', [CashierController::class, 'offlineBooking']);
Route::get('/print-ticket/{id}', [CashierController::class, 'printTicket']);
Route::post('/process-ticket', [CashierController::class, 'processOnlineTicket']);

// Transaction history
Route::get('/transactions', [CashierController::class, 'getTransactions']);