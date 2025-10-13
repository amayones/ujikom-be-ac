<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Film extends Model
{
    protected $fillable = [
        'title',
        'genre',
        'duration',
        'description',
        'status',
        'poster',
        'director',
        'release_date',
        'created_by',
    ];

    protected $casts = [
        'release_date' => 'date',
        'duration' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Modern Laravel attribute accessor
    protected function genreArray(): Attribute
    {
        return Attribute::make(
            get: fn () => explode(', ', $this->genre ?? ''),
        );
    }

    // Modern Laravel attribute mutator
    protected function genre(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_array($value) ? implode(', ', $value) : $value,
        );
    }
}
