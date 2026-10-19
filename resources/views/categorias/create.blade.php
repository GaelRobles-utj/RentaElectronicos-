
@extends('layouts.app')

@section('title', 'Registrar categoría | RentaElectrónicos')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Registrar categoría
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Completa los datos para agregar una nueva categoría.
        </p>
    </div>

    @if($errors->any())
        <div class="rounded-lg bg-red-100 p-4 text-sm text-red-800 dark:bg-red-900 dark:text-red-200">
            <p class="mb-2 font-semibold">Revisa los siguientes campos:</p>

            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <form action="{{ route('categorias.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf

            <div>
                <label for="nombre"
                       class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Nombre de la categoría *
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    maxlength="100"
                    required
                    autofocus
                    placeholder="Ej. Laptops, Celulares o Accesorios"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="imagen"
                       class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Imagen de la categoría
                </label>

                <input
                    type="file"
                    id="imagen"
                    name="imagen"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Formatos permitidos: JPG, JPEG, PNG y WebP. Máximo 2 MB.
                </p>

                @error('imagen')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estado"
                       class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Estado *
                </label>

                <select
                    id="estado"
                    name="estado"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                    <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>
                        Activa
                    </option>

                    <option value="0" {{ old('estado') === '0' ? 'selected' : '' }}>
                        Inactiva
                    </option>
                </select>

                @error('estado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">

                <button
                    type="submit"
                    class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                    Guardar categoría
                </button>

                <a
                    href="{{ route('categorias.listado') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</div>
@endsection