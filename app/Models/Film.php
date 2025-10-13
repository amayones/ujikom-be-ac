<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Helper methods for genre handling
    public function getGenreArrayAttribute()
    {
        return explode(', ', $this->genre);
    }

    public function setGenreAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['genre'] = implode(', ', $value);
        } else {
            $this->attributes['genre'] = $value;
        }
    }
}
