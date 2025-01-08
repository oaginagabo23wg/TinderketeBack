<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\Tournament;
use App\Models\TournamentUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // "php artisan db:seed"  seederrak pasatzeko
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Desactiva las claves foráneas
        DB::table('users')->truncate();            // Trunca la tabla
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Reactiva las claves foráneas
                // Puedes crear varios usuarios de prueba, incluyendo los campos adicionales
        $test = User::factory()->create([
            'name' => 'Test',              // Nombre
            'surname' => 'User',           // Apellidos
            'email' => 'adibidea@tinderkete.com', // Correo electrónico
            'password' => bcrypt('1234'),  // Contraseña, la debes cifrar
            'birth_date' => '2000-01-01',  // Fecha de nacimiento (asegúrate de que sea mayor de 18 años)
            'admin' => 1,
            'img' => 'ane.jpg',
        ]);

        $mikel = User::factory()->create([
            'name' => 'mikel',              // Nombre
            'surname' => 'Erzibengoa',           // Apellidos
            'email' => 'mikel@tinderkete.com', // Correo electrónico
            'password' => bcrypt('1234'),  // Contraseña, la debes cifrar
            'birth_date' => '2000-01-02',  // Fecha de nacimiento (asegúrate de que sea mayor de 18 años)
            'admin' => 0,
            'img' => 'mikel.jpg'
        ]);

        User::factory(15)->create();


        $antiguo = Location::factory()->create([
            'name' => 'antiguo',
            'coordinates' => 'hurruti',
            'img' => 'LezoFrontoia.jpg'
        ]);

        $txapelketa = Tournament::factory()->create([
            'title' => 'Trinkete txapelketa!',
            'description' => 'asdasd',
            'date' => '2024-12-13 12:00:30',
            'time' => '12:00:30',
            'price' => 40,
            'max_participants' => 12,
            'location_id' => 1
        ]);

        $userId = DB::table('users')->value('id'); // Primer ID de la tabla `users`
        $tournamentId = DB::table('tournaments')->value('id'); // Primer ID de la tabla `tournaments`
        if ($userId && $tournamentId) {
            // Insertar la relación en tournament_user
            DB::table('tournament_users')->insert([
                'user_id' => $userId,
                'tournament_id' => $tournamentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        TournamentUser::factory()->create([
            'tournament_id' => 1,
            'user_id' => 2
        ]);
    }
}
