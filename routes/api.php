<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CashierController;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public routes (no auth required)
Route::get('/films', [CustomerController::class, 'getFilms']);
Route::get('/films/{id}', [CustomerController::class, 'getFilmDetail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Customer routes (authenticated)
    Route::middleware('role:customer')->prefix('customer')->group(function () {
        Route::get('/schedules/{filmId}', [CustomerController::class, 'getSchedules']);
        Route::get('/seats/{scheduleId}', [CustomerController::class, 'getAvailableSeats']);
        Route::post('/book', [CustomerController::class, 'bookTicket']);
        Route::get('/orders', [CustomerController::class, 'getOrderHistory']);
        Route::put('/profile', [CustomerController::class, 'updateProfile']);
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

    // Cashier routes
    Route::middleware('role:cashier')->prefix('cashier')->group(function () {
        Route::post('/book-offline', [CashierController::class, 'bookOfflineTicket']);
        Route::get('/print-ticket/{orderId}', [CashierController::class, 'printTicket']);
        Route::get('/online-orders', [CashierController::class, 'getOnlineOrders']);
        Route::put('/process-order/{orderId}', [CashierController::class, 'processOnlineTicket']);
    });
});