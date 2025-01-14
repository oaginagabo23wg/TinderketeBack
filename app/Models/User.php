<?php

namespace App\Models;

use app\Models\Reservation;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'birth_date',
        'admin',
        'hometown',
        'telephone',
        'img',
        'aktibatua',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            //'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    // Relations
    public function tournament()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_users');
    }

    public function reservations () 
    {
        return $this->belongsToMany(Reservation::class, 'reservation_user');    
    }
}
