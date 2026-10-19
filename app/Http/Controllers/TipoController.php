<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TipoController extends Controller
{
    // Listado y búsqueda de tipos.
    public function listado(Request $request)
    {
        $busqueda = trim((string) $request->query('buscar', ''));

        $tipos = Tipo::query()
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

        return view('tipos.listado', compact('tipos', 'busqueda'));
    }

    // Formulario de registro.
    public function create()
    {
        return view('tipos.create');
    }

    // Registrar un tipo.
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'estado' => ['required', 'in:0,1'],
        ], [
            'nombre.required' => 'El nombre del tipo es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado del tipo.',
        ]);

        $tipo = new Tipo();
        $tipo->nombre = $datos['nombre'];
        $tipo->estado = (int) $datos['estado'];
        $tipo->imagen = null;

        if ($request->hasFile('imagen')) {
            $tipo->imagen = $request->file('imagen')
                ->store('tipos', 'public');
        }

        $tipo->save();

        return redirect()
            ->route('tipos.listado')
            ->with('success', 'Tipo registrado correctamente.');
    }

    // Formulario de edición.
    public function edit($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:tipos,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('tipos.listado')
                ->with('error', 'El tipo no existe.');
        }

        $tipo = Tipo::find($id);

        return view('tipos.edit', compact('tipo'));
    }

    // Actualizar un tipo.
    public function update(Request $request, $id)
    {
        $validatorId = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:tipos,id']
        );

        if ($validatorId->fails()) {
            return redirect()
                ->route('tipos.listado')
                ->with('error', 'El tipo no existe.');
        }

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'estado' => ['required', 'in:0,1'],
        ], [
            'nombre.required' => 'El nombre del tipo es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado del tipo.',
        ]);

        $tipo = Tipo::find($id);

        $tipo->nombre = $datos['nombre'];
        $tipo->estado = (int) $datos['estado'];

        // Sustituir la imagen solamente si se seleccionó otra.
        if ($request->hasFile('imagen')) {
            $imagenAnterior = $tipo->imagen;

            $tipo->imagen = $request->file('imagen')
                ->store('tipos', 'public');

            // No intentar borrar imágenes alojadas en sitios externos.
            if (
                $imagenAnterior &&
                !filter_var($imagenAnterior, FILTER_VALIDATE_URL) &&
                Storage::disk('public')->exists($imagenAnterior)
            ) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        $tipo->save();

        return redirect()
            ->route('tipos.edit', $tipo->id)
            ->with('success', 'Tipo actualizado correctamente.');
    }

    // Detalles de un tipo.
    public function show($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:tipos,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('tipos.listado')
                ->with('error', 'El tipo no existe.');
        }

        $tipo = Tipo::withCount('productos')->find($id);

        return view('tipos.show', compact('tipo'));
    }

    // Baja lógica: conservar el registro y sus relaciones.
    public function destroy($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer|exists:tipos,id']
        );

        if ($validator->fails()) {
            return redirect()
                ->route('tipos.listado')
                ->with('error', 'El tipo no existe.');
        }

        $tipo = Tipo::find($id);
        $tipo->estado = 0;
        $tipo->save();

        return redirect()
            ->route('tipos.listado')
            ->with('success', 'Tipo dado de baja correctamente.');
    }
}