<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Cash', 'code' => 'cash', 'type' => 'offline', 'fee_percentage' => 0, 'fee_fixed' => 0, 'description' => 'Pembayaran tunai'],
            ['name' => 'Debit Card', 'code' => 'debit', 'type' => 'offline', 'fee_percentage' => 0, 'fee_fixed' => 2500, 'description' => 'Kartu debit'],
            ['name' => 'Credit Card', 'code' => 'credit', 'type' => 'both', 'fee_percentage' => 2.9, 'fee_fixed' => 0, 'description' => 'Kartu kredit'],
            ['name' => 'Bank Transfer', 'code' => 'transfer', 'type' => 'online', 'fee_percentage' => 0, 'fee_fixed' => 6500, 'description' => 'Transfer bank'],
            ['name' => 'GoPay', 'code' => 'gopay', 'type' => 'both', 'fee_percentage' => 2, 'fee_fixed' => 0, 'description' => 'GoPay e-wallet'],
            ['name' => 'OVO', 'code' => 'ovo', 'type' => 'both', 'fee_percentage' => 2, 'fee_fixed' => 0, 'description' => 'OVO e-wallet'],
            ['name' => 'DANA', 'code' => 'dana', 'type' => 'both', 'fee_percentage' => 2, 'fee_fixed' => 0, 'description' => 'DANA e-wallet'],
            ['name' => 'ShopeePay', 'code' => 'shopeepay', 'type' => 'online', 'fee_percentage' => 2.5, 'fee_fixed' => 0, 'description' => 'ShopeePay e-wallet']
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(['code' => $method['code']], $method);
        }
    }
}