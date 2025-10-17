<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $payment = [
            'id' => rand(10000, 99999),
            'order_id' => $request->order_id,
            'method' => $request->payment_method,
            'amount' => $request->amount,
            'status' => 'success',
            'transaction_id' => 'TXN_' . time()
        ];

        return response()->json(['success' => true, 'data' => $payment, 'message' => 'Payment processed successfully']);
    }

    public function methods()
    {
        $methods = [
            ['id' => 'card', 'name' => 'Credit/Debit Card', 'enabled' => true],
            ['id' => 'qris', 'name' => 'QRIS', 'enabled' => true],
            ['id' => 'transfer', 'name' => 'Bank Transfer', 'enabled' => true],
            ['id' => 'cash', 'name' => 'Cash', 'enabled' => true]
        ];

        return response()->json(['success' => true, 'data' => $methods]);
    }
}