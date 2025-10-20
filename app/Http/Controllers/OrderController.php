<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Get orders for authenticated user or all orders for admin
        $query = Order::with(['schedule.film', 'schedule.studio'])
            ->orderBy('created_at', 'desc');
        
        // If user is authenticated, filter by user_id
        if ($request->user()) {
            $query->where('user_id', $request->user()->id);
        }
        
        $orders = $query->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'movie_title' => $order->schedule->film->title ?? 'Unknown Movie',
                'schedule_date' => $order->schedule->date ?? null,
                'schedule_time' => $order->schedule->time ?? null,
                'studio' => $order->schedule->studio->name ?? 'Unknown Studio',
                'seats' => json_decode($order->seats ?? '[]'),
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at->toISOString()
            ];
        });

        return response()->json([
            'success' => true, 
            'message' => 'Orders retrieved successfully',
            'data' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seats' => 'required|array',
            'total_amount' => 'required|numeric|min:0'
        ]);

        $order = Order::create([
            'user_id' => $request->user()->id ?? null,
            'schedule_id' => $validated['schedule_id'],
            'order_date' => now(),
            'status' => 'pending',
            'total_amount' => $validated['total_amount'],
            'seats' => json_encode($validated['seats'])
        ]);

        // Create order details for each seat
        foreach ($validated['seats'] as $seatId) {
            OrderDetail::create([
                'order_id' => $order->id,
                'schedule_seat_id' => $seatId
            ]);
        }

        $order->load(['schedule.film', 'schedule.studio']);

        return response()->json([
            'success' => true, 
            'message' => 'Order created successfully',
            'data' => [
                'id' => $order->id,
                'schedule_id' => $order->schedule_id,
                'seats' => json_decode($order->seats ?? '[]'),
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at->toISOString()
            ]
        ], 201);
    }

    public function show($id)
    {
        $order = Order::with(['schedule.film', 'schedule.studio', 'payment'])
            ->findOrFail($id);

        $orderData = [
            'id' => $order->id,
            'movie_title' => $order->schedule->film->title ?? 'Unknown Movie',
            'schedule_date' => $order->schedule->date ?? null,
            'schedule_time' => $order->schedule->time ?? null,
            'studio' => $order->schedule->studio->name ?? 'Unknown Studio',
            'seats' => json_decode($order->seats ?? '[]'),
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'payment_method' => $order->payment->method ?? 'Unknown',
            'created_at' => $order->created_at->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully', 
            'data' => $orderData
        ]);
    }
}