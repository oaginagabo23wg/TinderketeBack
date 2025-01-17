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
            'aktibatua' => 1,
        ]);

        $mikel = User::factory()->create([
            'name' => 'mikel',              // Nombre
            'surname' => 'Erzibengoa',           // Apellidos
            'email' => 'mikel@tinderkete.com', // Correo electrónico
            'password' => bcrypt('1234'),  // Contraseña, la debes cifrar
            'birth_date' => '2000-01-02',  // Fecha de nacimiento (asegúrate de que sea mayor de 18 años)
            'admin' => 0,
            'img' => 'mikel.jpg',
            'aktibatua' => 1,
        ]);

        User::factory(15)->create();


        $antiguo = Location::factory()->create([
            'name' => 'antiguo',
            'type' => 'frontoi',
            'img' => 'LezoFrontoia.jpg',
            'iframe' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10345.314412364152!2d-2.006094752446477!3d43.31309128937382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd51baa707632f93%3A0x8680d5efa9844049!2sAntiguako%20Frontoia%20-%20Front%C3%B3n%20del%20Antiguo!5e0!3m2!1ses!2ses!4v1732786583382!5m2!1ses!2ses',
            'url' => 'https://www.google.com/maps/place//data=!4m2!3m1!1s0xd51baa707632f93:0x8680d5efa9844049?sa=X&ved=1t:8290&ictx=111'
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
        TournamentUser::factory()->create([
            'tournament_id' => 1,
            'user_id' => 1
        ]);
    }
}
