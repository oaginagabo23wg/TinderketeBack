<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    // Aldatu daitezkeen 
    protected $fillable = [
        'sport',
        'description',
        'date',
        'price',
        'max_participants',
        'location_id',
    ];

    // Relations
    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    // Relación: Un torneo tiene muchos usuarios
    public function users()
    {
        return $this->belongsToMany(User::class, 'tournament_user');
    }
}
