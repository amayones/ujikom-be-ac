<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code', 
        'type',
        'is_active',
        'fee_percentage',
        'fee_fixed',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2'
    ];
}