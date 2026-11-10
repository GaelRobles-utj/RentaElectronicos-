<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteApiController extends Controller
{
    // GET: Listar clientes
    public function index()
    {
        $clientes = Cliente::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Clientes recuperados correctamente.',
            'data' => $clientes->map(
                fn ($cliente) => $this->datosCliente($cliente)
            ),
        ], 200);
    }

    // GET: Consultar un cliente
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cliente encontrado correctamente.',
            'data' => $this->datosCliente($cliente),
        ], 200);
    }

    // POST: Registrar cliente
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:120',
            'correo' => 'required|email|max:150|unique:clientes,correo',
            'contrasena' => 'required|string|min:8|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

        $cliente = new Cliente();

        $cliente->nombres = $datos['nombres'];
        $cliente->apellidos = $datos['apellidos'];
        $cliente->correo = $datos['correo'];
        $cliente->contrasena = Hash::make($datos['contrasena']);
        $cliente->telefono = $datos['telefono'] ?? null;
        $cliente->direccion = $datos['direccion'] ?? null;
        $cliente->estado = $datos['estado'] ?? true;
        $cliente->imagen = null;

        $cliente->save();

        // Guardar imagen con el ID del cliente
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');

            $nombreArchivo = 'cliente_' . $cliente->id
                . '.' . strtolower($archivo->extension());

            $ruta = $archivo->storeAs(
                'clientes',
                $nombreArchivo,
                'public'
            );

            if (!$ruta) {
                $cliente->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo guardar la imagen del cliente.',
                ], 500);
            }

            $cliente->imagen = $ruta;
            $cliente->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cliente registrado correctamente.',
            'data' => $this->datosCliente($cliente),
        ], 201);
    }

    // PUT: Actualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado.',
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
                Rule::unique('clientes', 'correo')->ignore($cliente->id),
            ],
            'contrasena' => 'sometimes|nullable|string|min:8|max:255',
            'telefono' => 'sometimes|nullable|string|max:20',
            'direccion' => 'sometimes|nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'telefono',
            'direccion',
            'estado',
        ] as $campo) {
            if (array_key_exists($campo, $datos)) {
                $cliente->$campo = $datos[$campo];
            }
        }

        // Cambiar contraseña solamente cuando se envía una nueva
        if (!empty($datos['contrasena'])) {
            $cliente->contrasena = Hash::make($datos['contrasena']);
        }

        $rutaAnterior = $cliente->imagen;
        $rutaNueva = null;

        // Reemplazar imagen si se adjunta otra
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');

            $nombreArchivo = 'cliente_' . $cliente->id
                . '.' . strtolower($archivo->extension());

            $rutaNueva = $archivo->storeAs(
                'clientes',
                $nombreArchivo,
                'public'
            );

            if (!$rutaNueva) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar la imagen.',
                ], 500);
            }

            $cliente->imagen = $rutaNueva;
        }

        $cliente->save();

        // Eliminar imagen anterior si fue reemplazada por otra ruta
        if (
            $rutaNueva &&
            $rutaAnterior &&
            $rutaAnterior !== $rutaNueva
        ) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cliente actualizado correctamente.',
            'data' => $this->datosCliente($cliente),
        ], 200);
    }

    // DELETE: Desactivar cliente (borrado lógico)
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado.',
            ], 404);
        }

        $cliente->estado = false;
        $cliente->save();

        return response()->json([
            'success' => true,
            'message' => 'Cliente desactivado correctamente.',
        ], 200);
    }

    // Datos que se devolverán en JSON, sin exponer la contraseña
    private function datosCliente(Cliente $cliente)
    {
        return [
            'id' => $cliente->id,
            'nombres' => $cliente->nombres,
            'apellidos' => $cliente->apellidos,
            'correo' => $cliente->correo,
            'telefono' => $cliente->telefono,
            'direccion' => $cliente->direccion,
            'imagen' => $cliente->imagen,
            'imagen_url' => $cliente->imagen
                ? Storage::disk('public')->url($cliente->imagen)
                : null,
            'estado' => $cliente->estado,
            'created_at' => $cliente->created_at,
            'updated_at' => $cliente->updated_at,
        ];
    }
}