
@extends('layouts.app')

@section('title', 'Editar producto | RentaElectrónicos')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Editar producto
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Modifica la información del producto #{{ $producto->id }}.
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

        <form action="{{ route('productos.update', $producto->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf
            @method('PUT')

            <div>
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Clasificación del producto
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    <div>
                        <label for="tipo_id"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Tipo *
                        </label>

                        <select id="tipo_id" name="tipo_id" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecciona un tipo</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ (string) old('tipo_id', $producto->tipo_id) === (string) $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('tipo_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="marca_id"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Marca *
                        </label>

                        <select id="marca_id" name="marca_id" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecciona una marca</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}"
                                    {{ (string) old('marca_id', $producto->marca_id) === (string) $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('marca_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="categoria_id"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Categoría *
                        </label>

                        <select id="categoria_id" name="categoria_id" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ (string) old('categoria_id', $producto->categoria_id) === (string) $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('categoria_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Información general
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div class="md:col-span-2">
                        <label for="nombre"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Nombre *
                        </label>

                        <input type="text" id="nombre" name="nombre"
                               value="{{ old('nombre', $producto->nombre) }}"
                               maxlength="150" required
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="descripcion"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Descripción *
                        </label>

                        <textarea id="descripcion" name="descripcion"
                                  rows="4" maxlength="500" required
                                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('descripcion', $producto->descripcion) }}</textarea>

                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="precio_dia"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Precio por día *
                        </label>

                        <input type="number" id="precio_dia" name="precio_dia"
                               value="{{ old('precio_dia', $producto->precio_dia) }}"
                               min="0" step="0.01" required
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                        @error('precio_dia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="existencia"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Existencia *
                        </label>

                        <input type="number" id="existencia" name="existencia"
                               value="{{ old('existencia', $producto->existencia) }}"
                               min="0" step="1" required
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                        @error('existencia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="descuento"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Descuento *
                        </label>

                        <input type="number" id="descuento" name="descuento"
                               value="{{ old('descuento', $producto->descuento) }}"
                               min="0" step="0.01" required
                               class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                        @error('descuento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado"
                               class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Estado *
                        </label>

                        <select id="estado" name="estado" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                            <option value="1"
                                {{ (string) old('estado', (string) $producto->estado) === '1' ? 'selected' : '' }}>
                                Activo
                            </option>

                            <option value="0"
                                {{ (string) old('estado', (string) $producto->estado) === '0' ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>

                        @error('estado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                <h2 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                    Imágenes del producto
                </h2>

                <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                    Selecciona un archivo únicamente si deseas sustituir esa imagen.
                </p>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    @foreach([1, 2, 3] as $numero)
                        @php
                            $campoImagen = 'imagen' . $numero;
                            $imagenActual = $producto->{$campoImagen};
                        @endphp

                        <div>
                            <label for="{{ $campoImagen }}"
                                   class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                Imagen {{ $numero }}
                            </label>

                            @if($imagenActual)
                                @php
                                    $imagenUrl = filter_var($imagenActual, FILTER_VALIDATE_URL)
                                        ? $imagenActual
                                        : asset('storage/' . ltrim(str_replace('storage/', '', $imagenActual), '/'));
                                @endphp

                                <img src="{{ $imagenUrl }}"
                                     alt="Imagen {{ $numero }} de {{ $producto->nombre }}"
                                     class="mb-3 h-28 w-full rounded-lg border border-gray-200 object-contain dark:border-gray-600">
                            @else
                                <div class="mb-3 flex h-28 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                    Sin imagen
                                </div>
                            @endif

                            <input type="file"
                                   id="{{ $campoImagen }}"
                                   name="{{ $campoImagen }}"
                                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                   class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                JPG, JPEG, PNG o WebP. Máximo 2 MB.
                            </p>

                            @error($campoImagen)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="flex flex-wrap gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">

                <button type="submit"
                        class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                    Guardar cambios
                </button>

                <a href="{{ route('productos.show', $producto->id) }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    Ver producto
                </a>

                <a href="{{ route('productos.listado') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
