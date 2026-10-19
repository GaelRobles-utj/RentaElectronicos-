<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    // Mostrar listado y buscar marcas por nombre o ID.
    public function listado(Request $request)
    {
        $busqueda = trim((string) $request->query('buscar', ''));

        $marcas = Marca::query()
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

        return view('marcas.listado', compact('marcas', 'busqueda'));
    }

    // Mostrar formulario de registro.
    public function create()
    {
        return view('marcas.create');
    }

    // Registrar una marca.
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
            'nombre.required' => 'El nombre de la marca es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser PNG, JPG, JPEG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado de la marca.',
        ]);

        $marca = new Marca();
        $marca->nombre = $datos['nombre'];
        $marca->estado = (int) $datos['estado'];
        $marca->imagen = null;

        if ($request->hasFile('imagen')) {
            $marca->imagen = $request->file('imagen')
                ->store('marcas', 'public');
        }

        $marca->save();

        return redirect()
            ->route('marcas.listado')
            ->with('success', 'Marca registrada correctamente.');
    }

    // Mostrar formulario de edición.
    public function edit($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:marcas,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('marcas.listado')
                ->with('error', 'La marca no existe.');
        }

        $marca = Marca::find($id);

        return view('marcas.edit', compact('marca'));
    }

    // Actualizar una marca.
    public function update(Request $request, $id)
    {
        $validatorId = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:marcas,id']
        );

        if ($validatorId->fails()) {
            return redirect()
                ->route('marcas.listado')
                ->with('error', 'La marca no existe.');
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
            'nombre.required' => 'El nombre de la marca es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser PNG, JPG, JPEG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado de la marca.',
        ]);

        $marca = Marca::find($id);

        $marca->nombre = $datos['nombre'];
        $marca->estado = (int) $datos['estado'];

        // Solo cambiar la imagen si se seleccionó una nueva.
        if ($request->hasFile('imagen')) {
            $imagenAnterior = $marca->imagen;

            $marca->imagen = $request->file('imagen')
                ->store('marcas', 'public');

            // Eliminar la imagen anterior si estaba guardada localmente.
            if (
                $imagenAnterior &&
                Storage::disk('public')->exists($imagenAnterior)
            ) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        $marca->save();

        return redirect()
            ->route('marcas.edit', $marca->id)
            ->with('success', 'Marca actualizada correctamente.');
    }

    // Mostrar los detalles de una marca.
    public function show($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:marcas,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('marcas.listado')
                ->with('error', 'La marca no existe.');
        }

        $marca = Marca::find($id);

        return view('marcas.show', compact('marca'));
    }

    // Dar de baja una marca sin borrar físicamente el registro.
    public function destroy($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:marcas,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('marcas.listado')
                ->with('error', 'La marca no existe.');
        }

        $marca = Marca::find($id);
        $marca->estado = 0;
        $marca->save();

        return redirect()
            ->route('marcas.listado')
            ->with('success', 'La marca fue dada de baja correctamente.');
    }
}