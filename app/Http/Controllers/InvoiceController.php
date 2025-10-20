<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($orderId)
    {
        $order = Order::with(['schedule.film', 'schedule.studio', 'payment', 'user'])
            ->findOrFail($orderId);

        // Create or get invoice
        $invoice = Invoice::firstOrCreate(
            ['order_id' => $order->id],
            [
                'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'invoice_date' => now(),
                'total' => $order->total_amount
            ]
        );

        $invoiceData = [
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => $invoice->invoice_date,
            'order' => [
                'id' => $order->id,
                'movie_title' => $order->schedule->film->title,
                'schedule_date' => $order->schedule->date,
                'schedule_time' => $order->schedule->time,
                'studio' => $order->schedule->studio->name,
                'customer_name' => $order->user->name ?? 'Walk-in Customer',
                'customer_email' => $order->user->email ?? null,
                'seats' => json_decode($order->seats ?? '[]'),
                'total_amount' => $order->total_amount,
                'payment_method' => $order->payment->method ?? 'Cash',
                'payment_status' => $order->payment->payment_status ?? 'success'
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Invoice retrieved successfully',
            'data' => $invoiceData
        ]);
    }
}