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
        'payment_date'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}