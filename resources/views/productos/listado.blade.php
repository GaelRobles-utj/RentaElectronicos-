
@extends('layouts.app')

@section('title', 'Productos | RentaElectrónicos')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Productos
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Consulta y administra el inventario de productos electrónicos.
            </p>
        </div>

        <a href="{{ route('productos.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
            + Registrar producto
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

        <form action="{{ route('productos.listado') }}"
              method="GET"
              class="mb-5 flex flex-col gap-3 sm:flex-row">

            <input type="text"
                   name="buscar"
                   value="{{ $busqueda }}"
                   placeholder="Buscar por nombre, ID, marca o categoría..."
                   class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

            <button type="submit"
                    class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-900">
                Buscar
            </button>

            <a href="{{ route('productos.listado') }}"
               class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Limpiar
            </a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                <thead class="bg-gray-100 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Imagen</th>
                        <th class="px-4 py-3">Producto</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Marca</th>
                        <th class="px-4 py-3">Categoría</th>
                        <th class="px-4 py-3">Precio/día</th>
                        <th class="px-4 py-3">Existencia</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($productos as $producto)
                        <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">

                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $producto->id }}
                            </td>

                            <td class="px-4 py-4">
                                @if($producto->imagen1)
                                    @php
                                        $imagenUrl = filter_var($producto->imagen1, FILTER_VALIDATE_URL)
                                            ? $producto->imagen1
                                            : asset('storage/' . ltrim(str_replace('storage/', '', $producto->imagen1), '/'));
                                    @endphp

                                    <img src="{{ $imagenUrl }}"
                                         alt="{{ $producto->nombre }}"
                                         class="h-14 w-14 rounded-lg object-cover">
                                @else
                                    <span class="text-gray-400">Sin imagen</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $producto->nombre }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $producto->tipo?->nombre ?? 'Sin tipo' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $producto->marca?->nombre ?? 'Sin marca' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                ${{ number_format((float) $producto->precio_dia, 2) }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $producto->existencia }}
                            </td>

                            <td class="px-4 py-4">
                                @if($producto->estado == 1)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Activo
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center gap-2">

                                    <a href="{{ route('productos.show', $producto->id) }}"
                                       class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                        Ver
                                    </a>

                                    <a href="{{ route('productos.edit', $producto->id) }}"
                                       class="font-medium text-yellow-600 hover:underline dark:text-yellow-400">
                                        Editar
                                    </a>

                                    @if($producto->estado == 1)
                                        <form action="{{ route('productos.destroy', $producto->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Deseas dar de baja este producto?');">

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
                            <td colspan="10"
                                class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron productos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $productos->links() }}
        </div>
    </div>
</div>
@endsection