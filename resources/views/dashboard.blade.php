@extends('layouts.app')

@section('title', 'Panel de control | RentaElectrónicos')
@section('page-title', 'Panel de control')

@section('content')

        @auth('admin')
    <div class="mb-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-bold uppercase tracking-wider text-blue-700">
            BIENVENIDO AL SISTEMA
        </p>

        <h2 class="mt-2 text-2xl font-bold text-slate-900">
            
            {{ auth('admin')->user()->nombres }}
            {{ auth('admin')->user()->apellidos }}
        </h2>

        <p class="mt-2 text-sm text-slate-600">
            Has iniciado sesión correctamente en RentaElectrónicos.
        </p>

    </div>
@endauth

        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
            Administra tu negocio
        </h1>

        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-base">
            Gestiona el catálogo de productos, los clientes y las operaciones
            de renta desde un solo lugar.
        </p>
    </section>

    <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">

        <article class="rounded-2xl border border-slate-200
                        bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center
                            rounded-xl bg-blue-50 text-blue-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>

                <span class="rounded-full bg-blue-50 px-3 py-1
                             text-xs font-semibold text-blue-700">
                    Módulos
                </span>
            </div>

            <h3 class="mt-5 text-2xl font-bold text-slate-900">
                10 módulos
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Secciones definidas para administrar las entidades
                principales del sistema.
            </p>

        </article>

        <article class="rounded-2xl border border-slate-200
                        bg-white p-6 shadow-sm">

            <div class="flex h-12 w-12 items-center justify-center
                        rounded-xl bg-violet-50 text-violet-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 7H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3M13 3h8v8M21 3l-9 9"/>
                </svg>
            </div>

            <h3 class="mt-5 text-2xl font-bold text-slate-900">
                Gestión CRUD
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Espacio para listar, registrar, consultar, editar
                y actualizar registros.
            </p>

        </article>

        <article class="rounded-2xl border border-slate-200
                        bg-white p-6 shadow-sm sm:col-span-2 xl:col-span-1">

            <div class="flex h-12 w-12 items-center justify-center
                        rounded-xl bg-emerald-50 text-emerald-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m9 12 2 2 4-4m5-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>

            <h3 class="mt-5 text-2xl font-bold text-slate-900">
                Interfaz integrada
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Diseño compartido basado en Blade, Tailwind CSS
                y componentes de Flowbite.
            </p>

        </article>

    </section>

    <section class="mt-8 rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm sm:p-8">

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Acceso a los módulos
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Selecciona una sección para continuar trabajando.
                </p>
            </div>

            <span class="text-xs text-slate-400">
                RentaElectrónicos
            </span>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ([
                ['name' => 'Administradores', 'slug' => 'administradores'],
                ['name' => 'Clientes', 'slug' => 'clientes'],
                ['name' => 'Tipos', 'slug' => 'tipos'],
                ['name' => 'Marcas', 'slug' => 'marcas'],
                ['name' => 'Categorías', 'slug' => 'categorias'],
                ['name' => 'Productos', 'slug' => 'productos'],
                ['name' => 'Rentas', 'slug' => 'rentas'],
                ['name' => 'Productos de renta', 'slug' => 'productos-renta'],
                ['name' => 'Pagos', 'slug' => 'pagos'],
                ['name' => 'Devoluciones', 'slug' => 'devoluciones'],
            ] as $module)

                <a href="/{{ $module['slug'] }}/listado"
                   class="group flex items-center justify-between rounded-xl
                          border border-slate-200 p-4 transition
                          hover:border-blue-300 hover:bg-blue-50/50">

                    <span class="text-sm font-semibold text-slate-700
                                 group-hover:text-blue-700">
                        {{ $module['name'] }}
                    </span>

                    <svg class="h-5 w-5 text-slate-400 transition
                                group-hover:translate-x-1 group-hover:text-blue-600"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="1.8" d="M9 5l7 7-7 7"/>
                    </svg>

                </a>

            @endforeach

        </div>

    </section>

@endsection
