
@extends('layouts.app')

@section('title', 'Tipos | RentaElectrónicos')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tipos de producto
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Administra los tipos disponibles para clasificar los productos.
            </p>
        </div>

        <a href="{{ route('tipos.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
            + Registrar tipo
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg bg-red-100 p-4 text-sm text-red-800 dark:bg-red-900 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <form action="{{ route('tipos.listado') }}"
              method="GET"
              class="mb-5 flex flex-col gap-3 sm:flex-row">

            <input
                type="text"
                name="buscar"
                value="{{ $busqueda }}"
                placeholder="Buscar por nombre o ID..."
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

            <button type="submit"
                    class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-900">
                Buscar
            </button>

            <a href="{{ route('tipos.listado') }}"
               class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Limpiar
            </a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                <thead class="bg-gray-100 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-5 py-3">ID</th>
                        <th class="px-5 py-3">Imagen</th>
                        <th class="px-5 py-3">Nombre</th>
                        <th class="px-5 py-3">Productos</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tipos as $tipo)
                        <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">

                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $tipo->id }}
                            </td>

                            <td class="px-5 py-4">
                                @if($tipo->imagen)
                                    @php
                                        $imagenUrl = filter_var($tipo->imagen, FILTER_VALIDATE_URL)
                                            ? $tipo->imagen
                                            : asset('storage/' . ltrim(str_replace('storage/', '', $tipo->imagen), '/'));
                                    @endphp

                                    <img src="{{ $imagenUrl }}"
                                         alt="{{ $tipo->nombre }}"
                                         class="h-12 w-12 rounded-lg object-cover">
                                @else
                                    <span class="text-gray-400">Sin imagen</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $tipo->nombre }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $tipo->productos_count }}
                            </td>

                            <td class="px-5 py-4">
                                @if($tipo->estado)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Activo
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-2">

                                    <a href="{{ route('tipos.show', $tipo->id) }}"
                                       class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                        Ver
                                    </a>

                                    <a href="{{ route('tipos.edit', $tipo->id) }}"
                                       class="font-medium text-yellow-600 hover:underline dark:text-yellow-400">
                                        Editar
                                    </a>

                                    @if($tipo->estado)
                                        <form action="{{ route('tipos.destroy', $tipo->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Deseas dar de baja este tipo?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="font-medium text-red-600 hover:underline dark:text-red-400">
                                                Dar de baja
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron tipos de producto.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $tipos->links() }}
        </div>
    </div>
</div>
@endsection