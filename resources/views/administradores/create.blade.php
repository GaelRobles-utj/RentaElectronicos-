
@extends('layouts.app')

@section('title', 'Registrar administrador | RentaElectrónicos')
@section('page-title', 'Registrar administrador')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    <div>
        <p class="text-sm text-slate-500">
            Gestión de módulos / Administradores / Registrar
        </p>

        <h1 class="mt-2 text-3xl font-bold text-slate-900">
            Registrar administrador
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Completa los datos para agregar un nuevo administrador al sistema.
        </p>
    </div>

    @if(session('success'))
        <div role="alert"
             class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div role="alert"
             class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div role="alert"
             class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">

            <p class="font-semibold">Revisa los siguientes campos:</p>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('administradores.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        @csrf

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-bold text-slate-900">
                Información general
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Los campos marcados con * son obligatorios.
            </p>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2">

            <div>
                <label for="nombres"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Nombres *
                </label>

                <input
                    type="text"
                    id="nombres"
                    name="nombres"
                    required
                    maxlength="100"
                    autocomplete="given-name"
                    value="{{ old('nombres') }}"
                    class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                @error('nombres')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="apellidos"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Apellidos *
                </label>

                <input
                    type="text"
                    id="apellidos"
                    name="apellidos"
                    required
                    maxlength="120"
                    autocomplete="family-name"
                    value="{{ old('apellidos') }}"
                    class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                @error('apellidos')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            
<div class="min-w-0">
    <label for="correo"
           class="mb-2 block text-sm font-semibold text-slate-700">
        Correo electrónico *
    </label>

    <input
        type="email"
        id="correo"
        name="correo"
        required
        maxlength="150"
        autocomplete="email"
        value="{{ old('correo') }}"
        placeholder="ejemplo@correo.com"
        class="block w-full min-w-0 rounded-xl border border-slate-300
               bg-white p-3 text-sm text-slate-900
               focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

    @error('correo')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
                
<div>
    <label for="imagen"
           class="mb-2 block text-sm font-semibold text-slate-700">
        Imagen del administrador
    </label>

    <input
        type="file"
        id="imagen"
        name="imagen"
        accept=".jpg,.jpeg,.png,.webp"
        class="block w-full cursor-pointer rounded-xl border border-slate-300
               bg-white text-sm text-slate-700
               file:mr-4 file:border-0 file:bg-blue-600
               file:px-4 file:py-3 file:font-semibold file:text-white
               hover:file:bg-blue-700">

    <p class="mt-1 text-xs text-slate-500">
        Formatos JPG, JPEG, PNG o WebP. Máximo 2 MB. Campo opcional.
    </p>

    @error('imagen')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

            <div>
                <label for="usuario"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Nombre de usuario *
                </label>

                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    required
                    maxlength="80"
                    autocomplete="username"
                    value="{{ old('usuario') }}"
                    class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                @error('usuario')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contrasena"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Contraseña *
                </label>

                <input
                    type="password"
                    id="contrasena"
                    name="contrasena"
                    required
                    minlength="8"
                    maxlength="255"
                    autocomplete="new-password"
                    class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                <p class="mt-1 text-xs text-slate-500">
                    Debe contener al menos 8 caracteres.
                </p>

                @error('contrasena')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rol"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Rol *
                </label>

                <select
                    id="rol"
                    name="rol"
                    required
                    class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    <option value="">Selecciona un rol</option>

                    <option value="superadministrador"
                        @selected(old('rol') === 'superadministrador')>
                        Superadministrador
                    </option>

                    <option value="administrador"
                        @selected(old('rol') === 'administrador')>
                        Administrador
                    </option>

                    <option value="empleado"
                        @selected(old('rol') === 'empleado')>
                        Empleado
                    </option>

                </select>

                @error('rol')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="imagen"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Ruta de imagen
                </label>

                <input
                    type="text"
                    id="imagen"
                    name="imagen"
                    maxlength="255"
                    value="{{ old('imagen') }}"
                    placeholder="Ej. administradores/administrador_1.jpg"
                    class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                <p class="mt-1 text-xs text-slate-500">
                    Campo opcional. Por ahora puedes escribir una ruta.
                </p>

                @error('imagen')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estado"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Estado *
                </label>

                <select
                    id="estado"
                    name="estado"
                    required
                    class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    <option value="1"
                        @selected(old('estado', '1') === '1')>
                        Activo
                    </option>

                    <option value="0"
                        @selected(old('estado') === '0')>
                        Inactivo
                    </option>

                </select>

                @error('estado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex flex-col justify-between gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center">

            <p class="text-xs text-slate-500">
                Verifica la información antes de registrar al administrador.
            </p>

            <div class="flex gap-3">

                <a href="{{ route('administradores.listado') }}"
                   class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Cancelar
                </a>

                <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Registrar administrador
                </button>

            </div>
        </div>

    </form>

</div>

@endsection
