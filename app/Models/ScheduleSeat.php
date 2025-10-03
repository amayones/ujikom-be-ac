<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSeat extends Model
{
    protected $fillable = [
        'schedule_id',
        'studio_seat_id',
        'status',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function studioSeat()
    {
        return $this->belongsTo(StudioSeat::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
