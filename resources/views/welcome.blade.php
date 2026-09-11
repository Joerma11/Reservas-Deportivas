<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reserva de Canchas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen py-10">

    <div class="container mx-auto px-4">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Complejo Deportivo</h1>
            <p class="text-gray-600 mt-2">Reserva tu cancha en línea de forma rápida y sencilla</p>
        </header>

        <main class="space-y-12 pb-16">
            @livewire('booking-calendar')
            
        </main>
    </div>
    @livewireScripts
</body>
</html>