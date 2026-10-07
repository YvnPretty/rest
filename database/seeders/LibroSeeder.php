<?php

namespace Database\Seeders;

use App\Models\Libro;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        Libro::firstOrCreate(['titulo' => 'Don Quijote', 'autor' => 'Miguel de Cervantes'], ['genero' => 'Novela', 'anio_publicacion' => 1605]);
    }
}
