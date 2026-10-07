<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LibroController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Libro::all(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'genero' => 'nullable|string|max:255',
            'anio_publicacion' => 'nullable|integer',
        ]);
        $libro = Libro::create($validated);

        return response()->json($libro, 201)->header('Location', route('libros.show', $libro->id));
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(Libro::findOrFail($id), 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $libro = Libro::findOrFail($id);
        $validated = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'autor' => 'sometimes|required|string|max:255',
            'genero' => 'sometimes|nullable|string|max:255',
            'anio_publicacion' => 'sometimes|nullable|integer',
        ]);
        $libro->update($validated);

        return response()->json($libro, 200);
    }

    public function destroy(string $id): Response
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();

        return response()->noContent();
    }
}
