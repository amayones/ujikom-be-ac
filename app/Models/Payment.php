<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'total_amount',
        'payment_status',
        'payment_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
