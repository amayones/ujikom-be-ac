<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Film;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalTickets = Order::where('status', 'completed')->count();
        $totalMovies = Film::count();
        
        $lastMonth = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_amount');
        $thisMonth = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');
        
        $monthlyGrowth = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_tickets' => $totalTickets,
                'total_movies' => $totalMovies,
                'monthly_growth' => round($monthlyGrowth, 2)
            ]
        ]);
    }

    public function income(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        $today = Order::where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');
            
        $week = Order::where('status', 'completed')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('total_amount');
            
        $month = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');
            
        $year = Order::where('status', 'completed')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // Chart data for last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $amount = Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $chartData[] = [
                'date' => $date->format('Y-m-d'),
                'amount' => $amount
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Income report retrieved successfully',
            'data' => [
                'today' => $today,
                'week' => $week,
                'month' => $month,
                'year' => $year,
                'chart_data' => $chartData
            ]
        ]);
    }

    public function expense()
    {
        // Mock expense data - in real app, this would come from expense tracking
        $expenses = [
            'today' => 500000,
            'week' => 3200000,
            'month' => 12800000,
            'year' => 156000000
        ];

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('Y-m-d'),
                'amount' => rand(400000, 600000) // Mock data
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Expense report retrieved successfully',
            'data' => array_merge($expenses, ['chart_data' => $chartData])
        ]);
    }

    public function performance()
    {
        $topMovies = Order::join('schedules', 'orders.schedule_id', '=', 'schedules.id')
            ->join('films', 'schedules.film_id', '=', 'films.id')
            ->where('orders.status', 'completed')
            ->selectRaw('films.title, COUNT(orders.id) as tickets_sold, SUM(orders.total_amount) as revenue')
            ->groupBy('films.id', 'films.title')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();

        $totalSeats = 250; // Mock total cinema capacity
        $occupiedSeats = Order::where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->count() * 2; // Assuming average 2 seats per order
        
        $occupancyRate = $totalSeats > 0 ? ($occupiedSeats / $totalSeats) * 100 : 0;

        return response()->json([
            'success' => true,
            'message' => 'Performance report retrieved successfully',
            'data' => [
                'top_movies' => $topMovies,
                'occupancy_rate' => round($occupancyRate, 2),
                'customer_satisfaction' => 4.2 // Mock data
            ]
        ]);
    }
}