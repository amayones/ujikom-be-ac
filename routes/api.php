<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Health check
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Absolute Cinema API is running',
        'data' => [
            'app' => 'Absolute Cinema',
            'version' => '1.0.0',
            'timestamp' => now()->toISOString()
        ]
    ]);
});

// Auth routes (public)
Route::prefix('auth')->group(function () {
    require base_path('routes/auth.php');
});

// Protected routes with role-based access
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('admin')->group(function () {
        require base_path('routes/admin.php');
    });
    Route::prefix('owner')->group(function () {
        require base_path('routes/owner.php');
    });
    Route::prefix('cashier')->group(function () {
        require base_path('routes/cashier.php');
    });
});

// Public routes (films & schedules)
Route::get('/films', [FilmController::class, 'index']);
Route::get('/films/{film}', [FilmController::class, 'show']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
Route::get('/seats/studio/{studio}', [SeatController::class, 'index']);

// Customer routes (protected)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::post('/payment/process', [PaymentController::class, 'process']);
    Route::get('/invoice/{order}', [InvoiceController::class, 'show']);
});
Route::get('/payment-methods', [PaymentController::class, 'methods']);