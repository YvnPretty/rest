<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LibroFactory extends Factory
{
    public function definition(): array
    {
        return ['titulo' => fake()->sentence(3), 'autor' => fake()->name(), 'genero' => 'Novela', 'anio_publicacion' => 2000];
    }
}
