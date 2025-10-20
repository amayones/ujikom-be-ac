<?php

use App\Http\Controllers\FilmController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;

// Admin routes
// Film management
Route::get('/films', [FilmController::class, 'index']);
Route::post('/films', [FilmController::class, 'store']);
Route::get('/films/{film}', [FilmController::class, 'show']);
Route::put('/films/{film}', [FilmController::class, 'update']);
Route::delete('/films/{film}', [FilmController::class, 'destroy']);

// Schedule management
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

// Studio management
Route::get('/studios', [StudioController::class, 'index']);
Route::post('/studios', [StudioController::class, 'store']);
Route::get('/studios/{studio}', [StudioController::class, 'show']);
Route::put('/studios/{studio}', [StudioController::class, 'update']);
Route::delete('/studios/{studio}', [StudioController::class, 'destroy']);

// User management
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

// Price management
Route::get('/prices', [PriceController::class, 'index']);
Route::put('/prices/{type}', [PriceController::class, 'update']);

// Seat management
Route::get('/seats/studio/{studio}', [SeatController::class, 'index']);
Route::put('/seats/{seat}', [SeatController::class, 'update']);

// Cashier management
Route::get('/cashiers', [UserController::class, 'getCashiers']);
Route::post('/cashiers', [UserController::class, 'store']);
Route::put('/cashiers/{user}', [UserController::class, 'update']);
Route::delete('/cashiers/{user}', [UserController::class, 'destroy']);