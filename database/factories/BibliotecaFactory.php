<?php

namespace Database\Factories;

use App\Models\Biblioteca;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Biblioteca>
 */
class BibliotecaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => \App\Models\User::factory(),
            'nome' => fake()->company(),
            'endereco' => fake()->address(),
            'telefone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
        ];
    }
}
