<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | RentaElectrónicos</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            background: #f3f4f6;
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 32px;
            border-radius: 14px;
            box-shadow: 0 10px 30px #00000012;
        }

        h1 { margin: 0 0 8px; }
        .subtitle { color: #6b7280; margin-bottom: 24px; }

        label {
            display: block;
            margin: 16px 0 7px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 22px;
            border: 0;
            border-radius: 8px;
            background: #1d4ed8;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .errors {
            padding: 12px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
            margin-bottom: 16px;
        }

        .brand {
            color: #1d4ed8;
            font-weight: bold;
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <main class="card">
        <div class="brand">RE · RentaElectrónicos</div>

        <h1>Iniciar sesión</h1>
        <p class="subtitle">Acceso al panel administrativo</p>

        @if ($errors->any())
            <div class="errors" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <label for="usuario">Usuario</label>
            <input
                type="text"
                id="usuario"
                name="usuario"
                value="{{ old('usuario') }}"
                autocomplete="username"
                required
                autofocus
            >

            <label for="contrasena">Contraseña</label>
            <input
                type="password"
                id="contrasena"
                name="contrasena"
                autocomplete="current-password"
                required
            >

            <button type="submit">Ingresar al sistema</button>
        </form>
    </main>
</body>
</html>
