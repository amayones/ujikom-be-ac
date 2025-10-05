<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute()
    {
        return $this->nama;
    }

    // Role checking methods
    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function kasirOrders()
    {
        return $this->hasMany(Order::class, 'kasir_id');
    }

    public function createdFilms()
    {
        return $this->hasMany(Film::class, 'created_by');
    }

    public function createdSchedules()
    {
        return $this->hasMany(Schedule::class, 'created_by');
    }
}
