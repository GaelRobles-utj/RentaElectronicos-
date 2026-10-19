@extends('layouts.app')

@section('title', 'Editar cliente | RentaElectrónicos')
@section('page-title', 'Editar cliente')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div>
        <p class="text-sm text-slate-500">Gestión de módulos / Clientes / Editar</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-900">Editar cliente</h1>
        <p class="mt-2 text-sm text-slate-500">Modifica la información del registro #{{ $cliente->id }}.</p>
    </div>

    @if(session('success'))
        <div role="alert" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Revisa los siguientes campos:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" enctype="multipart/form-data" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @csrf
        @method('PUT')
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-bold text-slate-900">Información general</h2>
            <p class="mt-1 text-sm text-slate-500">Los campos marcados con * son obligatorios.</p>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2">
            <div>
                <label for="nombres" class="mb-2 block text-sm font-semibold text-slate-700">Nombres *</label>
                <input type="text" id="nombres" name="nombres" required maxlength="100" autocomplete="given-name" value="{{ old('nombres', $cliente->nombres) }}" class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                @error('nombres')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="apellidos" class="mb-2 block text-sm font-semibold text-slate-700">Apellidos *</label>
                <input type="text" id="apellidos" name="apellidos" required maxlength="120" autocomplete="family-name" value="{{ old('apellidos', $cliente->apellidos) }}" class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                @error('apellidos')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="correo" class="mb-2 block text-sm font-semibold text-slate-700">Correo electrónico *</label>
                <input type="email" id="correo" name="correo" required maxlength="150" autocomplete="email" value="{{ old('correo', $cliente->correo) }}" placeholder="ejemplo@correo.com" class="block w-full min-w-0 rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                @error('correo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="contrasena" class="mb-2 block text-sm font-semibold text-slate-700">Nueva contraseña</label>
                <input type="password" id="contrasena" name="contrasena" minlength="8" maxlength="255" autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <p class="mt-1 text-xs text-slate-500">Déjala vacía para conservar la contraseña actual.</p>
                @error('contrasena')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="telefono" class="mb-2 block text-sm font-semibold text-slate-700">Teléfono *</label>
                <input type="tel" id="telefono" name="telefono" required maxlength="20" autocomplete="tel" value="{{ old('telefono', $cliente->telefono) }}" class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                @error('telefono')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="direccion" class="mb-2 block text-sm font-semibold text-slate-700">Dirección *</label>
                <input type="text" id="direccion" name="direccion" required maxlength="255" autocomplete="street-address" value="{{ old('direccion', $cliente->direccion) }}" class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                @error('direccion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="imagen" class="mb-2 block text-sm font-semibold text-slate-700">Cambiar fotografía</label>
                @if($cliente->imagen && file_exists(public_path('storage/' . $cliente->imagen)))
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <img src="{{ asset('storage/' . $cliente->imagen) }}" alt="Fotografía actual del cliente" class="h-16 w-16 rounded-xl border border-slate-200 object-cover">
                        <div class="min-w-0"><p class="text-sm font-semibold text-slate-700">Fotografía actual</p><p class="break-all text-xs text-slate-500">{{ basename($cliente->imagen) }}</p></div>
                    </div>
                @elseif($cliente->imagen)
                    <p class="mb-2 break-all text-xs text-slate-500">Ruta guardada: {{ $cliente->imagen }}</p>
                @endif
                <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full cursor-pointer rounded-xl border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-3 file:font-semibold file:text-white hover:file:bg-blue-700">
                <p class="mt-1 text-xs text-slate-500">Opcional. JPG, JPEG, PNG o WebP; máximo 2 MB. Si no eliges otra, se conserva la actual.</p>
                @error('imagen')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="estado" class="mb-2 block text-sm font-semibold text-slate-700">Estado *</label>
                <select id="estado" name="estado" required class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="1" @selected((string)old('estado', $cliente->estado) === '1')>Activo</option>
                    <option value="0" @selected((string)old('estado', $cliente->estado) === '0')>Inactivo</option>
                </select>
                @error('estado')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex flex-col justify-between gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center">
            <p class="text-xs text-slate-500">Los cambios se guardarán al confirmar la actualización.</p>
            <div class="flex gap-3">
                <a href="{{ route('clientes.listado') }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancelar</a>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Guardar cambios</button>
            </div>
        </div>
    </form>
</div>
@endsection