# API de libros con Laravel y MongoDB

**Alumno:** Meza Corella César  
**Materia:** Aplicaciones web frontend NoSQL  
**Docente:** Roldán Aquino Segura  
**Repositorio:** https://github.com/YvnPretty/rest

## Iniciar la práctica

Requisitos: PHP 8.3 o superior con extensión `mongodb`, Composer y MongoDB local activo.
Versiones verificadas: PHP 8.5.4, Laravel 13.35.0 y mongodb/laravel-mongodb 5.11.0.

```bash
git clone https://github.com/YvnPretty/rest.git
cd rest
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

En la carpeta entregada en el Escritorio las dependencias y `.env` ya están preparados. Ejecuta `bash INICIAR.sh` o `php artisan serve` y abre http://127.0.0.1:8000. El botón ejecuta siete peticiones reales y elimina su libro de prueba al finalizar.

## Conexión MongoDB

```dotenv
DB_CONNECTION=mongodb
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DATABASE=api_libros
```

`config/database.php` registra el driver, la URI y la base de datos. `.env` es privado y no se publica; `.env.example` contiene una conexión local sin credenciales. Para Atlas, cambia MONGODB_URI por tu propia cadena de conexión y ejecuta `php artisan config:clear`.

MongoDB crea la colección `libros` con la primera inserción. Esta práctica no necesita las migraciones SQL del proyecto inicial. Los campos son `titulo`, `autor`, `genero`, `anio_publicacion`, `created_at`, `updated_at` y `_id`. El paquete presenta el identificador como `id` en JSON. No es un entero consecutivo: usa el ID devuelto al crear.

## Rutas y códigos

| Método | Ruta | Resultado |
|---|---|---|
| GET | /api/libros | Lista, 200 |
| POST | /api/libros | Crea, 201 |
| GET | /api/libros/{id} | Consulta, 200 o 404 |
| PUT / PATCH | /api/libros/{id} | Actualiza, 200 o 404 |
| DELETE | /api/libros/{id} | Elimina, 204 o 404 |

Datos inválidos devuelven 422. POST exige título y autor. Género y año aceptan null; año debe ser entero. PUT admite actualización parcial para conservar la guía original; PATCH es el verbo apropiado para ese comportamiento. En una API con reemplazo estricto, PUT exigiría la representación completa.

```bash
curl -i -H 'Accept: application/json' http://127.0.0.1:8000/api/libros
curl -i -X POST http://127.0.0.1:8000/api/libros \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"titulo":"Don Quijote","autor":"Miguel de Cervantes","genero":"Novela","anio_publicacion":1605}'
# Sustituye ID por el id real de la respuesta anterior:
curl -i -H 'Accept: application/json' http://127.0.0.1:8000/api/libros/ID
curl -i -X PUT http://127.0.0.1:8000/api/libros/ID \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"titulo":"Don Quijote de la Mancha"}'
curl -i -X DELETE -H 'Accept: application/json' http://127.0.0.1:8000/api/libros/ID
```

## Archivos y conceptos

- `routes/web.php`: página HTML de demostración, con middleware web (sesiones y protección CSRF).
- `routes/api.php`: rutas JSON registradas desde `bootstrap/app.php`, con prefijo `/api` y sin sesiones por defecto. No se instaló Sanctum porque la práctica no requiere autenticación.
- `app/Models/Libro.php`: hereda de `MongoDB\Laravel\Eloquent\Model`, fija la colección y permite los cuatro campos con `$fillable`.
- `app/Http/Controllers/Api/LibroController.php`: valida entrada y responde con códigos HTTP.
- `Libro::all()`: consulta todos los documentos; `create()`: inserta; `find()`: encuentra o devuelve null; `findOrFail()`: lanza una excepción que Laravel convierte en 404; `update()`: guarda cambios; `delete()`: elimina.
- REST es un estilo arquitectónico; RESTful describe una API que sigue sus principios. Esta práctica aplica recursos, verbos HTTP y peticiones sin estado. JSON no es un requisito de REST, ni usar HTTP y JSON garantiza cumplir todas sus restricciones.

## Verificación

```bash
php artisan test --compact
php artisan route:list --path=api
```

Las pruebas usan una base separada `api_libros_test_<PID>` y limpian sus documentos. No requieren transacciones ni un replica set. La página inicial permite repetir las peticiones y ver las respuestas. Las capturas del reporte corresponden a una ejecución real.

Alcance académico local: sin autenticación ni paginación. Antes de exponerla a internet se deben incorporar controles de acceso y límites de consulta.

## Fuentes oficiales

- https://www.mongodb.com/docs/drivers/php/laravel-mongodb/current/get-started/
- https://www.mongodb.com/docs/drivers/php/laravel-mongodb/current/connect/connect-to-mongodb/
- https://laravel.com/docs/13.x/routing
