<?php

use App\Http\Controllers\Api\LibroController;
use Illuminate\Support\Facades\Route;

Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');
Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libros.show');
Route::put('/libros/{id}', [LibroController::class, 'update'])->name('libros.update');
Route::patch('/libros/{id}', [LibroController::class, 'update']);
Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('libros.destroy');
