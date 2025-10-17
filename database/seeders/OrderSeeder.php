<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $cashiers = User::where('role', 'cashier')->get();
        $schedules = Schedule::with(['film', 'studio'])->get();
        
        // Generate sample orders
        for ($i = 0; $i < 15; $i++) {
            $customer = $customers->random();
            $cashier = $cashiers->random();
            $schedule = $schedules->random();
            
            // Create order
            $order = Order::create([
                'user_id' => $customer->id,
                'cashier_id' => rand(0, 1) ? $cashier->id : null, // Some online, some offline
                'schedule_id' => $schedule->id,
                'order_date' => Carbon::now()->subDays(rand(0, 30)),
                'total_amount' => 0, // Will be calculated
                'status' => collect(['pending', 'paid', 'cancelled'])->random(),
                'booking_code' => 'BK' . str_pad($i + 1, 6, '0', STR_PAD_LEFT)
            ]);
            
            // Create order details (seats)
            $seatCount = rand(1, 4);
            $totalAmount = 0;
            
            for ($j = 0; $j < $seatCount; $j++) {
                $seatNumber = chr(65 + rand(0, 4)) . rand(1, 10); // A1-E10
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'seat_number' => $seatNumber,
                    'price' => $schedule->price
                ]);
                
                $totalAmount += $schedule->price;
            }
            
            // Update order total
            $order->update(['total_amount' => $totalAmount]);
            
            // Create payment if order is paid
            if ($order->status === 'paid') {
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalAmount,
                    'payment_method' => collect(['cash', 'card', 'qris', 'transfer'])->random(),
                    'payment_date' => $order->order_date->addMinutes(rand(1, 30)),
                    'status' => 'completed'
                ]);
                
                // Create invoice
                Invoice::create([
                    'order_id' => $order->id,
                    'invoice_number' => 'INV' . $order->order_date->format('Ymd') . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    'issue_date' => $order->order_date,
                    'due_date' => $order->order_date->addDays(1),
                    'total_amount' => $totalAmount,
                    'status' => 'paid'
                ]);
            }
        }
    }
}