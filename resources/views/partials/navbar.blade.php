<header class="sticky top-0 z-30 border-b border-slate-200
               bg-white/95 backdrop-blur">

    <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6">

        <div class="flex items-center gap-3">

            <button
                data-drawer-target="logo-sidebar"
                data-drawer-toggle="logo-sidebar"
                aria-controls="logo-sidebar"
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center
                       rounded-lg text-slate-600 hover:bg-slate-100
                       focus:outline-none focus:ring-2 focus:ring-blue-500
                       sm:hidden">

                <span class="sr-only">Abrir menú</span>

                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider
                          text-slate-400">
                    Sistema administrativo
                </p>

                <h2 class="mt-1 text-lg font-bold text-slate-800 sm:text-xl">
                    @yield('page-title', 'Panel de control')
                </h2>
            </div>

        </div>

        <div class="hidden items-center gap-3 sm:flex">

            <div class="text-right">
                <p class="text-sm font-semibold text-slate-800">
                    Administración
                </p>

                <p class="text-xs text-slate-500">
                    Renta de electrónicos
                </p>
            </div>

            <div class="flex h-10 w-10 items-center justify-center
                        rounded-full bg-blue-100 font-bold text-blue-700">
                RE
            </div>

        </div>

    </div>

</header>
