<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    // Mostrar el listado y permitir búsquedas.
    public function listado(Request $request)
    {
        $busqueda = trim((string) $request->query('buscar', ''));

        $categorias = Categoria::query()
            ->withCount('productos')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($filtro) use ($busqueda) {
                    $filtro->where('nombre', 'like', "%{$busqueda}%");

                    if (ctype_digit($busqueda)) {
                        $filtro->orWhere('id', (int) $busqueda);
                    }
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('categorias.listado', compact(
            'categorias',
            'busqueda'
        ));
    }

    // Mostrar el formulario de registro.
    public function create()
    {
        return view('categorias.create');
    }

    // Registrar una categoría.
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
            'estado' => ['required', 'in:0,1'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser PNG, JPG, JPEG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado de la categoría.',
        ]);

        $categoria = new Categoria();
        $categoria->nombre = $datos['nombre'];
        $categoria->estado = (int) $datos['estado'];
        $categoria->imagen = null;

        if ($request->hasFile('imagen')) {
            $categoria->imagen = $request->file('imagen')
                ->store('categorias', 'public');
        }

        $categoria->save();

        return redirect()
            ->route('categorias.listado')
            ->with('success', 'Categoría registrada correctamente.');
    }

    // Mostrar el formulario de edición.
    public function edit($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:categorias,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('categorias.listado')
                ->with('error', 'La categoría no existe.');
        }

        $categoria = Categoria::find($id);

        return view('categorias.edit', compact('categoria'));
    }

    // Actualizar una categoría.
    public function update(Request $request, $id)
    {
        $validatorId = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:categorias,id']
        );

        if ($validatorId->fails()) {
            return redirect()
                ->route('categorias.listado')
                ->with('error', 'La categoría no existe.');
        }

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
            'estado' => ['required', 'in:0,1'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser PNG, JPG, JPEG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado de la categoría.',
        ]);

        $categoria = Categoria::find($id);

        $categoria->nombre = $datos['nombre'];
        $categoria->estado = (int) $datos['estado'];

        // Conservar la imagen actual si no se selecciona otra.
        if ($request->hasFile('imagen')) {
            $imagenAnterior = $categoria->imagen;

            $categoria->imagen = $request->file('imagen')
                ->store('categorias', 'public');

            // Borrar el archivo anterior solo si existe en
            // el almacenamiento público de Laravel.
            if (
                $imagenAnterior &&
                Storage::disk('public')->exists($imagenAnterior)
            ) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        $categoria->save();

        return redirect()
            ->route('categorias.edit', $categoria->id)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    // Mostrar los detalles de una categoría.
    public function show($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:categorias,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('categorias.listado')
                ->with('error', 'La categoría no existe.');
        }

        $categoria = Categoria::withCount('productos')->find($id);

        return view('categorias.show', compact('categoria'));
    }

    // Dar de baja sin eliminar el registro de la base de datos.
    public function destroy($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:categorias,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('categorias.listado')
                ->with('error', 'La categoría no existe.');
        }

        $categoria = Categoria::find($id);
        $categoria->estado = 0;
        $categoria->save();

        return redirect()
            ->route('categorias.listado')
            ->with('success', 'Categoría dada de baja correctamente.');
    }
}