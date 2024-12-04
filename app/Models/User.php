<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'izena',       // Nombre
        'abizenak',    // Apellidos
        'email',       // Correo electrónico
        'pasahitza',   // Contraseña (cambiado de 'password' a 'pasahitza')
        'jaiotzeData', // Fecha de nacimiento
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pasahitza',   // Asegúrate de ocultar 'pasahitza', no 'password'
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'pasahitza' => 'hashed',  // Asegúrate de que la contraseña se guarde correctamente como hash
            'jaiotzeData' => 'date', // Aseguramos que la fecha de nacimiento se maneje como una fecha
        ];
    }
}
