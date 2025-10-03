<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Schedule;
use App\Models\ScheduleSeat;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function bookOfflineTicket(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:schedule_seats,id',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string'
        ]);

        $order = Order::create([
            'schedule_id' => $validated['schedule_id'],
            'tanggal_pesan' => now(),
            'status' => 'confirmed',
            'kasir_id' => Auth::id()
        ]);

        foreach ($validated['seat_ids'] as $seatId) {
            ScheduleSeat::where('id', $seatId)->update(['status' => 'booked']);
        }

        // Create payment record
        $schedule = Schedule::with('price')->find($validated['schedule_id']);
        $totalAmount = $schedule->price->harga * count($validated['seat_ids']);

        $payment = Payment::create([
            'order_id' => $order->id,
            'jumlah' => $totalAmount,
            'metode' => 'cash',
            'status' => 'success'
        ]);

        return response()->json([
            'order' => $order,
            'payment' => $payment,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone']
        ], 201);
    }

    public function printTicket($orderId)
    {
        $order = Order::with(['schedule.film', 'schedule.studio', 'orderDetails'])
            ->findOrFail($orderId);

        // Generate ticket data for printing
        $ticketData = [
            'order_id' => $order->id,
            'film' => $order->schedule->film->judul,
            'studio' => $order->schedule->studio->nama,
            'date' => $order->schedule->tanggal,
            'time' => $order->schedule->jam,
            'seats' => $order->orderDetails->pluck('seat_number'),
            'total_amount' => $order->payments->sum('jumlah')
        ];

        return response()->json(['ticket' => $ticketData]);
    }

    public function processOnlineTicket(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        $validated = $request->validate([
            'action' => 'required|in:confirm,cancel'
        ]);

        if ($validated['action'] === 'confirm') {
            $order->update(['status' => 'confirmed']);
            
            // Create invoice
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'nomor_invoice' => 'INV-' . time(),
                'tanggal' => now(),
                'total' => $order->payments->sum('jumlah')
            ]);

            return response()->json(['order' => $order, 'invoice' => $invoice]);
        } else {
            $order->update(['status' => 'cancelled']);
            
            // Release seats
            ScheduleSeat::where('schedule_id', $order->schedule_id)
                ->whereIn('id', $order->orderDetails->pluck('schedule_seat_id'))
                ->update(['status' => 'available']);

            return response()->json(['order' => $order]);
        }
    }

    public function getOnlineOrders()
    {
        return Order::with(['schedule.film', 'user'])
            ->where('status', 'pending')
            ->whereNull('kasir_id')
            ->get();
    }
}