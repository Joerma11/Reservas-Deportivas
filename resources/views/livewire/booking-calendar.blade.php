<div class="max-w-5xl mx-auto p-4 md:p-8">
    <!-- Barra de Pasos -->
    <div class="flex items-center justify-center mb-10">
        <div class="flex items-center w-full max-w-2xl justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 -z-10"></div>
            
            <!-- Paso 1 -->
            <button wire:click="setStep(1)" class="flex flex-col items-center gap-2 focus:outline-none">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition {{ $step >= 1 ? 'bg-blue-600 text-white shadow-lg ring-4 ring-blue-100' : 'bg-gray-200 text-gray-500' }}">
                    1
                </div>
                <span class="text-xs font-semibold {{ $step >= 1 ? 'text-blue-600' : 'text-gray-400' }}">Deporte</span>
            </button>

            <!-- Paso 2 -->
            <button wire:click="setStep(2)" @disabled($step < 2) class="flex flex-col items-center gap-2 focus:outline-none">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition {{ $step >= 2 ? 'bg-blue-600 text-white shadow-lg ring-4 ring-blue-100' : 'bg-gray-200 text-gray-500' }}">
                    2
                </div>
                <span class="text-xs font-semibold {{ $step >= 2 ? 'text-blue-600' : 'text-gray-400' }}">Cancha</span>
            </button>

            <!-- Paso 3 -->
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition {{ $step === 3 ? 'bg-blue-600 text-white shadow-lg ring-4 ring-blue-100' : 'bg-gray-200 text-gray-500' }}">
                    3
                </div>
                <span class="text-xs font-semibold {{ $step === 3 ? 'text-blue-600' : 'text-gray-400' }}">Horario</span>
            </div>
        </div>
    </div>

    <!-- PASO 1: DEPORTE -->
    @if($step === 1)
        <div class="animate-fadeIn">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-gray-800">¿Qué deporte vas a jugar hoy?</h2>
                <p class="text-gray-500 text-sm mt-1">Selecciona una disciplina para ver nuestras instalaciones</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($sports as $key => $sport)
                    <div wire:click="selectSport('{{ $key }}')" class="bg-white rounded-2xl border-2 border-gray-100 p-6 text-center cursor-pointer hover:shadow-xl hover:-translate-y-1 hover:border-blue-500 transition-all group">
                        <div class="w-16 h-16 mx-auto rounded-2xl flex items-center justify-center text-3xl mb-4 bg-gray-50 group-hover:scale-110 transition-transform">
                            {{ $sport['icon'] }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $sport['name'] }}</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">{{ $sport['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- PASO 2: CANCHA -->
    @if($step === 2)
        <div class="animate-fadeIn">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-800">Canchas de {{ $selectedSport }}</h2>
                    <p class="text-gray-500 text-sm">Elige la cancha para continuar con tu reserva</p>
                </div>
                <button wire:click="setStep(1)" class="text-xs font-semibold text-blue-600 hover:underline">
                    ← Cambiar Deporte
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($fieldsForSport as $field)
                    <div wire:click="selectField({{ $field->id }})" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-lg hover:border-blue-600 cursor-pointer transition flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-bold text-gray-800">{{ $field->name }}</h3>
                                <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                    {{ $field->slot_duration }} min
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">Excelente iluminación y vestuarios disponibles.</p>
                        </div>
                        <div class="flex justify-between items-center border-t pt-4 mt-2">
                            <div>
                                <span class="text-xs text-gray-400 block uppercase font-bold">Precio</span>
                                <span class="text-xl font-extrabold text-blue-600">${{ number_format($field->price_per_hour, 0) }}</span>
                            </div>
                            <span class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-lg">
                                Seleccionar
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed">
                        <p class="text-gray-500">No hay canchas disponibles para {{ $selectedSport }}.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- PASO 3: CALENDARIO DE DISPONIBILIDAD Y SLOTS -->
    @if($step === 3 && $selectedField)
        <div class="animate-fadeIn space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-gray-800">{{ $selectedField->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $selectedSport }} • Duración: {{ $selectedField->slot_duration }} min • ${{ number_format($selectedField->price_per_hour, 0) }}/bloque</p>
                </div>
                <button wire:click="setStep(2)" class="text-xs font-semibold text-blue-600 hover:underline">
                    ← Elegir otra cancha
                </button>
            </div>

            <!-- Semáforo de disponibilidad -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <button wire:click="prevMonth" class="p-2 border rounded-lg hover:bg-gray-50">&larr;</button>
                        <h3 class="text-lg font-bold text-gray-800 capitalize">
                            {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->isoFormat('MMMM YYYY') }}
                        </h3>
                        <button wire:click="nextMonth" class="p-2 border rounded-lg hover:bg-gray-50">&rarr;</button>
                    </div>

                    <!-- Leyenda de colores -->
                    <div class="flex flex-wrap gap-4 text-xs font-semibold">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500"></span> Disponible</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-yellow-400"></span> Casi lleno</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span> Agotado</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-300"></span> No disponible</span>
                    </div>
                </div>

                <!-- Grilla del Calendario -->
                <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-gray-500 mb-2">
                    <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div>
                </div>

                <div class="grid grid-cols-7 gap-2">
                    @foreach($monthDays as $dayItem)
                        @if(!$dayItem)
                            <div class="h-12"></div>
                        @else
                            @php
                                $colorClasses = match($dayItem['status']) {
                                    'green' => 'bg-green-500 text-white hover:bg-green-600 cursor-pointer',
                                    'yellow' => 'bg-yellow-400 text-slate-900 hover:bg-yellow-500 cursor-pointer',
                                    'red' => 'bg-red-500 text-white opacity-60 cursor-not-allowed',
                                    default => 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                };

                                $isSelected = $selectedDate === $dayItem['date'];
                            @endphp

                            <button 
                                @if($dayItem['status'] === 'gray' || $dayItem['status'] === 'red') disabled @endif
                                wire:click="selectDate('{{ $dayItem['date'] }}')" 
                                class="h-12 rounded-xl flex flex-col items-center justify-center font-bold text-sm transition relative {{ $colorClasses }} {{ $isSelected ? 'ring-4 ring-blue-600 ring-offset-2' : '' }}"
                            >
                                {{ $dayItem['day'] }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Bloques de Horarios del día seleccionado -->
            @if($selectedDate)
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-md font-bold text-gray-800 mb-4">
                        Turnos disponibles para el {{ \Carbon\Carbon::parse($selectedDate)->isoFormat('LL') }}
                    </h3>

                    @if(count($availableSlots) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach($availableSlots as $slot)
                                <button 
                                    @disabled(!$slot['is_available'])
                                    class="py-3 px-2 rounded-xl border text-center font-bold text-sm transition {{ $slot['is_available'] ? 'bg-green-50 border-green-200 text-green-800 hover:bg-blue-600 hover:text-white hover:border-blue-600 shadow-sm' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed line-through' }}"
                                >
                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                </button>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm text-center py-6">No hay turnos disponibles para esta fecha.</p>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>