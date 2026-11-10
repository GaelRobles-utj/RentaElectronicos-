<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'RentaElectrónicos | Administración')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    @include('partials.sidebar')

    <div class="min-h-screen transition-all sm:ml-72">

        @include('partials.navbar')

        <main class="px-4 py-6 sm:px-6 lg:px-8">
            @include('partials.api-context-widget')

            @yield('content')
        </main>

        <footer class="border-t border-slate-200 px-6 py-5">
            <p class="text-center text-sm text-slate-500">
                © {{ date('Y') }} RentaElectrónicos.
                Sistema de administración.
            </p>
        </footer>

    </div>

    

</body>
</html>