<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KasirController;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public routes (no auth required)
Route::get('/films', [PelangganController::class, 'getFilms']);
Route::get('/films/{id}', [PelangganController::class, 'getFilmDetail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Pelanggan routes (authenticated)
    Route::middleware('role:pelanggan')->prefix('pelanggan')->group(function () {
        Route::get('/schedules/{filmId}', [PelangganController::class, 'getSchedules']);
        Route::get('/seats/{scheduleId}', [PelangganController::class, 'getAvailableSeats']);
        Route::post('/book', [PelangganController::class, 'bookTicket']);
        Route::get('/orders', [PelangganController::class, 'getOrderHistory']);
        Route::put('/profile', [PelangganController::class, 'updateProfile']);
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Film management
        Route::get('/films', [AdminController::class, 'getFilms']);
        Route::post('/films', [AdminController::class, 'storeFilm']);
        Route::put('/films/{id}', [AdminController::class, 'updateFilm']);
        Route::delete('/films/{id}', [AdminController::class, 'deleteFilm']);

        // Customer management
        Route::get('/customers', [AdminController::class, 'getCustomers']);
        Route::put('/customers/{id}', [AdminController::class, 'updateCustomer']);

        // Schedule management
        Route::get('/schedules', [AdminController::class, 'getSchedules']);
        Route::post('/schedules', [AdminController::class, 'storeSchedule']);

        // Price management
        Route::get('/prices', [AdminController::class, 'getPrices']);
        Route::post('/prices', [AdminController::class, 'storePrice']);

        // Cashier management
        Route::get('/cashiers', [AdminController::class, 'getCashiers']);
        Route::post('/cashiers', [AdminController::class, 'storeCashier']);

        // Seat management
        Route::get('/seats', [AdminController::class, 'getSeats']);
        Route::post('/seats', [AdminController::class, 'storeSeat']);
    });

    // Owner routes
    Route::middleware('role:owner')->prefix('owner')->group(function () {
        Route::get('/financial-report', [OwnerController::class, 'getFinancialReport']);
        Route::get('/monthly-report', [OwnerController::class, 'getMonthlyReport']);
        Route::post('/expenses', [OwnerController::class, 'addExpense']);
    });

    // Kasir routes
    Route::middleware('role:kasir')->prefix('kasir')->group(function () {
        Route::post('/book-offline', [KasirController::class, 'bookOfflineTicket']);
        Route::get('/print-ticket/{orderId}', [KasirController::class, 'printTicket']);
        Route::get('/online-orders', [KasirController::class, 'getOnlineOrders']);
        Route::put('/process-order/{orderId}', [KasirController::class, 'processOnlineTicket']);
    });
});