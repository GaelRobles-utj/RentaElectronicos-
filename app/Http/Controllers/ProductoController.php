<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Tipo;
use App\Models\Marca;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    // Mostrar el listado y permitir búsquedas.
    public function listado(Request $request)
    {
        $busqueda = trim((string) $request->query('buscar', ''));

        $productos = Producto::with(['tipo', 'marca', 'categoria'])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($filtro) use ($busqueda) {
                    $filtro->where('nombre', 'like', "%{$busqueda}%")
                        ->orWhere('descripcion', 'like', "%{$busqueda}%")
                        ->orWhereHas('marca', function ($marca) use ($busqueda) {
                            $marca->where('nombre', 'like', "%{$busqueda}%");
                        })
                        ->orWhereHas('categoria', function ($categoria) use ($busqueda) {
                            $categoria->where('nombre', 'like', "%{$busqueda}%");
                        });

                    if (ctype_digit($busqueda)) {
                        $filtro->orWhere('id', (int) $busqueda);
                    }
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('productos.listado', compact(
            'productos',
            'busqueda'
        ));
    }

    // Mostrar el formulario de registro.
    public function create()
    {
        $tipos = Tipo::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.create', compact(
            'tipos',
            'marcas',
            'categorias'
        ));
    }

    // Registrar un producto.
    public function store(Request $request)
    {
        $datos = $request->validate([
            'tipo_id' => ['required', 'integer', 'exists:tipos,id'],
            'marca_id' => ['required', 'integer', 'exists:marcas,id'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['required', 'string', 'max:500'],
            'precio_dia' => ['required', 'numeric', 'min:0'],
            'existencia' => ['required', 'integer', 'min:0'],
            'descuento' => ['required', 'numeric', 'min:0'],
            'imagen1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'estado' => ['required', 'in:0,1'],
        ], [
            'tipo_id.required' => 'Selecciona un tipo de producto.',
            'tipo_id.exists' => 'El tipo seleccionado no existe.',
            'marca_id.required' => 'Selecciona una marca.',
            'marca_id.exists' => 'La marca seleccionada no existe.',
            'categoria_id.required' => 'Selecciona una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar los 500 caracteres.',
            'precio_dia.required' => 'Indica el precio por día.',
            'precio_dia.numeric' => 'El precio debe ser numérico.',
            'precio_dia.min' => 'El precio no puede ser negativo.',
            'existencia.required' => 'Indica la existencia del producto.',
            'existencia.integer' => 'La existencia debe ser un número entero.',
            'existencia.min' => 'La existencia no puede ser negativa.',
            'descuento.required' => 'Indica el descuento.',
            'descuento.numeric' => 'El descuento debe ser numérico.',
            'descuento.min' => 'El descuento no puede ser negativo.',
            'imagen1.image' => 'La imagen 1 debe ser un archivo de imagen válido.',
            'imagen2.image' => 'La imagen 2 debe ser un archivo de imagen válido.',
            'imagen3.image' => 'La imagen 3 debe ser un archivo de imagen válido.',
            'imagen1.mimes' => 'La imagen 1 debe ser JPG, JPEG, PNG o WebP.',
            'imagen2.mimes' => 'La imagen 2 debe ser JPG, JPEG, PNG o WebP.',
            'imagen3.mimes' => 'La imagen 3 debe ser JPG, JPEG, PNG o WebP.',
            'imagen1.max' => 'La imagen 1 no debe superar los 2 MB.',
            'imagen2.max' => 'La imagen 2 no debe superar los 2 MB.',
            'imagen3.max' => 'La imagen 3 no debe superar los 2 MB.',
        ]);

        $producto = new Producto();

        $producto->tipo_id = $datos['tipo_id'];
        $producto->marca_id = $datos['marca_id'];
        $producto->categoria_id = $datos['categoria_id'];
        $producto->nombre = $datos['nombre'];
        $producto->descripcion = $datos['descripcion'];
        $producto->precio_dia = $datos['precio_dia'];
        $producto->existencia = $datos['existencia'];
        $producto->descuento = $datos['descuento'];
        $producto->estado = (int) $datos['estado'];

        // Guardar las imágenes seleccionadas.
        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            $producto->{$campo} = $request->hasFile($campo)
                ? $request->file($campo)->store('productos', 'public')
                : null;
        }

        $producto->save();

        return redirect()
            ->route('productos.listado')
            ->with('success', 'Producto registrado correctamente.');
    }

    // Mostrar el formulario de edición.
    public function edit($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:productos,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('productos.listado')
                ->with('error', 'El producto no existe.');
        }

        $producto = Producto::find($id);

        // Incluir todas las opciones para que la relación
        // guardada siga apareciendo aunque esté inactiva.
        $tipos = Tipo::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.edit', compact(
            'producto',
            'tipos',
            'marcas',
            'categorias'
        ));
    }

    // Actualizar un producto.
    public function update(Request $request, $id)
    {
        $validatorId = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:productos,id']
        );

        if ($validatorId->fails()) {
            return redirect()
                ->route('productos.listado')
                ->with('error', 'El producto no existe.');
        }

        $datos = $request->validate([
            'tipo_id' => ['required', 'integer', 'exists:tipos,id'],
            'marca_id' => ['required', 'integer', 'exists:marcas,id'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['required', 'string', 'max:500'],
            'precio_dia' => ['required', 'numeric', 'min:0'],
            'existencia' => ['required', 'integer', 'min:0'],
            'descuento' => ['required', 'numeric', 'min:0'],
            'imagen1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'estado' => ['required', 'in:0,1'],
        ]);

        $producto = Producto::find($id);

        $producto->tipo_id = $datos['tipo_id'];
        $producto->marca_id = $datos['marca_id'];
        $producto->categoria_id = $datos['categoria_id'];
        $producto->nombre = $datos['nombre'];
        $producto->descripcion = $datos['descripcion'];
        $producto->precio_dia = $datos['precio_dia'];
        $producto->existencia = $datos['existencia'];
        $producto->descuento = $datos['descuento'];
        $producto->estado = (int) $datos['estado'];

        // Solo sustituir las imágenes para las que se haya
        // seleccionado un archivo nuevo.
        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                $imagenAnterior = $producto->{$campo};

                $nuevaImagen = $request->file($campo)
                    ->store('productos', 'public');

                $producto->{$campo} = $nuevaImagen;

                // Evitar intentar borrar rutas web externas.
                if (
                    $imagenAnterior &&
                    !filter_var($imagenAnterior, FILTER_VALIDATE_URL) &&
                    Storage::disk('public')->exists($imagenAnterior)
                ) {
                    Storage::disk('public')->delete($imagenAnterior);
                }
            }
        }

        $producto->save();

        return redirect()
            ->route('productos.edit', $producto->id)
            ->with('success', 'Producto actualizado correctamente.');
    }

    // Mostrar los detalles de un producto.
    public function show($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:productos,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('productos.listado')
                ->with('error', 'El producto no existe.');
        }

        $producto = Producto::with([
            'tipo',
            'marca',
            'categoria'
        ])->find($id);

        return view('productos.show', compact('producto'));
    }

    // Dar de baja sin borrar físicamente el registro.
    public function destroy($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:productos,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('productos.listado')
                ->with('error', 'El producto no existe.');
        }

        $producto = Producto::find($id);
        $producto->estado = 0;
        $producto->save();

        return redirect()
            ->route('productos.listado')
            ->with('success', 'Producto dado de baja correctamente.');
    }
}