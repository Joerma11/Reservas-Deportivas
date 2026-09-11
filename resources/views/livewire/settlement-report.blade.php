<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-100 mt-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 pb-4 border-b">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Panel de Liquidaciones y Reportes</h2>
            <p class="text-sm text-gray-500">Genera reportes de recaudación y expórtalos a Excel</p>
        </div>
        <button 
            wire:click="exportExcel" 
            class="mt-4 md:mt-0 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow transition flex items-center justify-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Exportar a Excel
        </button>
    </div>

    <!-- Filtro de Fechas y Métricas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Fecha Inicio</label>
            <input type="date" wire:model.live="startDate" class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Fecha Fin</label>
            <input type="date" wire:model.live="endDate" class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border">
        </div>
        <div class="bg-blue-50 p-3.5 rounded-lg border border-blue-100 flex flex-col justify-center">
            <span class="text-xs font-semibold text-blue-600 uppercase">Total Reservas</span>
            <span class="text-2xl font-black text-blue-900">{{ $totalBookings }}</span>
        </div>
        <div class="bg-green-50 p-3.5 rounded-lg border border-green-100 flex flex-col justify-center">
            <span class="text-xs font-semibold text-green-600 uppercase">Recaudación Total</span>
            <span class="text-2xl font-black text-green-900">${{ number_format($totalRevenue, 0) }}</span>
        </div>
    </div>

    <!-- Tabla Resumen -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Cancha</th>
                    <th class="p-3">Fecha</th>
                    <th class="p-3">Horario</th>
                    <th class="p-3">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($recentBookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-medium">#{{ $booking->id }}</td>
                        <td class="p-3">{{ $booking->field->name }}</td>
                        <td class="p-3">{{ $booking->date }}</td>
                        <td class="p-3">{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                        <td class="p-3 font-semibold text-gray-900">${{ number_format($booking->total_price, 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-400 italic">No hay reservas en este rango de fechas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>