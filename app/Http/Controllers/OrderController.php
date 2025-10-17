<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = [
            ['id' => 1, 'movie' => 'Spider-Man', 'date' => '2024-01-15', 'time' => '19:00', 'seats' => 'A1,A2', 'total' => 100000, 'status' => 'paid'],
            ['id' => 2, 'movie' => 'Batman', 'date' => '2024-01-16', 'time' => '16:00', 'seats' => 'B5', 'total' => 55000, 'status' => 'pending']
        ];

        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function store(Request $request)
    {
        $order = [
            'id' => rand(1000, 9999),
            'movie_id' => $request->movie_id,
            'schedule_id' => $request->schedule_id,
            'seats' => $request->seats,
            'total' => $request->total,
            'status' => 'confirmed'
        ];

        return response()->json(['success' => true, 'data' => $order, 'message' => 'Order created successfully']);
    }

    public function show($id)
    {
        $order = [
            'id' => $id,
            'movie' => 'Spider-Man',
            'date' => '2024-01-15',
            'time' => '19:00',
            'seats' => 'A1,A2',
            'total' => 100000,
            'status' => 'paid'
        ];

        return response()->json(['success' => true, 'data' => $order]);
    }
}