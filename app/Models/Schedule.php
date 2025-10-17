<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Schedule extends Model
{
    protected $fillable = [
        'film_id',
        'studio_id',
        'date',
        'time',
        'price_id',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price_relation?->price ?? 50000,
        );
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function price_relation()
    {
        return $this->belongsTo(Price::class, 'price_id');
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
