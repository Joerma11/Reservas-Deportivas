<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva tu Cancha en Línea</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 min-h-screen py-10">
    <div class="container mx-auto px-4">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Complejo Deportivo</h1>
            <p class="text-slate-600 mt-2">Selecciona tu deporte, horario y confirma tu reserva al instante</p>
        </header>

        @livewire('booking-calendar')
    </div>
    @livewireScripts
</body>
</html>