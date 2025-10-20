<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Owner routes - Reports & Analytics
Route::get('/dashboard', [ReportController::class, 'dashboard']);
Route::get('/income', [ReportController::class, 'income']);
Route::get('/expense', [ReportController::class, 'expense']);
Route::get('/performance', [ReportController::class, 'performance']);
