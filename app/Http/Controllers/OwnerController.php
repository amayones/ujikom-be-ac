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
            ->where('status', 'success')
            ->sum('jumlah');

        $orders = Order::with(['schedule.film', 'payments'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $expenses = Report::whereBetween('created_at', [$startDate, $endDate])
            ->where('tipe', 'pengeluaran')
            ->sum('jumlah');

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
            DB::raw('SUM(jumlah) as total_income')
        )
        ->where('status', 'success')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return response()->json(['monthly_report' => $monthlyData]);
    }

    public function addExpense(Request $request)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'jumlah' => 'required|numeric',
            'kategori' => 'required|string'
        ]);

        $expense = Report::create([
            'tipe' => 'pengeluaran',
            'deskripsi' => $validated['deskripsi'],
            'jumlah' => $validated['jumlah'],
            'kategori' => $validated['kategori']
        ]);

        return response()->json(['expense' => $expense], 201);
    }
}