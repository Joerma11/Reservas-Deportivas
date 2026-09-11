<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo - Canchas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-slate-800">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Portal Administrativo</h1>
            <p class="text-xs text-slate-500 mt-1">Ingresa tus credenciales para gestionar el complejo</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 text-xs rounded-lg border border-red-200">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-lg border-slate-300 p-3 border focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Contraseña</label>
                <input type="password" name="password" required class="w-full rounded-lg border-slate-300 p-3 border focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition">
                Iniciar Sesión
            </button>
        </form>
    </div>

</body>
</html>