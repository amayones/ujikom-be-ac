<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function getFinancialReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $income = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'success')
            ->sum('total_amount');

        $orders = Order::with(['schedule.film', 'payments'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $expenses = Report::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_expense');

        return response()->json([
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $income - $expenses,
            'orders' => $orders,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    }

    public function getMonthlyReport()
    {
        $monthlyData = Payment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_amount) as total_income')
        )
        ->where('payment_status', 'success')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return response()->json(['monthly_report' => $monthlyData]);
    }

    public function addExpense(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'category' => 'required|string'
        ]);

        $expense = Report::create([
            'period' => now()->format('Y-m'),
            'total_income' => 0,
            'total_expense' => $validated['amount'],
            'owner_id' => auth()->id()
        ]);

        return response()->json(['expense' => $expense], 201);
    }
}