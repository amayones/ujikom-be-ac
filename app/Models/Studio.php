<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $fillable = [
        'nama',
        'kapasitas',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function seats()
    {
        return $this->hasMany(StudioSeat::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
