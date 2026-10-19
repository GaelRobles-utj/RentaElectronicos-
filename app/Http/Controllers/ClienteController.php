<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function listar()
    {
        $clientes = Cliente::orderByDesc('id')->get();

        return view('clientes.listado', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:120'],
            'correo' => ['required', 'email', 'max:150', 'unique:clientes,correo'],
            'contrasena' => ['required', 'string', 'min:8', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'estado' => ['required', 'boolean'],
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Ingresa un correo electrónico válido.',
            'correo.unique' => 'Este correo ya está registrado para otro cliente.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado del cliente.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->except('contrasena'));
        }

        $cliente = new Cliente();
        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->correo = $request->correo;
        $cliente->contrasena = Hash::make($request->contrasena);
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->imagen = null;
        $cliente->estado = $request->estado;
        $cliente->save();

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('clientes', 'public');

            if ($rutaImagen) {
                $cliente->imagen = $rutaImagen;
                $cliente->save();
            }
        }

        return redirect()->route('clientes.listado')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', 'exists:clientes,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('clientes.listado')
                ->with('error', 'El cliente no existe.');
        }

        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $validatorId = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', 'exists:clientes,id'],
        ]);

        if ($validatorId->fails()) {
            return redirect()->route('clientes.listado')
                ->with('error', 'El cliente no existe.');
        }

        $cliente = Cliente::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:120'],
            'correo' => ['required', 'email', 'max:150', Rule::unique('clientes', 'correo')->ignore($cliente->id)],
            'contrasena' => ['nullable', 'string', 'min:8', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'estado' => ['required', 'boolean'],
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Ingresa un correo electrónico válido.',
            'correo.unique' => 'Este correo ya está registrado para otro cliente.',
            'contrasena.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WebP.',
            'imagen.max' => 'La imagen no debe superar los 2 MB.',
            'estado.required' => 'Selecciona el estado del cliente.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->except('contrasena'));
        }

        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->correo = $request->correo;

        if ($request->filled('contrasena')) {
            $cliente->contrasena = Hash::make($request->contrasena);
        }

        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->estado = $request->estado;

        // Solo reemplaza la foto si se subió una nueva imagen correctamente.
        if ($request->hasFile('imagen')) {
            $rutaAnterior = $cliente->imagen;
            $rutaNueva = $request->file('imagen')->store('clientes', 'public');

            if ($rutaNueva) {
                $cliente->imagen = $rutaNueva;

                if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
                    Storage::disk('public')->delete($rutaAnterior);
                }
            }
        }

        $cliente->save();

        return redirect()->route('clientes.edit', $id)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', 'exists:clientes,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('clientes.listado')
                ->with('error', 'El cliente no existe.');
        }

        $cliente = Cliente::findOrFail($id);

        return view('clientes.show', compact('cliente'));
    }

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', 'exists:clientes,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('clientes.listado')
                ->with('error', 'El cliente no existe.');
        }

        $cliente = Cliente::findOrFail($id);
        $cliente->estado = 0;
        $cliente->save();

        return redirect()->route('clientes.listado')
            ->with('success', 'Cliente desactivado correctamente.');
    }
}

