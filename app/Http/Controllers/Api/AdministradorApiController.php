<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdministradorApiController extends Controller
{
    // GET: Listar administradores
    public function index()
    {
        $administradores = Administrador::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Administradores recuperados correctamente.',
            'data' => $administradores->map(
                fn ($admin) => $this->datosAdministrador($admin)
            ),
        ], 200);
    }

    // GET: Consultar un administrador
    public function show($id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            return response()->json([
                'success' => false,
                'message' => 'Administrador no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Administrador encontrado.',
            'data' => $this->datosAdministrador($administrador),
        ], 200);
    }

    // POST: Registrar administrador
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:120',
            'correo' => 'required|email|max:150|unique:administradores,correo',
            'usuario' => 'required|string|max:80|unique:administradores,usuario',
            'contrasena' => 'required|string|min:8|max:255',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'rol' => [
                'required',
                Rule::in([
                    'superadministrador',
                    'administrador',
                    'empleado'
                ]),
            ],
            'estado' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();

        $administrador = new Administrador();

        $administrador->nombres = $datos['nombres'];
        $administrador->apellidos = $datos['apellidos'];
        $administrador->correo = $datos['correo'];
        $administrador->usuario = $datos['usuario'];
        $administrador->contrasena = Hash::make($datos['contrasena']);
        $administrador->rol = $datos['rol'];
        $administrador->estado = $datos['estado'] ?? true;
        $administrador->imagen = null;

        $administrador->save();

        // Guardar imagen utilizando el ID generado
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');

            $nombre = 'administrador_' . $administrador->id
                    . '.' . strtolower($archivo->extension());

            $ruta = $archivo->storeAs(
                'administradores',
                $nombre,
                'public'
            );

            if (!$ruta) {
                $administrador->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo guardar la imagen.',
                ], 500);
            }

            $administrador->imagen = $ruta;
            $administrador->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Administrador registrado correctamente.',
            'data' => $this->datosAdministrador($administrador),
        ], 201);
    }

    // PUT: Actualizar administrador
    public function update(Request $request, $id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            return response()->json([
                'success' => false,
                'message' => 'Administrador no encontrado.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombres' => 'sometimes|required|string|max:100',
            'apellidos' => 'sometimes|required|string|max:120',
            'correo' => [
                'sometimes',
                'required',
                'email',
                'max:150',
                Rule::unique('administradores', 'correo')
                    ->ignore($administrador->id),
            ],
            'usuario' => [
                'sometimes',
                'required',
                'string',
                'max:80',
                Rule::unique('administradores', 'usuario')
                    ->ignore($administrador->id),
            ],
            'contrasena' => 'sometimes|nullable|string|min:8|max:255',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'rol' => [
                'sometimes',
                'required',
                Rule::in([
                    'superadministrador',
                    'administrador',
                    'empleado'
                ]),
            ],
            'estado' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();

        foreach ([
            'nombres',
            'apellidos',
            'correo',
            'usuario',
            'rol',
            'estado'
        ] as $campo) {
            if (array_key_exists($campo, $datos)) {
                $administrador->$campo = $datos[$campo];
            }
        }

        // Solo cambiar la contraseña si se proporciona una nueva
        if (!empty($datos['contrasena'])) {
            $administrador->contrasena = Hash::make(
                $datos['contrasena']
            );
        }

        $rutaAnterior = $administrador->imagen;

        // Sustituir imagen si se envía una nueva
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');

            $nombre = 'administrador_' . $administrador->id
                    . '.' . strtolower($archivo->extension());

            $rutaNueva = $archivo->storeAs(
                'administradores',
                $nombre,
                'public'
            );

            if (!$rutaNueva) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar la imagen.',
                ], 500);
            }

            $administrador->imagen = $rutaNueva;
        }

        $administrador->save();

        // Eliminar imagen anterior cuando se reemplazó
        if (
            $request->hasFile('imagen') &&
            $rutaAnterior &&
            $rutaAnterior !== $administrador->imagen
        ) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        return response()->json([
            'success' => true,
            'message' => 'Administrador actualizado correctamente.',
            'data' => $this->datosAdministrador($administrador),
        ], 200);
    }

    // DELETE: Desactivar administrador (borrado lógico)
    public function destroy($id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            return response()->json([
                'success' => false,
                'message' => 'Administrador no encontrado.',
            ], 404);
        }

        $administrador->estado = false;
        $administrador->save();

        // Revocar los tokens del administrador desactivado
        $administrador->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Administrador desactivado y sus tokens revocados.',
        ], 200);
    }

    // Datos públicos del administrador
    private function datosAdministrador(Administrador $administrador)
    {
        return [
            'id' => $administrador->id,
            'nombres' => $administrador->nombres,
            'apellidos' => $administrador->apellidos,
            'correo' => $administrador->correo,
            'usuario' => $administrador->usuario,
            'imagen' => $administrador->imagen,
            'imagen_url' => $administrador->imagen
                ? Storage::disk('public')->url($administrador->imagen)
                : null,
            'rol' => $administrador->rol,
            'estado' => $administrador->estado,
            'created_at' => $administrador->created_at,
        ];
    }
}

