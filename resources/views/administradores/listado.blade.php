@extends('layouts.app')

@section('title', 'Administradores | RentaElectrónicos')
@section('page-title', 'Administradores')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500"><span>Gestión de módulos</span><span>/</span><span class="font-medium text-blue-600">Administradores</span></div>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Administradores</h1>
            <p class="mt-2 text-sm text-slate-500">Consulta y administra los usuarios responsables del sistema.</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total de registros</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $administradores->count() }}</p>
        </div>
    </section>

    @if(session('success'))
        <div role="alert" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center">
            <div><h2 class="text-lg font-bold text-slate-900">Listado de registros</h2><p class="mt-1 text-sm text-slate-500">Información registrada en la base de datos.</p></div>
            <div class="relative w-full sm:max-w-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m16 16 4 4"/></svg>
                </div>
                <input type="search" id="buscarAdministrador" placeholder="Buscar por nombre, correo o usuario..." aria-label="Buscar administradores" class="block w-full rounded-xl border border-slate-300 bg-slate-50 p-2.5 pl-10 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th scope="col" class="px-6 py-4">ID</th><th scope="col" class="px-6 py-4">Administrador</th><th scope="col" class="px-6 py-4">Usuario</th><th scope="col" class="px-6 py-4">Rol</th><th scope="col" class="px-6 py-4">Estado</th><th scope="col" class="px-6 py-4 text-right">Acciones</th></tr></thead>
                <tbody id="tablaAdministradores" class="divide-y divide-slate-100">
                    @forelse($administradores as $administrador)
                        @php
                            $rutaImagen = $administrador->imagen;
                            if ($rutaImagen && !\Illuminate\Support\Facades\Storage::disk('public')->exists($rutaImagen) && !str_contains($rutaImagen, '/')) {
                                $rutaAlterna = 'administradores/' . $rutaImagen;
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rutaAlterna)) $rutaImagen = $rutaAlterna;
                            }
                            $busqueda = $administrador->id . ' ' . $administrador->nombres . ' ' . $administrador->apellidos . ' ' . $administrador->correo . ' ' . $administrador->usuario . ' ' . $administrador->rol . ' ' . ($administrador->estado ? 'activo' : 'inactivo');
                        @endphp
                        <tr data-search="{{ $busqueda }}" class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-500">#{{ $administrador->id }}</td>
                            <td class="px-6 py-4"><div class="flex items-center gap-3">
                                @if($rutaImagen && \Illuminate\Support\Facades\Storage::disk('public')->exists($rutaImagen))
                                    <img src="{{ asset('storage/' . $rutaImagen) }}" alt="Foto de {{ $administrador->nombres }}" loading="lazy" class="h-11 w-11 shrink-0 rounded-full border border-slate-200 object-cover">
                                @else
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">{{ strtoupper(substr($administrador->nombres, 0, 1)) }}</div>
                                @endif
                                <div class="min-w-0"><p class="font-semibold text-slate-900">{{ $administrador->nombres }} {{ $administrador->apellidos }}</p><p class="mt-1 break-all text-xs text-slate-500">{{ $administrador->correo }}</p>@if($administrador->imagen)<p class="mt-1 text-xs text-slate-400">{{ basename($administrador->imagen) }}</p>@endif</div>
                            </div></td>
                            <td class="whitespace-nowrap px-6 py-4">{{ $administrador->usuario }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ['superadministrador' => 'Superadministrador', 'administrador' => 'Administrador', 'empleado' => 'Empleado'][$administrador->rol] ?? $administrador->rol }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($administrador->estado == 1)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Activo</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactivo</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4"><div class="flex items-center justify-end gap-2">
                                <a href="{{ route('administradores.show', $administrador->id) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">Mostrar</a>
                                <a href="{{ route('administradores.edit', $administrador->id) }}" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">Editar</a>
                                @if($administrador->estado == 1)
                                    <form action="{{ route('administradores.destroy', $administrador->id) }}" method="POST" onsubmit="return confirm('¿Deseas desactivar este administrador?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">Borrar</button>
                                    </form>
                                @endif
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 20v-1a6 6 0 0 1 12 0v1M16 11a3 3 0 1 0 0-6m2 9a5 5 0 0 1 3 4.5V20"/></svg></div><h3 class="mt-4 font-semibold text-slate-900">No hay administradores registrados</h3><p class="mt-1 text-sm text-slate-500">Los registros aparecerán aquí cuando se agreguen.</p></td></tr>
                    @endforelse
                    <tr id="sinResultados" class="hidden"><td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">No se encontraron administradores con esa búsqueda.</td></tr>
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 bg-slate-50 px-6 py-3"><p id="contadorRegistros" class="text-xs text-slate-500">Se muestran {{ $administradores->count() }} registros.</p></div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buscador = document.getElementById('buscarAdministrador');
    const filas = Array.from(document.querySelectorAll('#tablaAdministradores tr[data-search]'));
    const sinResultados = document.getElementById('sinResultados');
    const contador = document.getElementById('contadorRegistros');

    if (!buscador || !sinResultados || !contador) return;

    const normalizar = (texto) => texto.toLocaleLowerCase('es').normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();

    function filtrarAdministradores() {
        const consulta = normalizar(buscador.value);
        let visibles = 0;

        filas.forEach((fila) => {
            const contenido = normalizar(fila.dataset.search || '');
            const coincide = contenido.includes(consulta);
            fila.classList.toggle('hidden', !coincide);
            if (coincide) visibles++;
        });

        sinResultados.classList.toggle('hidden', visibles > 0 || filas.length === 0);
        contador.textContent = consulta
            ? `Se muestran ${visibles} de ${filas.length} registros.`
            : `Se muestran ${filas.length} registros.`;
    }

    buscador.addEventListener('input', filtrarAdministradores);
    filtrarAdministradores();
});
</script>
@endsection