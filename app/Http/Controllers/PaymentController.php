<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        // Create payment record
        $payment = Payment::create([
            'order_id' => $validated['order_id'],
            'method' => $validated['payment_method'],
            'total_amount' => $validated['amount'],
            'payment_status' => 'success',
            'payment_date' => now()
        ]);

        // Update order status
        Order::where('id', $validated['order_id'])
            ->update(['status' => 'completed']);

        return response()->json([
            'success' => true, 
            'message' => 'Payment processed successfully',
            'data' => [
                'id' => $payment->id,
                'order_id' => $payment->order_id,
                'method' => $payment->method,
                'amount' => $payment->total_amount,
                'status' => $payment->payment_status,
                'transaction_id' => 'TXN_' . $payment->id,
                'processed_at' => $payment->payment_date->toISOString()
            ]
        ]);
    }

    public function methods()
    {
        $methods = PaymentMethod::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'type', 'fee_percentage', 'fee_fixed']);

        return response()->json([
            'success' => true,
            'message' => 'Payment methods retrieved successfully',
            'data' => $methods
        ]);
    }
}