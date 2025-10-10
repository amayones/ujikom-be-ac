<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Simple test route
Route::get('/test', function () {
    return response()->json(['message' => 'Backend is working!']);
});

// Admin routes for films CRUD
Route::prefix('admin')->group(function () {
    Route::get('/films', [AdminController::class, 'getFilms']);
    Route::post('/films', [AdminController::class, 'storeFilm']);
    Route::put('/films/{id}', [AdminController::class, 'updateFilm']);
    Route::delete('/films/{id}', [AdminController::class, 'deleteFilm']);
});
