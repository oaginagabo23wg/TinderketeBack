<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends Model
{
    use HasFactory;

    // Aldatu daitezkeen
    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
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
