<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'type',
        'price',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}