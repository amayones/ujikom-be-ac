<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;

// Admin routes (NO MIDDLEWARE)
// Films management
Route::get('/films', [FilmController::class, 'index']);
Route::post('/films', [FilmController::class, 'store']);
Route::get('/films/{film}', [FilmController::class, 'show']);
Route::put('/films/{film}', [FilmController::class, 'update']);
Route::delete('/films/{film}', [FilmController::class, 'destroy']);

// Schedules management
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

// Users/Customers management
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

// Prices management
Route::get('/prices', [PriceController::class, 'index']);
Route::put('/prices/{price}', [PriceController::class, 'update']);

// Seats management
Route::get('/seats', [SeatController::class, 'index']);
Route::put('/seats/{seat}', [SeatController::class, 'update']);

// Cashiers management
Route::get('/cashiers', function () {
    return response()->json([
        'success' => true,
        'data' => [
            ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice@cinema.com', 'shift' => 'Morning', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob@cinema.com', 'shift' => 'Evening', 'status' => 'Active']
        ]
    ]);
});
Route::post('/cashiers', function () {
    return response()->json(['success' => true, 'message' => 'Cashier created']);
});
Route::put('/cashiers/{id}', function () {
    return response()->json(['success' => true, 'message' => 'Cashier updated']);
});
Route::delete('/cashiers/{id}', function () {
    return response()->json(['success' => true, 'message' => 'Cashier deleted']);
});