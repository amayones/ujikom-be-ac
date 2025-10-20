<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();
        
        $stats = [
            'today_sales' => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total_amount'),
            'today_tickets' => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processed_orders' => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->count()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully',
            'data' => $stats
        ]);
    }

    public function offlineBooking(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seats' => 'required|array',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'payment_method' => 'required|string'
        ]);

        $order = Order::create([
            'schedule_id' => $validated['schedule_id'],
            'order_date' => now(),
            'status' => 'completed',
            'total_amount' => count($validated['seats']) * 50000,
            'cashier_id' => $request->user()->id ?? 1,
            'seats' => json_encode($validated['seats'])
        ]);

        Payment::create([
            'order_id' => $order->id,
            'method' => $validated['payment_method'],
            'total_amount' => $order->total_amount,
            'payment_status' => 'success',
            'payment_date' => now()
        ]);

        $bookingCode = 'AC' . str_pad($order->id, 6, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'message' => 'Offline booking created successfully',
            'data' => [
                'id' => $order->id,
                'booking_code' => $bookingCode,
                'schedule_id' => $order->schedule_id,
                'seats' => $validated['seats'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at->toISOString()
            ]
        ], 201);
    }

    public function printTicket($id)
    {
        $order = Order::with(['schedule.film', 'schedule.studio', 'user'])
            ->findOrFail($id);

        $bookingCode = 'AC' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        
        $ticket = [
            'id' => $order->id,
            'booking_code' => $bookingCode,
            'movie_title' => $order->schedule->film->title,
            'schedule_date' => $order->schedule->date,
            'schedule_time' => $order->schedule->time,
            'studio' => $order->schedule->studio->name,
            'seats' => json_decode($order->seats ?? '[]'),
            'customer_name' => $order->user->name ?? 'Walk-in Customer',
            'total_amount' => $order->total_amount,
            'qr_code' => base64_encode('TICKET_' . $order->id),
            'printed_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Ticket data for printing',
            'data' => $ticket
        ]);
    }

    public function processOnlineTicket(Request $request)
    {
        $validated = $request->validate([
            'booking_code' => 'required|string',
            'qr_code' => 'nullable|string'
        ]);

        $orderId = (int) substr($validated['booking_code'], 2);
        
        $order = Order::with(['schedule.film', 'schedule.studio', 'user'])
            ->find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking code'
            ], 400);
        }

        $order->update(['status' => 'validated']);

        $ticket = [
            'booking_code' => $validated['booking_code'],
            'movie_title' => $order->schedule->film->title,
            'schedule_date' => $order->schedule->date,
            'schedule_time' => $order->schedule->time,
            'studio' => $order->schedule->studio->name,
            'seats' => json_decode($order->seats ?? '[]'),
            'customer_name' => $order->user->name ?? 'Walk-in Customer',
            'status' => 'validated',
            'validated_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Online ticket validated successfully',
            'data' => $ticket
        ]);
    }

    public function getTransactions()
    {
        $transactions = Order::with(['schedule.film'])
            ->whereDate('created_at', now()->toDateString())
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => 'TXN' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                    'movie' => $order->schedule->film->title ?? 'Unknown',
                    'seats' => implode(',', json_decode($order->seats ?? '[]')),
                    'amount' => $order->total_amount,
                    'time' => $order->created_at->format('H:i')
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Recent transactions retrieved successfully',
            'data' => $transactions
        ]);
    }
}