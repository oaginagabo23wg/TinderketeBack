<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class TournamentFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->name(),
            'date' => fake()->date(),
            'time' => fake()->time(),
            'price' => fake()->numberBetween(10,90),
            'max_participants' => fake()->numberBetween(0,24),
            'location_id' => Location::inRandomOrder()->first()->id,
        ];
    }
}
