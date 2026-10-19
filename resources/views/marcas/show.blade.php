
@extends('layouts.app')

@section('title', 'Detalles de marca | RentaElectrónicos')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Detalles de la marca
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Información de la marca registrada en el sistema.
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
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                @if($marca->imagen)
                    @php
                        $imagenUrl = filter_var($marca->imagen, FILTER_VALIDATE_URL)
                            ? $marca->imagen
                            : asset('storage/' . ltrim(str_replace('storage/', '', $marca->imagen), '/'));
                    @endphp

                    <img src="{{ $imagenUrl }}"
                         alt="Imagen de {{ $marca->nombre }}"
                         class="h-24 w-24 rounded-xl border border-gray-200 object-cover dark:border-gray-600">
                @else
                    <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-gray-100 text-xs text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        Sin imagen
                    </div>
                @endif

                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $marca->nombre }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Identificador: #{{ $marca->id }}
                    </p>

                    <div class="mt-2">
                        @if($marca->estado == 1)
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                Activa
                            </span>
                        @else
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                Inactiva
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                Información general
            </h3>

            <dl class="divide-y divide-gray-200 dark:divide-gray-700">

                <div class="grid grid-cols-1 gap-2 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        ID de marca
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                        {{ $marca->id }}
                    </dd>
                </div>

                <div class="grid grid-cols-1 gap-2 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Nombre
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                        {{ $marca->nombre }}
                    </dd>
                </div>

                <div class="grid grid-cols-1 gap-2 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Estado
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-white sm:col-span-2">
                        {{ $marca->estado == 1 ? 'Activa' : 'Inactiva' }}
                    </dd>
                </div>

            </dl>

            <div class="mt-6 flex flex-wrap gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">

                <a href="{{ route('marcas.edit', $marca->id) }}"
                   class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                    Editar marca
                </a>

                @if($marca->estado == 1)
                    <form action="{{ route('marcas.destroy', $marca->id) }}"
                          method="POST"
                          onsubmit="return confirm('¿Deseas dar de baja esta marca?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300">
                            Dar de baja
                        </button>
                    </form>
                @endif

                <a href="{{ route('marcas.listado') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    Volver al listado
                </a>

            </div>
        </div>
    </div>
</div>
@endsection