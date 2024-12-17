<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;  // Agregar este trait

use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',       // Nombre
        'surname',    // Apellidos
        'email',       // Correo electrónico
        'password',   // Contraseña (cambiado de 'password' a 'pasahitza')
        'birth_date', // Fecha de nacimiento
        'admin',
        'hometown',
        'telephone',
	'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',   // Asegúrate de ocultar 'pasahitza', no 'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            //'email_verified_at' => 'datetime',
            'password' => 'hashed',  // Asegúrate de que la contraseña se guarde correctamente como hash
            'birth_date' => 'date', // Aseguramos que la fecha de nacimiento se maneje como una fecha
        ];
    }

    // Relations
    public function tournament()
    {
        //TODO: tournament_user tabla
        return $this->belongsToMany(Tournament::class, 'tournament_user');
    }
}
