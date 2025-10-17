<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function dashboard()
    {
        $data = [
            'tickets_sold' => 45,
            'total_sales' => 2250000,
            'customers' => 38,
            'avg_time' => '3.2 min'
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function offlineBooking(Request $request)
    {
        $booking = [
            'id' => rand(1000, 9999),
            'movie' => $request->movie,
            'seats' => $request->seats,
            'total' => $request->total,
            'payment_method' => 'cash',
            'status' => 'confirmed'
        ];

        return response()->json(['success' => true, 'data' => $booking, 'message' => 'Offline booking created']);
    }

    public function printTicket($id)
    {
        $ticket = [
            'id' => $id,
            'movie' => 'Spider-Man',
            'date' => '2024-01-15',
            'time' => '19:00',
            'seats' => 'A1,A2',
            'qr_code' => 'QR_' . $id
        ];

        return response()->json(['success' => true, 'data' => $ticket]);
    }

    public function processOnlineTicket(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Ticket validated successfully']);
    }
}