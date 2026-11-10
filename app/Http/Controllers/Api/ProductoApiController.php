<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductoApiController extends Controller
{
    // Relaciones que se mostrarán en las respuestas JSON
    private function relaciones()
    {
        return [
            'tipo:id,nombre',
            'marca:id,nombre',
            'categoria:id,nombre',
        ];
    }

    // GET: Listar productos activos e inactivos
    public function index()
    {
        $productos = Producto::with($this->relaciones())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Productos recuperados correctamente.',
            'data' => $productos->map(
                fn ($producto) => $this->datosProducto($producto)
            ),
        ], 200);
    }

    // GET: Consultar un producto por ID
    public function show($id)
    {
        $producto = Producto::with($this->relaciones())->find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto encontrado correctamente.',
            'data' => $this->datosProducto($producto),
        ], 200);
    }

    // POST: Registrar producto con hasta tres imágenes
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_id' => 'required|integer|exists:tipos,id',
            'marca_id' => 'required|integer|exists:marcas,id',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio_dia' => 'required|numeric|min:0',
            'existencia' => 'required|integer|min:0',
            'descuento' => 'nullable|numeric|min:0|max:100',
            'estado' => 'sometimes|boolean',
            'imagen1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'imagen2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'imagen3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();

        $producto = new Producto();

        $producto->tipo_id = $datos['tipo_id'];
        $producto->marca_id = $datos['marca_id'];
        $producto->categoria_id = $datos['categoria_id'];
        $producto->nombre = $datos['nombre'];
        $producto->descripcion = $datos['descripcion'] ?? null;
        $producto->precio_dia = $datos['precio_dia'];
        $producto->existencia = $datos['existencia'];
        $producto->descuento = $datos['descuento'] ?? 0;
        $producto->estado = $datos['estado'] ?? true;

        $producto->imagen1 = null;
        $producto->imagen2 = null;
        $producto->imagen3 = null;

        $producto->save();

        $rutasGuardadas = [];

        try {
            foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {

                if ($request->hasFile($campo)) {
                    $archivo = $request->file($campo);

                    $extension = strtolower($archivo->extension());

                    $nombreArchivo = 'producto_' . $producto->id
                        . '_' . $campo . '.' . $extension;

                    $ruta = $archivo->storeAs(
                        'productos',
                        $nombreArchivo,
                        'public'
                    );

                    if (!$ruta) {
                        throw new \RuntimeException(
                            'No se pudo guardar ' . $campo
                        );
                    }

                    $rutasGuardadas[] = $ruta;

                    $producto->$campo = $ruta;
                }
            }

            $producto->save();

        } catch (Throwable $e) {

            foreach ($rutasGuardadas as $ruta) {
                Storage::disk('public')->delete($ruta);
            }

            $producto->delete();

            return response()->json([
                'success' => false,
                'message' => 'No se pudieron guardar las imágenes del producto.',
            ], 500);
        }

        $producto->load($this->relaciones());

        return response()->json([
            'success' => true,
            'message' => 'Producto registrado correctamente.',
            'data' => $this->datosProducto($producto),
        ], 201);
    }

    // PUT: Actualizar producto y reemplazar imágenes cuando se envíen
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo_id' => 'sometimes|required|integer|exists:tipos,id',
            'marca_id' => 'sometimes|required|integer|exists:marcas,id',
            'categoria_id' => 'sometimes|required|integer|exists:categorias,id',
            'nombre' => 'sometimes|required|string|max:150',
            'descripcion' => 'sometimes|nullable|string',
            'precio_dia' => 'sometimes|required|numeric|min:0',
            'existencia' => 'sometimes|required|integer|min:0',
            'descuento' => 'sometimes|nullable|numeric|min:0|max:100',
            'estado' => 'sometimes|boolean',
            'imagen1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'imagen2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'imagen3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $datos = $validator->validated();

        // Actualizar solamente los campos enviados
        foreach ([
            'tipo_id',
            'marca_id',
            'categoria_id',
            'nombre',
            'descripcion',
            'precio_dia',
            'existencia',
            'descuento',
            'estado',
        ] as $campo) {
            if (array_key_exists($campo, $datos)) {
                $producto->$campo = $datos[$campo];
            }
        }

        $rutasNuevas = [];
        $rutasAnteriores = [];

        try {
            foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {

                if ($request->hasFile($campo)) {
                    $archivo = $request->file($campo);

                    $rutasAnteriores[$campo] = $producto->$campo;

                    $extension = strtolower($archivo->extension());

                    $nombreArchivo = 'producto_' . $producto->id
                        . '_' . $campo . '.' . $extension;

                    $ruta = $archivo->storeAs(
                        'productos',
                        $nombreArchivo,
                        'public'
                    );

                    if (!$ruta) {
                        throw new \RuntimeException(
                            'No se pudo actualizar ' . $campo
                        );
                    }

                    $rutasNuevas[$campo] = $ruta;
                    $producto->$campo = $ruta;
                }
            }

            $producto->save();

            // Eliminar las imágenes anteriores después de guardar
            foreach ($rutasAnteriores as $campo => $rutaAnterior) {
                if (
                    $rutaAnterior &&
                    $rutaAnterior !== $rutasNuevas[$campo]
                ) {
                    Storage::disk('public')->delete($rutaAnterior);
                }
            }

        } catch (Throwable $e) {

            // Quitar las imágenes nuevas que se alcanzaron a guardar
            foreach ($rutasNuevas as $rutaNueva) {
                Storage::disk('public')->delete($rutaNueva);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el producto y sus imágenes.',
            ], 500);
        }

        $producto->load($this->relaciones());

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente.',
            'data' => $this->datosProducto($producto),
        ], 200);
    }

    // DELETE: Desactivar producto (borrado lógico)
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        $producto->estado = false;
        $producto->save();

        return response()->json([
            'success' => true,
            'message' => 'Producto desactivado correctamente.',
        ], 200);
    }

    // Preparar los datos públicos del producto
    private function datosProducto(Producto $producto)
    {
        return [
            'id' => $producto->id,

            'tipo_id' => $producto->tipo_id,
            'tipo_nombre' => $producto->tipo?->nombre,

            'marca_id' => $producto->marca_id,
            'marca_nombre' => $producto->marca?->nombre,

            'categoria_id' => $producto->categoria_id,
            'categoria_nombre' => $producto->categoria?->nombre,

            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'precio_dia' => $producto->precio_dia,
            'existencia' => $producto->existencia,
            'descuento' => $producto->descuento,

            'imagen1' => $producto->imagen1,
            'imagen1_url' => $producto->imagen1
                ? Storage::disk('public')->url($producto->imagen1)
                : null,

            'imagen2' => $producto->imagen2,
            'imagen2_url' => $producto->imagen2
                ? Storage::disk('public')->url($producto->imagen2)
                : null,

            'imagen3' => $producto->imagen3,
            'imagen3_url' => $producto->imagen3
                ? Storage::disk('public')->url($producto->imagen3)
                : null,

            'estado' => $producto->estado,
            'created_at' => $producto->created_at,
            'updated_at' => $producto->updated_at,
        ];
    }
}

