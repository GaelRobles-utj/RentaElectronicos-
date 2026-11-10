
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TipoController;

// ==========================================
// RUTAS PÚBLICAS: NO REQUIEREN AUTENTICACIÓN
// ==========================================

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');


// ==========================================
// RUTAS PROTEGIDAS: REQUIEREN GUARD ADMIN
// ==========================================

Route::middleware('auth:admin')->group(function () {

    // INICIO Y CIERRE DE SESIÓN

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');


    // ======================================
    // ADMINISTRADORES
    // ======================================

    Route::get('/administradores', function () {
        return redirect()->route('administradores.listado');
    })->name('administradores.index');

    Route::get('/administradores/listado', [
        AdministradorController::class, 'listar'
    ])->name('administradores.listado');

    Route::get('/administradores/crear', [
        AdministradorController::class, 'create'
    ])->name('administradores.create');

    Route::post('/administradores', [
        AdministradorController::class, 'store'
    ])->name('administradores.store');

    Route::get('/administradores/{id}/edit', [
        AdministradorController::class, 'edit'
    ])->name('administradores.edit');

    Route::put('/administradores/{id}', [
        AdministradorController::class, 'update'
    ])->name('administradores.update');

    Route::get('/administradores/{id}', [
        AdministradorController::class, 'show'
    ])->name('administradores.show');

    Route::delete('/administradores/{id}', [
        AdministradorController::class, 'destroy'
    ])->name('administradores.destroy');


    // ======================================
    // CLIENTES
    // ======================================

    Route::get('/clientes/listado', [
        ClienteController::class, 'listar'
    ])->name('clientes.listado');

    Route::get('/clientes/crear', [
        ClienteController::class, 'create'
    ])->name('clientes.create');

    Route::get('/clientes/formulario', [
        ClienteController::class, 'create'
    ])->name('clientes.formulario');

    Route::post('/clientes', [
        ClienteController::class, 'store'
    ])->name('clientes.store');

    Route::get('/clientes/{id}/edit', [
        ClienteController::class, 'edit'
    ])->name('clientes.edit');

    Route::put('/clientes/{id}', [
        ClienteController::class, 'update'
    ])->name('clientes.update');

    Route::get('/clientes/{id}', [
        ClienteController::class, 'show'
    ])->name('clientes.show');

    Route::delete('/clientes/{id}', [
        ClienteController::class, 'destroy'
    ])->name('clientes.destroy');


    // ======================================
    // MARCAS
    // ======================================

    Route::get('/marcas/listado', [
        MarcaController::class, 'listado'
    ])->name('marcas.listado');

    Route::get('/marcas/create', [
        MarcaController::class, 'create'
    ])->name('marcas.create');

    Route::get('/marcas/formulario', [
        MarcaController::class, 'create'
    ])->name('marcas.formulario');

    Route::post('/marcas', [
        MarcaController::class, 'store'
    ])->name('marcas.store');

    Route::get('/marcas/{id}/edit', [
        MarcaController::class, 'edit'
    ])->name('marcas.edit');

    Route::put('/marcas/{id}', [
        MarcaController::class, 'update'
    ])->name('marcas.update');

    Route::get('/marcas/{id}', [
        MarcaController::class, 'show'
    ])->name('marcas.show');

    Route::delete('/marcas/{id}', [
        MarcaController::class, 'destroy'
    ])->name('marcas.destroy');


    // ======================================
    // CATEGORÍAS
    // ======================================

    Route::get('/categorias/listado', [
        CategoriaController::class, 'listado'
    ])->name('categorias.listado');

    Route::get('/categorias/create', [
        CategoriaController::class, 'create'
    ])->name('categorias.create');

    Route::get('/categorias/formulario', [
        CategoriaController::class, 'create'
    ])->name('categorias.formulario');

    Route::post('/categorias', [
        CategoriaController::class, 'store'
    ])->name('categorias.store');

    Route::get('/categorias/{id}/edit', [
        CategoriaController::class, 'edit'
    ])->name('categorias.edit');

    Route::put('/categorias/{id}', [
        CategoriaController::class, 'update'
    ])->name('categorias.update');

    Route::get('/categorias/{id}', [
        CategoriaController::class, 'show'
    ])->name('categorias.show');

    Route::delete('/categorias/{id}', [
        CategoriaController::class, 'destroy'
    ])->name('categorias.destroy');


    // ======================================
    // PRODUCTOS
    // ======================================

    Route::get('/productos/listado', [
        ProductoController::class, 'listado'
    ])->name('productos.listado');

    Route::get('/productos/create', [
        ProductoController::class, 'create'
    ])->name('productos.create');

    Route::get('/productos/formulario', [
        ProductoController::class, 'create'
    ])->name('productos.formulario');

    Route::post('/productos', [
        ProductoController::class, 'store'
    ])->name('productos.store');

    Route::get('/productos/{id}/edit', [
        ProductoController::class, 'edit'
    ])->name('productos.edit');

    Route::put('/productos/{id}', [
        ProductoController::class, 'update'
    ])->name('productos.update');

    Route::get('/productos/{id}', [
        ProductoController::class, 'show'
    ])->name('productos.show');

    Route::delete('/productos/{id}', [
        ProductoController::class, 'destroy'
    ])->name('productos.destroy');


    // ======================================
    // TIPOS
    // ======================================

    Route::get('/tipos/listado', [
        TipoController::class, 'listado'
    ])->name('tipos.listado');

    Route::get('/tipos/create', [
        TipoController::class, 'create'
    ])->name('tipos.create');

    Route::get('/tipos/formulario', [
        TipoController::class, 'create'
    ])->name('tipos.formulario');

    Route::post('/tipos', [
        TipoController::class, 'store'
    ])->name('tipos.store');

    Route::get('/tipos/{id}/edit', [
        TipoController::class, 'edit'
    ])->name('tipos.edit');

    Route::put('/tipos/{id}', [
        TipoController::class, 'update'
    ])->name('tipos.update');

    Route::get('/tipos/{id}', [
        TipoController::class, 'show'
    ])->name('tipos.show');

    Route::delete('/tipos/{id}', [
        TipoController::class, 'destroy'
    ])->name('tipos.destroy');

});
