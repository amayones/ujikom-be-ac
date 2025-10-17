<?php

use Illuminate\Support\Facades\Route;

// Owner routes (NO MIDDLEWARE)
    
    // Dashboard & Analytics
    Route::get('/dashboard', function () {
        return response()->json([
            'success' => true,
            'message' => 'Owner dashboard data',
            'data' => [
                'total_revenue' => 15000000,
                'total_tickets' => 1250,
                'total_movies' => 12,
                'monthly_growth' => 15.5
            ]
        ]);
    });
    
    // Financial Reports
    Route::get('/reports/financial', function () {
        return response()->json([
            'success' => true,
            'message' => 'Financial reports',
            'data' => [
                'daily_revenue' => [
                    ['date' => '2024-01-01', 'revenue' => 500000],
                    ['date' => '2024-01-02', 'revenue' => 750000],
                    ['date' => '2024-01-03', 'revenue' => 600000]
                ],
                'monthly_summary' => [
                    'total_revenue' => 15000000,
                    'total_expenses' => 8000000,
                    'net_profit' => 7000000
                ]
            ]
        ]);
    });
    
    // Performance Reports
    Route::get('/reports/performance', function () {
        return response()->json([
            'success' => true,
            'message' => 'Performance reports',
            'data' => [
                'top_movies' => [
                    ['title' => 'Spider-Man', 'tickets_sold' => 450, 'revenue' => 2250000],
                    ['title' => 'Batman', 'tickets_sold' => 380, 'revenue' => 1900000]
                ],
                'occupancy_rate' => 75.5,
                'customer_satisfaction' => 4.2
            ]
        ]);
    });
