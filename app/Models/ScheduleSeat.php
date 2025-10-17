<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSeat extends Model
{
    protected $fillable = [
        'schedule_id',
        'studio_seat_id',
        'status',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function studioSeat()
    {
        return $this->belongsTo(StudioSeat::class);
    }
}