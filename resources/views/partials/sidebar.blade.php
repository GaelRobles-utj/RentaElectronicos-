<aside
    id="logo-sidebar"
    class="fixed left-0 top-0 z-40 h-screen w-72
           -translate-x-full border-r border-slate-800
           bg-slate-950 transition-transform sm:translate-x-0"
    aria-label="Menú principal">

    <div class="flex h-20 items-center gap-3 border-b border-slate-800 px-6">

        <div class="flex h-11 w-11 items-center justify-center rounded-xl
                    bg-blue-600 text-lg font-bold text-white shadow-lg">
            RE
        </div>

        <div>
            <h1 class="text-base font-bold tracking-wide text-white">
                RentaElectrónicos
            </h1>

            <p class="text-xs text-slate-400">
                Panel administrativo
            </p>
        </div>

    </div>

    <div class="h-[calc(100vh-5rem)] overflow-y-auto px-4 py-5">

        <p class="mb-3 px-3 text-xs font-semibold uppercase
                  tracking-widest text-slate-500">
            General
        </p>

        <a href="/"
           class="mb-6 flex items-center gap-3 rounded-xl bg-blue-600
                  px-4 py-3 text-sm font-medium text-white
                  shadow-md shadow-blue-950/20">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg
                         bg-white/15">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m3 10 9-7 9 7M5 9v12h14V9M9 21v-7h6v7"/>
                </svg>
            </span>

            Panel de control
        </a>

        <p class="mb-3 px-3 text-xs font-semibold uppercase
                  tracking-widest text-slate-500">
            Gestión de módulos
        </p>

        @php
            $modules = [
                ['name' => 'Administradores', 'slug' => 'administradores', 'abbr' => 'AD'],
                ['name' => 'Clientes', 'slug' => 'clientes', 'abbr' => 'CL'],
                ['name' => 'Tipos', 'slug' => 'tipos', 'abbr' => 'TI'],
                ['name' => 'Marcas', 'slug' => 'marcas', 'abbr' => 'MA'],
                ['name' => 'Categorías', 'slug' => 'categorias', 'abbr' => 'CA'],
                ['name' => 'Productos', 'slug' => 'productos', 'abbr' => 'PR'],
                ['name' => 'Rentas', 'slug' => 'rentas', 'abbr' => 'RE'],
                ['name' => 'Productos de renta', 'slug' => 'productos-renta', 'abbr' => 'DR'],
                ['name' => 'Pagos', 'slug' => 'pagos', 'abbr' => 'PA'],
                ['name' => 'Devoluciones', 'slug' => 'devoluciones', 'abbr' => 'DE'],
            ];
        @endphp

        <nav class="space-y-1">

            @foreach ($modules as $module)
                <div class="flex items-center gap-1">

                    <a href="/{{ $module['slug'] }}/listado"
                       class="group flex min-w-0 flex-1 items-center gap-3
                              rounded-lg px-3 py-2.5 text-sm text-slate-300
                              transition hover:bg-slate-800 hover:text-white">

                        <span class="flex h-8 w-8 shrink-0 items-center
                                     justify-center rounded-lg bg-slate-800
                                     text-xs font-bold text-slate-300
                                     group-hover:bg-blue-500/20
                                     group-hover:text-blue-300">
                            {{ $module['abbr'] }}
                        </span>

                        <span class="truncate">
                            {{ $module['name'] }}
                        </span>

                    </a>

                    
<a href="{{ $module['slug'] === 'administradores'
            ? route('administradores.create')
            : '/' . $module['slug'] . '/formulario' }}"
   title="Nuevo registro: {{ $module['name'] }}"
   aria-label="Nuevo registro: {{ $module['name'] }}"
   class="flex h-8 w-8 shrink-0 items-center justify-center
          rounded-lg text-lg text-slate-400
          transition hover:bg-blue-600 hover:text-white">
    +
</a>

                </div>
            @endforeach

        </nav>

        <div class="mt-8 rounded-xl border border-slate-800
                    bg-slate-900 p-4">

            <p class="text-sm font-semibold text-white">
                Sistema de renta
            </p>

            <p class="mt-1 text-xs leading-5 text-slate-400">
                Administración de productos electrónicos,
                clientes y operaciones de renta.
            </p>

        </div>

    </div>

</aside>
