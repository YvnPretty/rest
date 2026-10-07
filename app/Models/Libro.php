<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $table = 'libros';

    protected $fillable = ['titulo', 'autor', 'genero', 'anio_publicacion'];

    protected function casts(): array
    {
        return ['anio_publicacion' => 'integer'];
    }
}
