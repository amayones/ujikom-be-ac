<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'film_id',
        'studio_id',
        'tanggal',
        'jam',
        'harga_id',
        'created_by',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'harga_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scheduleSeats()
    {
        return $this->hasMany(ScheduleSeat::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
