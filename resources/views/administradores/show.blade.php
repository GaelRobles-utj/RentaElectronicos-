@extends('layouts.app')

@section('title', 'Detalle del administrador | RentaElectrónicos')
@section('page-title', 'Detalle del administrador')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm text-slate-500">Gestión de módulos / Administradores / Detalle</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Información del administrador</h1>
            <p class="mt-2 text-sm text-slate-500">Consulta los datos del registro seleccionado.</p>
        </div>
        <a href="{{ route('administradores.listado') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Volver al listado</a>
    </div>

    @if(session('success'))
        <div role="alert" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
            <div class="flex flex-wrap items-center gap-4">
                @if($administrador->imagen && \Illuminate\Support\Facades\Storage::disk('public')->exists($administrador->imagen))
                    <img src="{{ asset('storage/' . $administrador->imagen) }}" alt="Fotografía de {{ $administrador->nombres }} {{ $administrador->apellidos }}" class="h-20 w-20 rounded-2xl object-cover ring-1 ring-slate-200">
                @else
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-2xl font-bold text-blue-700">{{ strtoupper(substr($administrador->nombres, 0, 1)) }}</div>
                @endif
                <div class="min-w-0">
                    <h2 class="text-xl font-bold text-slate-900">{{ $administrador->nombres }} {{ $administrador->apellidos }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Registro #{{ $administrador->id }}</p>
                    <p class="mt-1 break-all text-sm text-slate-500">{{ $administrador->correo }}</p>
                </div>
                <div class="ml-auto">
                    @if($administrador->estado == 1)
                        <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">Activo</span>
                    @else
                        <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">Inactivo</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 p-4"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nombres</p><p class="mt-2 font-semibold text-slate-900">{{ $administrador->nombres }}</p></div>
            <div class="rounded-xl border border-slate-200 p-4"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Apellidos</p><p class="mt-2 font-semibold text-slate-900">{{ $administrador->apellidos }}</p></div>
            <div class="rounded-xl border border-slate-200 p-4"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Correo electrónico</p><p class="mt-2 break-words font-semibold text-slate-900">{{ $administrador->correo }}</p></div>
            <div class="rounded-xl border border-slate-200 p-4"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nombre de usuario</p><p class="mt-2 break-words font-semibold text-slate-900">{{ $administrador->usuario }}</p></div>
            <div class="rounded-xl border border-slate-200 p-4"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Rol asignado</p><p class="mt-2 font-semibold text-slate-900">{{ ['superadministrador' => 'Superadministrador', 'administrador' => 'Administrador', 'empleado' => 'Empleado'][$administrador->rol] ?? $administrador->rol }}</p></div>
            <div class="rounded-xl border border-slate-200 p-4"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Estado del registro</p><p class="mt-2 font-semibold text-slate-900">{{ $administrador->estado == 1 ? 'Activo' : 'Inactivo' }}</p></div>
            @if($administrador->imagen)
                <div class="rounded-xl border border-slate-200 p-4 sm:col-span-2"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Archivo de imagen</p><p class="mt-2 break-all font-semibold text-slate-900">{{ basename($administrador->imagen) }}</p></div>
            @endif
        </div>

        <div class="flex flex-col justify-between gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center">
            <p class="text-xs text-slate-500">Vista de consulta. Los datos no se pueden modificar aquí.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('administradores.edit', $administrador->id) }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Editar administrador</a>
                @if($administrador->estado == 1)
                    <form action="{{ route('administradores.destroy', $administrador->id) }}" method="POST" onsubmit="return confirm('¿Deseas desactivar este administrador?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">Desactivar</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

