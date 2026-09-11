<x-admin-layout>
    <div class="space-y-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">Panel de Control</h2>
            <p class="text-gray-500 text-sm">Bienvenido al panel de administración del complejo deportivo.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.fields') }}" class="p-6 bg-white rounded-xl shadow-md border hover:border-blue-500 transition block">
                <h3 class="font-bold text-gray-800 text-lg mb-2">Gestión de Canchas</h3>
                <p class="text-sm text-gray-500">Configura precios, deportes y horarios por día.</p>
            </a>

            <a href="{{ route('admin.bookings') }}" class="p-6 bg-white rounded-xl shadow-md border hover:border-blue-500 transition block">
                <h3 class="font-bold text-gray-800 text-lg mb-2">Aprobación de Reservas</h3>
                <p class="text-sm text-gray-500">Acepta solicitudes pendientes y valida códigos QR.</p>
            </a>

            <a href="{{ route('admin.reports') }}" class="p-6 bg-white rounded-xl shadow-md border hover:border-blue-500 transition block">
                <h3 class="font-bold text-gray-800 text-lg mb-2">Reportes Excel</h3>
                <p class="text-sm text-gray-500">Exporta las liquidaciones financieras y métricas.</p>
            </a>
        </div>
    </div>
</x-admin-layout>