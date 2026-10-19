@extends('layouts.app')

@section('title', 'Clientes | RentaElectrónicos')
@section('page-title', 'Clientes')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500"><span>Gestión de módulos</span><span>/</span><span class="font-medium text-blue-600">Clientes</span></div>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Clientes</h1>
            <p class="mt-2 text-sm text-slate-500">Consulta y administra la información de los clientes registrados.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total de registros</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $clientes->count() }}</p>
            </div>
            <a href="{{ route('clientes.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"><span class="text-lg leading-none">+</span> Nuevo cliente</a>
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
            <div>
                <h2 class="text-lg font-bold text-slate-900">Listado de registros</h2>
                <p class="mt-1 text-sm text-slate-500">Busca por nombre, correo, teléfono o dirección.</p>
            </div>
            <div class="relative w-full sm:max-w-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m16 16 4 4"/></svg>
                </div>
                <input type="search" id="buscarCliente" placeholder="Buscar cliente..." class="block w-full rounded-xl border border-slate-300 bg-slate-50 p-2.5 pl-10 text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID</th>
                        <th scope="col" class="px-6 py-4">Cliente</th>
                        <th scope="col" class="px-6 py-4">Teléfono</th>
                        <th scope="col" class="px-6 py-4">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaClientes" class="divide-y divide-slate-100">
                    @forelse($clientes as $cliente)
                        <tr data-search="{{ strtolower($cliente->id . ' ' . $cliente->nombres . ' ' . $cliente->apellidos . ' ' . $cliente->correo . ' ' . $cliente->telefono . ' ' . $cliente->direccion) }}" class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-500">#{{ $cliente->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($cliente->imagen && file_exists(public_path('storage/' . $cliente->imagen)))
                                        <img src="{{ asset('storage/' . $cliente->imagen) }}" alt="Fotografía del cliente {{ $cliente->id }}" class="h-11 w-11 shrink-0 rounded-full border border-slate-200 object-cover">
                                    @else
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">{{ strtoupper(substr($cliente->nombres ?? 'C', 0, 1)) }}</div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900">{{ $cliente->nombres }} {{ $cliente->apellidos }}</p>
                                        <p class="mt-1 break-all text-xs text-slate-500">{{ $cliente->correo }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">{{ $cliente->telefono }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($cliente->estado == 1)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Activo</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactivo</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">Mostrar</a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">Editar</a>
                                    @if($cliente->estado == 1)
                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirm('¿Deseas desactivar este cliente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">Desactivar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 20v-1a6 6 0 0 1 12 0v1M16 11a3 3 0 1 0 0-6m2 9a5 5 0 0 1 3 4.5V20"/></svg>
                                </div>
                                <h3 class="mt-4 font-semibold text-slate-900">No hay clientes registrados</h3>
                                <p class="mt-1 text-sm text-slate-500">Los registros aparecerán aquí cuando se agreguen.</p>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="sinResultadosClientes" class="hidden"><td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">No se encontraron clientes con esa búsqueda.</td></tr>
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 bg-slate-50 px-6 py-3"><p class="text-xs text-slate-500">Se muestran {{ $clientes->count() }} registros.</p></div>
    </section>
</div>

<script>
    const buscadorClientes = document.getElementById('buscarCliente');
    const filasClientes = document.querySelectorAll('#tablaClientes tr[data-search]');
    const sinResultadosClientes = document.getElementById('sinResultadosClientes');

    if (buscadorClientes && sinResultadosClientes) {
        buscadorClientes.addEventListener('input', function () {
            const normalizar = (texto) => texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const consulta = normalizar(this.value.trim());
            let visibles = 0;

            filasClientes.forEach(function (fila) {
                const coincide = normalizar(fila.dataset.search).includes(consulta);
                fila.classList.toggle('hidden', !coincide);
                if (coincide) visibles++;
            });

            sinResultadosClientes.classList.toggle('hidden', visibles > 0 || filasClientes.length === 0);
        });
    }
</script>
@endsection