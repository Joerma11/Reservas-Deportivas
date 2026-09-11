<div class="max-w-xl mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-100 mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Validación de Acceso QR</h2>

    <div class="mb-6">
        <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Escanear o Ingresar Código QR</label>
        <div class="flex gap-2">
            <input 
                type="text" 
                wire:model="qrCodeInput" 
                wire:keydown.enter="searchBooking"
                placeholder="Ej: res_64f1a2b3c4d5e" 
                class="w-full rounded-lg border-gray-300 shadow-sm p-3 border focus:ring-2 focus:ring-blue-500"
                autofocus
            >
            <button 
                wire:click="searchBooking" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-lg transition"
            >
                Buscar
            </button>
        </div>
    </div>

    @if ($message)
        <div class="mb-6 p-4 text-sm rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
            {{ $message }}
        </div>
    @endif

    @if ($bookingFound)
        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 space-y-3">
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-xs uppercase font-bold text-gray-500">Estado</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $bookingFound->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($bookingFound->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ $bookingFound->status }}
                </span>
            </div>
            <div>
                <span class="text-xs text-gray-500 uppercase block">Cancha</span>
                <p class="text-lg font-bold text-gray-800">{{ $bookingFound->field->name }} ({{ $bookingFound->field->sport_type }})</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase block">Fecha</span>
                    <p class="font-semibold text-gray-700">{{ $bookingFound->date }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase block">Horario</span>
                    <p class="font-semibold text-gray-700">{{ $bookingFound->start_time }} - {{ $bookingFound->end_time }}</p>
                </div>
            </div>

            @if($bookingFound->status === 'confirmed')
                <button 
                    wire:click="markAsCompleted" 
                    class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow transition"
                >
                    Confirmar Ingesta / Check-In
                </button>
            @endif
        </div>
    @endif
</div>