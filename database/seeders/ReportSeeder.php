<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Generate daily reports for last 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->subDays($day);
            
            $dailyRevenue = rand(2000000, 8000000); // 2M - 8M per day
            $dailyExpenses = rand(500000, 1500000); // 500K - 1.5M per day
            $ticketsSold = rand(50, 200);
            
            Report::firstOrCreate([
                'report_date' => $date->format('Y-m-d'),
                'type' => 'daily'
            ], [
                'report_date' => $date->format('Y-m-d'),
                'type' => 'daily',
                'total_revenue' => $dailyRevenue,
                'total_expenses' => $dailyExpenses,
                'net_profit' => $dailyRevenue - $dailyExpenses,
                'tickets_sold' => $ticketsSold,
                'data' => json_encode([
                    'top_movies' => [
                        ['title' => 'Spider-Man', 'tickets' => rand(20, 50), 'revenue' => rand(1000000, 2500000)],
                        ['title' => 'Batman', 'tickets' => rand(15, 40), 'revenue' => rand(800000, 2000000)],
                        ['title' => 'Top Gun', 'tickets' => rand(10, 30), 'revenue' => rand(600000, 1500000)]
                    ],
                    'payment_methods' => [
                        'cash' => rand(30, 50),
                        'card' => rand(20, 40), 
                        'qris' => rand(15, 35),
                        'transfer' => rand(10, 25)
                    ],
                    'studio_occupancy' => [
                        'Studio 1' => rand(60, 90),
                        'Studio 2' => rand(55, 85),
                        'Studio 3' => rand(70, 95),
                        'Studio 4' => rand(80, 100),
                        'Studio 5' => rand(75, 95)
                    ]
                ])
            ]);
        }
        
        // Generate monthly reports for last 6 months
        for ($month = 0; $month < 6; $month++) {
            $date = Carbon::now()->subMonths($month)->startOfMonth();
            
            $monthlyRevenue = rand(50000000, 150000000); // 50M - 150M per month
            $monthlyExpenses = rand(15000000, 35000000); // 15M - 35M per month
            $ticketsSold = rand(1500, 4000);
            
            Report::firstOrCreate([
                'report_date' => $date->format('Y-m-d'),
                'type' => 'monthly'
            ], [
                'report_date' => $date->format('Y-m-d'),
                'type' => 'monthly',
                'total_revenue' => $monthlyRevenue,
                'total_expenses' => $monthlyExpenses,
                'net_profit' => $monthlyRevenue - $monthlyExpenses,
                'tickets_sold' => $ticketsSold,
                'data' => json_encode([
                    'growth_rate' => rand(-5, 25) . '%',
                    'customer_satisfaction' => rand(4.0, 4.8),
                    'avg_ticket_price' => rand(50000, 75000),
                    'peak_days' => ['Friday', 'Saturday', 'Sunday'],
                    'expenses_breakdown' => [
                        'staff_salary' => rand(8000000, 15000000),
                        'utilities' => rand(2000000, 5000000),
                        'maintenance' => rand(1000000, 3000000),
                        'marketing' => rand(2000000, 8000000),
                        'others' => rand(2000000, 4000000)
                    ]
                ])
            ]);
        }
    }
}