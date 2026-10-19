<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TipoController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/administradores/crear', [AdministradorController::class, 'create'])
    ->name('administradores.create');

Route::post('/administradores', [AdministradorController::class, 'store'])
    ->name('administradores.store');

// ADMINISTRADORES
Route::get('/administradores/{id}/edit', [AdministradorController::class, 'edit'])
    ->name('administradores.edit');

Route::put('/administradores/{id}', [AdministradorController::class, 'update'])
    ->name('administradores.update');


// CLIENTES
Route::get('/clientes/listado', [ClienteController::class, 'listar'])
    ->name('clientes.listado');

Route::get('/clientes/crear', [ClienteController::class, 'create'])
    ->name('clientes.create');

// Alias para que funcione el botón + del menú lateral actual.
Route::get('/clientes/formulario', [ClienteController::class, 'create'])
    ->name('clientes.formulario');

Route::post('/clientes', [ClienteController::class, 'store'])
    ->name('clientes.store');

Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])
    ->name('clientes.edit');

Route::put('/clientes/{id}', [ClienteController::class, 'update'])
    ->name('clientes.update');

Route::get('/clientes/{id}', [ClienteController::class, 'show'])
    ->name('clientes.show');

Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])
    ->name('clientes.destroy');



// Marcas
Route::get('/marcas/listado', [MarcaController::class, 'listado'])
    ->name('marcas.listado');

Route::get('/marcas/create', [MarcaController::class, 'create'])
    ->name('marcas.create');

Route::post('/marcas', [MarcaController::class, 'store'])
    ->name('marcas.store');

Route::get('/marcas/{id}/edit', [MarcaController::class, 'edit'])
    ->name('marcas.edit');

Route::put('/marcas/{id}', [MarcaController::class, 'update'])
    ->name('marcas.update');

Route::delete('/marcas/{id}', [MarcaController::class, 'destroy'])
    ->name('marcas.destroy');

Route::get('/marcas/{id}', [MarcaController::class, 'show'])
    ->name('marcas.show');


// Categorías
Route::get('/categorias/listado', [CategoriaController::class, 'listado'])
    ->name('categorias.listado');

Route::get('/categorias/create', [CategoriaController::class, 'create'])
    ->name('categorias.create');

Route::post('/categorias', [CategoriaController::class, 'store'])
    ->name('categorias.store');

Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit'])
    ->name('categorias.edit');

Route::put('/categorias/{id}', [CategoriaController::class, 'update'])
    ->name('categorias.update');

Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])
    ->name('categorias.destroy');

Route::get('/categorias/{id}', [CategoriaController::class, 'show'])
    ->name('categorias.show');



 // Productos
Route::get('/productos/listado', [ProductoController::class, 'listado'])
    ->name('productos.listado');

Route::get('/productos/create', [ProductoController::class, 'create'])
    ->name('productos.create');

Route::post('/productos', [ProductoController::class, 'store'])
    ->name('productos.store');

Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])
    ->name('productos.edit');

Route::put('/productos/{id}', [ProductoController::class, 'update'])
    ->name('productos.update');

Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])
    ->name('productos.destroy');

Route::get('/productos/{id}', [ProductoController::class, 'show'])
    ->name('productos.show');


// Tipos de producto
Route::get('/tipos/listado', [TipoController::class, 'listado'])
    ->name('tipos.listado');

Route::get('/tipos/create', [TipoController::class, 'create'])
    ->name('tipos.create');

Route::post('/tipos', [TipoController::class, 'store'])
    ->name('tipos.store');

Route::get('/tipos/{id}/edit', [TipoController::class, 'edit'])
    ->name('tipos.edit');

Route::put('/tipos/{id}', [TipoController::class, 'update'])
    ->name('tipos.update');

Route::delete('/tipos/{id}', [TipoController::class, 'destroy'])
    ->name('tipos.destroy');

Route::get('/tipos/{id}', [TipoController::class, 'show'])
    ->name('tipos.show');

// LISTAR ADMINISTRADORES
Route::get('/administradores/listado', [AdministradorController::class, 'listar'])
    ->name('administradores.listado');

    // MOSTRAR Y BORRAR ADMINISTRADORES
Route::get('/administradores/{id}', [AdministradorController::class, 'show'])
    ->name('administradores.show');

Route::delete('/administradores/{id}', [AdministradorController::class, 'destroy'])
    ->name('administradores.destroy');


// MOSTRAR Y BORRAR CLIENTES
Route::get('/clientes/{id}', [ClienteController::class, 'show'])
    ->name('clientes.show');

Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])
    ->name('clientes.destroy');


// MOSTRAR Y BORRAR MARCAS
Route::get('/marcas/{id}', [MarcaController::class, 'show'])
    ->name('marcas.show');

Route::delete('/marcas/{id}', [MarcaController::class, 'destroy'])
    ->name('marcas.destroy');


// MOSTRAR Y BORRAR CATEGORÍAS
Route::get('/categorias/{id}', [CategoriaController::class, 'show'])
    ->name('categorias.show');

Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])
    ->name('categorias.destroy');


// MOSTRAR Y BORRAR PRODUCTOS
Route::get('/productos/{id}', [ProductoController::class, 'show'])
    ->name('productos.show');

Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])
    ->name('productos.destroy');