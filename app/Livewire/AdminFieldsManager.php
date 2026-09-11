<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Field;
use App\Models\Schedule;

class AdminFieldsManager extends Component
{
    // Estado del Modal Flotante
    public $showModal = false;
    public $isEditing = false;

    // Propiedades del Formulario
    public $fieldId;
    public $name;
    public $sport_type;
    public $price_per_hour;
    public $slot_duration = 60;
    public $is_active = true;

    // Días de disponibilidad (0 = Domingo, 1 = Lunes, ..., 6 = Sábado)
    public $schedules = [];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $field = Field::findOrFail($id);
        $this->fieldId = $field->id;
        $this->name = $field->name;
        $this->sport_type = $field->sport_type;
        $this->price_per_hour = $field->price_per_hour;
        $this->slot_duration = $field->slot_duration;
        $this->is_active = $field->is_active;

        // Cargar horarios de atención
        $existingSchedules = Schedule::where('field_id', $field->id)->get()->keyBy('day_of_week');
        $this->schedules = [];

        foreach (range(0, 6) as $day) {
            $this->schedules[$day] = [
                'active'     => isset($existingSchedules[$day]),
                'start_time' => $existingSchedules[$day]->start_time ?? '09:00',
                'end_time'   => $existingSchedules[$day]->end_time ?? '22:00',
            ];
        }

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveField()
    {
        $this->validate([
            'name'           => 'required|string|max:255',
            'sport_type'     => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'slot_duration'  => 'required|integer|min:15',
        ]);

        $field = Field::updateOrCreate(
            ['id' => $this->fieldId],
            [
                'name'           => $this->name,
                'sport_type'     => $this->sport_type,
                'price_per_hour' => $this->price_per_hour,
                'slot_duration'  => $this->slot_duration,
                'is_active'      => $this->is_active,
            ]
        );

        // Guardar/Actualizar configuración de días y horarios
        foreach ($this->schedules as $day => $data) {
            if ($data['active']) {
                Schedule::updateOrCreate(
                    ['field_id' => $field->id, 'day_of_week' => $day],
                    ['start_time' => $data['start_time'], 'end_time' => $data['end_time']]
                );
            } else {
                Schedule::where('field_id', $field->id)->where('day_of_week', $day)->delete();
            }
        }

        $this->closeModal();
    }

    public function disableField($id)
    {
        // En lugar de borrar registros y perder historial, se desactiva en la BD
        $field = Field::findOrFail($id);
        $field->update(['is_active' => false]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['fieldId', 'name', 'sport_type', 'price_per_hour', 'slot_duration', 'is_active', 'schedules']);
        foreach (range(0, 6) as $day) {
            $this->schedules[$day] = ['active' => true, 'start_time' => '09:00', 'end_time' => '22:00'];
        }
    }

    public function render()
    {
        // Agrupar canchas activas e inactivas ordenadas por Deporte
        $groupedFields = Field::all()->groupBy('sport_type');

        return view('livewire.admin-fields-manager', compact('groupedFields'));
    }
}