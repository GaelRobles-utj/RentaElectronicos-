<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // POST: Iniciar sesión y generar token
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Buscar al administrador por su usuario
        $administrador = Administrador::where(
            'usuario',
            $request->usuario
        )->first();

        if (!$administrador) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario o contraseña incorrectos.',
            ], 401);
        }

        // Verificar que el administrador esté activo
        if ((int) $administrador->estado !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'La cuenta del administrador está desactivada.',
            ], 403);
        }

        // Comprobar la contraseña cifrada
        if (!Hash::check(
            $request->contrasena,
            $administrador->contrasena
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario o contraseña incorrectos.',
            ], 401);
        }

        // Generar token personal de Sanctum
        $token = $administrador->createToken('postman')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión correcto.',
            'token_type' => 'Bearer',
            'token' => $token,
            'administrador' => [
                'id' => $administrador->id,
                'nombres' => $administrador->nombres,
                'apellidos' => $administrador->apellidos,
                'usuario' => $administrador->usuario,
                'correo' => $administrador->correo,
                'rol' => $administrador->rol,
            ],
        ], 200);
    }

    // POST: Cerrar sesión y revocar token
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada y token revocado correctamente.',
        ], 200);
    }

    // GET: Consultar administrador autenticado
    public function perfil(Request $request)
    {
        $administrador = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Administrador autenticado correctamente.',
            'data' => [
                'id' => $administrador->id,
                'nombres' => $administrador->nombres,
                'apellidos' => $administrador->apellidos,
                'usuario' => $administrador->usuario,
                'correo' => $administrador->correo,
                'rol' => $administrador->rol,
            ],
        ], 200);
    }
}