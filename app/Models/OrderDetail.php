<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'schedule_seat_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scheduleSeat()
    {
        return $this->belongsTo(ScheduleSeat::class);
    }
}