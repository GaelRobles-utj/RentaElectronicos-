
@extends('layouts.app')

@section('title', 'Detalles del producto | RentaElectrónicos')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Detalles del producto
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Información del producto #{{ $producto->id }}.
        </p>
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

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <div class="border-b border-gray-200 p-6 dark:border-gray-700">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-start">

                @if($producto->imagen1)
                    @php
                        $imagenPrincipal = filter_var($producto->imagen1, FILTER_VALIDATE_URL)
                            ? $producto->imagen1
                            : asset('storage/' . ltrim(str_replace('storage/', '', $producto->imagen1), '/'));
                    @endphp

                    <img src="{{ $imagenPrincipal }}"
                         alt="{{ $producto->nombre }}"
                         class="h-36 w-36 rounded-xl border border-gray-200 object-contain dark:border-gray-600">
                @else
                    <div class="flex h-36 w-36 items-center justify-center rounded-xl bg-gray-100 text-sm text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        Sin imagen
                    </div>
                @endif

                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $producto->nombre }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Identificador: #{{ $producto->id }}
                    </p>

                    <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format((float) $producto->precio_dia, 2) }}
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            por día
                        </span>
                    </p>

                    <div class="mt-3">
                        @if($producto->estado == 1)
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                Activo
                            </span>
                        @else
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                Inactivo
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="space-y-6 p-6">

            <section>
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Clasificación
                </h3>

                <dl class="divide-y divide-gray-200 dark:divide-gray-700">

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Tipo
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                            {{ $producto->tipo?->nombre ?? 'Sin tipo' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Marca
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                            {{ $producto->marca?->nombre ?? 'Sin marca' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Categoría
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                            {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                        </dd>
                    </div>

                </dl>
            </section>

            <section class="border-t border-gray-200 pt-6 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Información general
                </h3>

                <dl class="divide-y divide-gray-200 dark:divide-gray-700">

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Descripción
                        </dt>
                        <dd class="whitespace-pre-line text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $producto->descripcion }}</dd>
                    </div>

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Precio por día
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                            ${{ number_format((float) $producto->precio_dia, 2) }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Existencia
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                            {{ $producto->existencia }} unidades
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Descuento
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                            {{ $producto->descuento }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 gap-2 py-3 sm:grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Estado
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                            {{ $producto->estado == 1 ? 'Activo' : 'Inactivo' }}
                        </dd>
                    </div>

                </dl>
            </section>

            <section class="border-t border-gray-200 pt-6 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Imágenes del producto
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                    @foreach([1, 2, 3] as $numero)
                        @php
                            $campoImagen = 'imagen' . $numero;
                            $imagenActual = $producto->{$campoImagen};
                        @endphp

                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Imagen {{ $numero }}
                            </p>

                            @if($imagenActual)
                                @php
                                    $imagenUrl = filter_var($imagenActual, FILTER_VALIDATE_URL)
                                        ? $imagenActual
                                        : asset('storage/' . ltrim(str_replace('storage/', '', $imagenActual), '/'));
                                @endphp

                                <img src="{{ $imagenUrl }}"
                                     alt="Imagen {{ $numero }} de {{ $producto->nombre }}"
                                     class="h-48 w-full rounded-lg border border-gray-200 object-contain dark:border-gray-600">
                            @else
                                <div class="flex h-48 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                    Sin imagen
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
            </section>

            <div class="flex flex-wrap gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">

                <a href="{{ route('productos.edit', $producto->id) }}"
                   class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                    Editar producto
                </a>

                @if($producto->estado == 1)
                    <form action="{{ route('productos.destroy', $producto->id) }}"
                          method="POST"
                          onsubmit="return confirm('¿Deseas dar de baja este producto?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300">
                            Dar de baja
                        </button>
                    </form>
                @endif

                <a href="{{ route('productos.listado') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>

        </div>
    </div>
</div>
@endsection