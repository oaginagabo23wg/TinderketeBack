<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Puedes crear varios usuarios de prueba, incluyendo los campos adicionales
        User::factory()->create([
            'izena' => 'Test',              // Nombre
            'abizenak' => 'User',           // Apellidos
            'email' => 'adibidea@tinderkete.com', // Correo electrónico
            'pasahitza' => bcrypt('1234'),  // Contraseña, la debes cifrar
            'jaiotzeData' => '2000-01-01',  // Fecha de nacimiento (asegúrate de que sea mayor de 18 años)
        ]);
    }
}
