<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CashierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/films', [CustomerController::class, 'films'])->name('films');
Route::get('/films/{id}', [CustomerController::class, 'filmDetail'])->name('film.detail');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Customer routes
    Route::middleware('role:customer')->group(function () {
        Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
        Route::get('/booking/{filmId}', [CustomerController::class, 'booking'])->name('customer.booking');
        Route::get('/history', [CustomerController::class, 'history'])->name('customer.history');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('films', AdminController::class);
        Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules');
        Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    });

    // Owner routes
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [OwnerController::class, 'reports'])->name('reports');
    });

    // Cashier routes
    Route::middleware('role:cashier')->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', [CashierController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [CashierController::class, 'transactions'])->name('transactions');
    });
});