<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">Administración de Canchas</h2>
            <p class="text-gray-500 text-sm">Organizadas por tipo de deporte</p>
        </div>
        <button wire:click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Agregar Nueva Cancha
        </button>
    </div>

    <!-- Malla / Recuadros Agrupados por Deporte -->
    @foreach($groupedFields as $sport => $fields)
        <div class="mb-10">
            <h3 class="text-xl font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                <span class="w-3 h-3 bg-blue-600 rounded-full"></span> {{ strtoupper($sport) }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($fields as $field)
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col justify-between relative overflow-hidden">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">{{ $field->name }}</h4>
                                <span class="text-xs text-gray-500">Duración turno: {{ $field->slot_duration }} min</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $field->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $field->is_active ? 'Activa' : 'Deshabilitada' }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <span class="text-2xl font-black text-blue-600">${{ number_format($field->price_per_hour, 0) }}</span>
                            <span class="text-xs text-gray-400">/ bloque</span>
                        </div>

                        <div class="flex gap-2 border-t pt-4">
                            <button wire:click="openEditModal({{ $field->id }})" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                                Editar
                            </button>
                            @if($field->is_active)
                                <button wire:click="disableField({{ $field->id }})" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-3 py-2 rounded-lg text-sm border border-red-200 transition">
                                    Deshabilitar
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Vista Flotante / MODAL de Edición y Creación -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ $isEditing ? 'Editar Cancha & Horarios' : 'Crear Nueva Cancha' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                </div>

                <form wire:submit.prevent="saveField" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nombre Cancha</label>
                            <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 p-2.5 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Deporte</label>
                            <input type="text" wire:model="sport_type" placeholder="Ej: Tenis, Pádel, Fútbol" class="w-full rounded-lg border-gray-300 p-2.5 border shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Precio por Bloque ($)</label>
                            <input type="number" wire:model="price_per_hour" class="w-full rounded-lg border-gray-300 p-2.5 border shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Duración (Minutos)</label>
                            <input type="number" wire:model="slot_duration" class="w-full rounded-lg border-gray-300 p-2.5 border shadow-sm">
                        </div>
                    </div>

                    <!-- Configuración de Días y Horarios -->
                    <div class="border-t pt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Días de Disponibilidad y Horarios</label>
                        @php $daysName = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']; @endphp
                        
                        <div class="space-y-2">
                            @foreach(range(0, 6) as $day)
                                <div class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border text-sm">
                                    <input type="checkbox" wire:model="schedules.{{ $day }}.active" class="rounded text-blue-600">
                                    <span class="w-24 font-medium text-gray-700">{{ $daysName[$day] }}</span>
                                    
                                    @if($schedules[$day]['active'] ?? false)
                                        <input type="time" wire:model="schedules.{{ $day }}.start_time" class="p-1 border rounded text-xs">
                                        <span class="text-gray-400">a</span>
                                        <input type="time" wire:model="schedules.{{ $day }}.end_time" class="p-1 border rounded text-xs">
                                    @else
                                        <span class="text-xs text-gray-400 italic">No disponible / Cerrado</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-4">
                        <button type="button" wire:click="closeModal" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow transition">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>