<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'created_by',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function studioSeats()
    {
        return $this->hasMany(StudioSeat::class);
    }
}