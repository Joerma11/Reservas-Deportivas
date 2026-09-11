<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Field;
use App\Models\BlockedSchedule;

class AdminFields extends Component
{
    // Campos para crear/editar cancha
    public $fieldId;
    public $name;
    public $sport_type;
    public $price_per_hour;
    public $slot_duration = 60;
    public $is_active = true;

    // Campos para bloquear horarios
    public $blockFieldId;
    public $blockDate;
    public $blockStartTime;
    public $blockEndTime;
    public $blockReason;

    public $successMessage = '';

    public function saveField()
    {
        $this->validate([
            'name'           => 'required|string|max:255',
            'sport_type'     => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'slot_duration'  => 'required|integer|min:15',
        ]);

        Field::updateOrCreate(
            ['id' => $this->fieldId],
            [
                'name'           => $this->name,
                'sport_type'     => $this->sport_type,
                'price_per_hour' => $this->price_per_hour,
                'slot_duration'  => $this->slot_duration,
                'is_active'      => $this->is_active,
            ]
        );

        $this->successMessage = $this->fieldId ? 'Cancha actualizada con éxito.' : 'Cancha creada con éxito.';
        $this->reset(['fieldId', 'name', 'sport_type', 'price_per_hour', 'slot_duration', 'is_active']);
    }

    public function editField($id)
    {
        $field = Field::findOrFail($id);
        $this->fieldId = $field->id;
        $this->name = $field->name;
        $this->sport_type = $field->sport_type;
        $this->price_per_hour = $field->price_per_hour;
        $this->slot_duration = $field->slot_duration;
        $this->is_active = $field->is_active;
    }

    public function toggleFieldStatus($id)
    {
        $field = Field::findOrFail($id);
        $field->update(['is_active' => !$field->is_active]);
    }

    public function createBlock()
    {
        $this->validate([
            'blockDate'      => 'required|date',
            'blockStartTime' => 'required|date_format:H:i',
            'blockEndTime'   => 'required|date_format:H:i|after:blockStartTime',
        ]);

        BlockedSchedule::create([
            'field_id'   => $this->blockFieldId ?: null, // null = bloqueo global
            'date'       => $this->blockDate,
            'start_time' => $this->blockStartTime,
            'end_time'   => $this->blockEndTime,
            'reason'     => $this->blockReason,
        ]);

        $this->successMessage = 'Bloqueo registrado correctamente.';
        $this->reset(['blockFieldId', 'blockDate', 'blockStartTime', 'blockEndTime', 'blockReason']);
    }

    public function render()
    {
        return view('livewire.admin-fields', [
            'fields' => Field::all(),
            'blocks' => BlockedSchedule::with('field')->orderBy('date', 'desc')->take(10)->get()
        ]);
    }
}