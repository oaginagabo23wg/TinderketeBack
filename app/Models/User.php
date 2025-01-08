<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

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
	    'remember_token',
        'image',
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
}
