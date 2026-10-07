<?php

namespace Tests\Feature;

use App\Models\Libro;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LibroApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.connections.mongodb.database' => 'api_libros_test_'.getmypid()]);
        DB::purge('mongodb');
    }

    protected function tearDown(): void
    {
        Libro::query()->delete();
        parent::tearDown();
    }

    public function test_crud_persists_and_deletes_a_book(): void
    {
        $data = ['titulo' => 'Don Quijote', 'autor' => 'Miguel de Cervantes', 'genero' => 'Novela', 'anio_publicacion' => 1605];
        $created = $this->postJson('/api/libros', $data)->assertCreated()->assertJsonFragment($data);
        $id = $created->json('id');
        $this->assertNotNull(Libro::find($id));
        $this->getJson('/api/libros')->assertOk()->assertJsonCount(1)->assertJsonFragment(['id' => $id]);
        $this->getJson('/api/libros/'.$id)->assertOk()->assertJsonPath('titulo', 'Don Quijote');
        $this->putJson('/api/libros/'.$id, ['titulo' => 'Don Quijote de la Mancha'])->assertOk()->assertJsonPath('autor', 'Miguel de Cervantes');
        $this->assertSame('Don Quijote de la Mancha', Libro::findOrFail($id)->titulo);
        $this->patchJson('/api/libros/'.$id, ['genero' => null])->assertOk()->assertJsonPath('genero', null);
        $this->assertNull(Libro::findOrFail($id)->genero);
        $this->deleteJson('/api/libros/'.$id)->assertNoContent();
        $this->assertNull(Libro::find($id));
        $this->getJson('/api/libros/'.$id)->assertNotFound();
    }

    public function test_invalid_creation_returns_422_without_writing(): void
    {
        $this->postJson('/api/libros', [])->assertUnprocessable()->assertJsonValidationErrors(['titulo', 'autor']);
        $this->postJson('/api/libros', ['titulo' => 'Libro', 'autor' => 'Autor', 'anio_publicacion' => 'texto'])->assertUnprocessable()->assertJsonValidationErrors('anio_publicacion');
        $this->assertSame(0, Libro::count());
    }

    public function test_invalid_update_preserves_original_data(): void
    {
        $libro = Libro::factory()->create(['titulo' => 'Original']);
        $this->putJson('/api/libros/'.$libro->id, ['titulo' => null])->assertUnprocessable()->assertJsonValidationErrors('titulo');
        $this->assertSame('Original', $libro->fresh()->titulo);
    }

    public function test_unknown_and_malformed_ids_return_404(): void
    {
        foreach (['000000000000000000000000', 'no-es-id'] as $id) {
            $this->getJson('/api/libros/'.$id)->assertNotFound();
            $this->putJson('/api/libros/'.$id, ['titulo' => 'Nuevo'])->assertNotFound();
            $this->deleteJson('/api/libros/'.$id)->assertNotFound();
        }
    }

    public function test_extra_attributes_are_not_stored(): void
    {
        $response = $this->postJson('/api/libros', ['titulo' => 'Libro', 'autor' => 'Autor', 'admin' => true])->assertCreated()->assertJsonMissingPath('admin');
        $this->assertArrayNotHasKey('admin', Libro::findOrFail($response->json('id'))->getAttributes());
    }
}
