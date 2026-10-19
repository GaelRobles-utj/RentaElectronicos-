<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdministradorController extends Controller
{
    public function create()
    {
        return view('administradores.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:120'],
            'correo' => ['required', 'email', 'max:150', 'unique:administradores,correo'],
            'usuario' => ['required', 'string', 'max:80', 'unique:administradores,usuario'],
            'contrasena' => ['required', 'string', 'min:8', 'max:255'],
            'imagen' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'rol' => ['required', Rule::in(['superadministrador', 'administrador', 'empleado'])],
            'estado' => ['required', 'boolean'],
        ], [
            'correo.unique' => 'Este correo ya está registrado.',
            'usuario.unique' => 'Este nombre de usuario ya está registrado.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe estar en formato JPG, JPEG, PNG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except(['contrasena', 'imagen']));
        }

        $administrador = new Administrador();
        $administrador->nombres = $request->input('nombres');
        $administrador->apellidos = $request->input('apellidos');
        $administrador->correo = $request->input('correo');
        $administrador->usuario = $request->input('usuario');
        $administrador->contrasena = Hash::make($request->input('contrasena'));
        // La imagen se asigna después de guardar el registro y obtener su ID.
        $administrador->imagen = null;
        $administrador->rol = $request->input('rol');
        $administrador->estado = $request->input('estado');
        $administrador->save();

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $extension = strtolower($archivo->extension());
            $nombreArchivo = 'administrador_' . $administrador->id . '.' . $extension;
            $ruta = $archivo->storeAs('administradores', $nombreArchivo, 'public');

            if ($ruta === false) {
                $administrador->delete();
                return back()->with('error', 'No se pudo guardar la imagen. Intenta nuevamente.')
                    ->withInput($request->except(['contrasena', 'imagen']));
            }

            $administrador->imagen = $ruta;
            $administrador->save();
        }

        return redirect()->route('administradores.listado')
            ->with('success', 'Administrador registrado correctamente.');
    }

    public function listar()
    {
        $administradores = Administrador::orderBy('id', 'desc')->get();

        return view('administradores.listado', compact('administradores'));
    }

    public function show($id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador || !filter_var($id, FILTER_VALIDATE_INT)) {
            return redirect()->route('administradores.listado')
                ->with('error', 'El administrador no existe.');
        }

        // Normaliza rutas antiguas que pudieran guardar solo el nombre del archivo.
        $administrador->imagen = $this->rutaImagenExistente($administrador->imagen);

        return view('administradores.show', compact('administrador'));
    }

    public function edit($id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador || !filter_var($id, FILTER_VALIDATE_INT)) {
            return redirect()->route('administradores.listado')
                ->with('error', 'El administrador no existe.');
        }

        $administrador->imagen = $this->rutaImagenExistente($administrador->imagen);

        return view('administradores.edit', compact('administrador'));
    }

    public function update(Request $request, $id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador || !filter_var($id, FILTER_VALIDATE_INT)) {
            return redirect()->route('administradores.listado')
                ->with('error', 'El administrador no existe.');
        }

        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:120'],
            'correo' => [
                'required', 'email', 'max:150',
                Rule::unique('administradores', 'correo')->ignore($administrador->id),
            ],
            'usuario' => [
                'required', 'string', 'max:80',
                Rule::unique('administradores', 'usuario')->ignore($administrador->id),
            ],
            'contrasena' => ['nullable', 'string', 'min:8', 'max:255'],
            'imagen' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'rol' => ['required', Rule::in(['superadministrador', 'administrador', 'empleado'])],
            'estado' => ['required', 'boolean'],
        ], [
            'correo.unique' => 'Este correo ya está registrado por otro administrador.',
            'usuario.unique' => 'Este nombre de usuario ya está registrado por otro administrador.',
            'contrasena.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe estar en formato JPG, JPEG, PNG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput($request->except(['contrasena', 'imagen']));
        }

        $rutaAnterior = $this->rutaImagenExistente($administrador->imagen);
        $rutaNueva = $rutaAnterior;

        // Primero guarda la nueva imagen. La anterior solo se elimina si la nueva se guardó bien.
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $extension = strtolower($archivo->extension());
            $nombreArchivo = 'administrador_' . $administrador->id . '.' . $extension;
            $rutaGuardada = $archivo->storeAs('administradores', $nombreArchivo, 'public');

            if ($rutaGuardada === false) {
                return back()->with('error', 'No se pudo guardar la nueva imagen. La foto anterior se conservó.')
                    ->withInput($request->except(['contrasena', 'imagen']));
            }

            $rutaNueva = $rutaGuardada;
        }

        $administrador->nombres = $request->input('nombres');
        $administrador->apellidos = $request->input('apellidos');
        $administrador->correo = $request->input('correo');
        $administrador->usuario = $request->input('usuario');

        if ($request->filled('contrasena')) {
            $administrador->contrasena = Hash::make($request->input('contrasena'));
        }

        $administrador->imagen = $rutaNueva;
        $administrador->rol = $request->input('rol');
        $administrador->estado = $request->input('estado');
        $administrador->save();

        if ($request->hasFile('imagen') && $rutaAnterior && $rutaAnterior !== $rutaNueva
            && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        return redirect()->route('administradores.edit', $id)
            ->with('success', 'Administrador actualizado correctamente.');
    }

    public function destroy($id)
    {
        $administrador = Administrador::find($id);

        if (!$administrador || !filter_var($id, FILTER_VALIDATE_INT)) {
            return redirect()->route('administradores.listado')
                ->with('error', 'El administrador no existe.');
        }

        $administrador->estado = 0;
        $administrador->save();

        return redirect()->route('administradores.listado')
            ->with('success', 'El administrador se desactivó correctamente.');
    }

    /** Devuelve una ruta de imagen existente y admite registros antiguos con solo el nombre. */
    private function rutaImagenExistente(?string $ruta): ?string
    {
        if (!$ruta) {
            return null;
        }

        $ruta = ltrim($ruta, '/');

        if (Storage::disk('public')->exists($ruta)) {
            return $ruta;
        }

        if (!str_contains($ruta, '/')) {
            $rutaCompleta = 'administradores/' . $ruta;
            if (Storage::disk('public')->exists($rutaCompleta)) {
                return $rutaCompleta;
            }
        }

        return $ruta;
    }
}


