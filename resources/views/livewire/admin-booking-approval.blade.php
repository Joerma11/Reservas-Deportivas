<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Aprobación de Solicitudes</h2>
        <p class="text-gray-500 text-sm">Gestione las solicitudes de arriendo entrantes</p>
    </div>

    <!-- Solicitudes Pendientes -->
    <div class="bg-white rounded-xl shadow-md border p-6">
        <h3 class="text-lg font-bold text-amber-600 mb-4 flex items-center gap-2">
            <span class="w-3 h-3 bg-amber-500 rounded-full animate-pulse"></span> Solicitudes Pendientes de Confirmación
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-xs border-b">
                    <tr>
                        <th class="p-3">Cliente</th>
                        <th class="p-3">Cancha</th>
                        <th class="p-3">Fecha</th>
                        <th class="p-3">Horario</th>
                        <th class="p-3">Monto</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pendingBookings as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-semibold text-gray-800">{{ $b->user->name ?? 'Cliente Demo' }}</td>
                            <td class="p-3">{{ $b->field->name }}</td>
                            <td class="p-3">{{ $b->date }}</td>
                            <td class="p-3">{{ $b->start_time }} - {{ $b->end_time }}</td>
                            <td class="p-3 font-bold text-green-600">${{ number_format($b->total_price, 0) }}</td>
                            <td class="p-3 flex justify-center gap-2">
                                <button wire:click="approveBooking({{ $b->id }})" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1.5 rounded-md text-xs transition">
                                    Aceptar
                                </button>
                                <button wire:click="rejectBooking({{ $b->id }})" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-1.5 rounded-md text-xs transition">
                                    Rechazar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-400 italic">No hay solicitudes pendientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Integración del Scanner QR en la misma sección -->
    @livewire('qr-scanner')
</div>