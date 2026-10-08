# API de libros con Laravel y MongoDB

Alumno: Meza Corella César  
Materia: Aplicaciones web frontend NoSQL  
Docente: Roldán Aquino Segura

## Iniciar

Se necesita PHP 8.3 o superior con la extensión mongodb, Composer y MongoDB local activo.

```bash
git clone https://github.com/YvnPretty/rest.git
cd rest
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

La conexión local se configura en `.env`:

```dotenv
DB_CONNECTION=mongodb
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DATABASE=api_libros
```

MongoDB crea la colección `libros` al insertar el primer libro. No se necesitan migraciones SQL. `.env` no se publica; `.env.example` contiene la configuración de ejemplo.

## Rutas

| Método | Ruta | Acción |
|---|---|---|
| GET | /api/libros | Listar libros |
| POST | /api/libros | Crear un libro |
| GET | /api/libros/{id} | Consultar un libro |
| PUT / PATCH | /api/libros/{id} | Actualizar un libro |
| DELETE | /api/libros/{id} | Eliminar un libro |

Título y autor son obligatorios. Género y año son opcionales; el año debe ser entero. Para consultar, actualizar o eliminar, se usa el identificador que devuelve MongoDB.

```bash
curl -i -X POST http://127.0.0.1:8000/api/libros \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"titulo":"Don Quijote","autor":"Miguel de Cervantes","genero":"Novela","anio_publicacion":1605}'

curl -i -H 'Accept: application/json' http://127.0.0.1:8000/api/libros
```

La API responde 200 al consultar o actualizar, 201 al crear, 204 al eliminar, 404 si el libro no existe y 422 si los datos son inválidos.

## Archivos principales

- `app/Models/Libro.php`: modelo y campos permitidos.
- `app/Http/Controllers/Api/LibroController.php`: operaciones y validación.
- `routes/api.php`: rutas de libros.
- `config/database.php`: conexión a MongoDB.

## Pruebas

```bash
php artisan test --compact
php artisan route:list --path=api
```

Las pruebas de libros usan una base separada `api_libros_test_<PID>`.

Código fuente: https://github.com/YvnPretty/rest
