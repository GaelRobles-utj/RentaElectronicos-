<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\ClienteApiController; 
use App\Http\Controllers\Api\AdministradorApiController;

// ========================================
// RUTAS PÚBLICAS
// ========================================

// Iniciar sesión y obtener token
Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');


// ========================================
// RUTAS PROTEGIDAS CON SANCTUM
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Consultar administrador autenticado
    Route::get('/perfil', [AuthController::class, 'perfil'])
        ->name('api.perfil');

    // Cerrar sesión y revocar token
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('api.logout');

    // ========================================
    // CRUD API DE PRODUCTOS
    // ========================================

    Route::apiResource('productos', ProductoApiController::class);

    // ========================================
    // CRUD API DE CLIENTES
    // ========================================

    Route::apiResource('clientes', ClienteApiController::class);

    // CRUD API DE ADMINISTRADORES
    Route::apiResource(
    'administradores',
    AdministradorApiController::class
);
});
