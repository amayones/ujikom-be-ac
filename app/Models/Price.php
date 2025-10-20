<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'type',
        'price',
        'day_type',
        'description',
        'created_by'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}