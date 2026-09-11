<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-100 mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b">Panel de Administración de Canchas</h2>

    @if ($successMessage)
        <div class="mb-6 p-4 text-sm text-green-800 bg-green-100 rounded-lg">
            {{ $successMessage }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Formulario Gestión de Cancha -->
        <div class="bg-gray-50 p-5 rounded-lg border">
            <h3 class="text-lg font-bold text-gray-700 mb-4">{{ $fieldId ? 'Editar Cancha' : 'Nueva Cancha' }}</h3>
            <form wire:submit.prevent="saveField" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Nombre</label>
                    <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Deporte</label>
                    <input type="text" wire:model="sport_type" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Precio por Turno ($)</label>
                        <input type="number" wire:model="price_per_hour" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Duración (Minutos)</label>
                        <input type="number" wire:model="slot_duration" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active">
                    <label for="is_active" class="text-sm text-gray-700 font-medium">Cancha Activa</label>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-md transition">
                    {{ $fieldId ? 'Actualizar Cancha' : 'Guardar Cancha' }}
                </button>
            </form>
        </div>

        <!-- Formulario de Bloqueo por Mantenimiento -->
        <div class="bg-gray-50 p-5 rounded-lg border">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Bloquear Horario / Mantenimiento</h3>
            <form wire:submit.prevent="createBlock" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Cancha (Opcional)</label>
                    <select wire:model="blockFieldId" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                        <option value="">-- Bloqueo Global (Todas) --</option>
                        @foreach($fields as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Fecha</label>
                    <input type="date" wire:model="blockDate" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Hora Inicio</label>
                        <input type="time" wire:model="blockStartTime" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Hora Fin</label>
                        <input type="time" wire:model="blockEndTime" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Motivo</label>
                    <input type="text" wire:model="blockReason" placeholder="Ej: Mantenimiento de césped" class="w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-md transition">
                    Registrar Bloqueo
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Canchas Existentes -->
    <div class="mt-8">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Canchas Registradas</h3>
        <table class="w-full text-left text-sm text-gray-600 border">
            <thead class="bg-gray-100 uppercase text-xs">
                <tr>
                    <th class="p-3 border">Nombre</th>
                    <th class="p-3 border">Deporte</th>
                    <th class="p-3 border">Duración</th>
                    <th class="p-3 border">Precio</th>
                    <th class="p-3 border">Estado</th>
                    <th class="p-3 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fields as $f)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3 font-semibold">{{ $f->name }}</td>
                        <td class="p-3">{{ $f->sport_type }}</td>
                        <td class="p-3">{{ $f->slot_duration }} min</td>
                        <td class="p-3">${{ number_format($f->price_per_hour, 0) }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $f->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $f->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="p-3 flex gap-2">
                            <button wire:click="editField({{ $f->id }})" class="bg-amber-500 text-white px-2.5 py-1 rounded text-xs font-semibold">Editar</button>
                            <button wire:click="toggleFieldStatus({{ $f->id }})" class="bg-gray-700 text-white px-2.5 py-1 rounded text-xs font-semibold">
                                {{ $f->is_active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>