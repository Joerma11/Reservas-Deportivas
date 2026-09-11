<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Field;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\BlockedSchedule;
use Carbon\Carbon;

class BookingCalendar extends Component
{
    public $step = 1;

    public $selectedSport = null;
    public $selectedFieldId = null;
    public $selectedField = null;
    public $selectedDate = null;
    public $selectedTimeSlot = null;

    // Navegación del mes en el paso 3
    public $currentMonth;
    public $currentYear;

    public $sports = [
        'Tenis' => [
            'name' => 'Tenis',
            'icon' => '🎾',
            'description' => 'Canchas de arcilla y superficie rápida'
        ],
        'Pádel' => [
            'name' => 'Pádel',
            'icon' => '🎾',
            'description' => 'Canchas vidriadas de última generación'
        ],
        'Fútbol' => [
            'name' => 'Fútbol',
            'icon' => '⚽',
            'description' => 'Césped sintético de alto tráfico'
        ],
        'Básquet' => [
            'name' => 'Básquet',
            'icon' => '🏀',
            'description' => 'Piso flotante e iluminación LED'
        ]
    ];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function selectSport($sportKey)
    {
        $this->selectedSport = $sportKey;
        $this->selectedFieldId = null;
        $this->selectedField = null;
        $this->step = 2;
    }

    public function selectField($fieldId)
    {
        $this->selectedFieldId = $fieldId;
        $this->selectedField = Field::find($fieldId);
        $this->step = 3;
    }

    public function selectDate($dateStr)
    {
        $this->selectedDate = $dateStr;
        $this->selectedTimeSlot = null;
    }

    public function setStep($stepNumber)
    {
        if ($stepNumber < $this->step) {
            $this->step = $stepNumber;
        }
    }

    public function prevMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function render()
    {
        $fieldsForSport = collect();
        $availableSlots = [];
        $monthDays = [];

        if ($this->selectedSport) {
            $fieldsForSport = Field::where('sport_type', 'LIKE', '%' . $this->selectedSport . '%')
                ->where('is_active', true)
                ->get();
        }

        if ($this->step === 3 && $this->selectedField) {
            $monthDays = $this->generateCalendarMonth();
            if ($this->selectedDate) {
                $availableSlots = $this->generateSlotsForDate();
            }
        }

        return view('livewire.booking-calendar', [
            'fieldsForSport' => $fieldsForSport,
            'availableSlots' => $availableSlots,
            'monthDays' => $monthDays
        ]);
    }

    private function generateCalendarMonth()
    {
        $startOfMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $daysInMonth = $startOfMonth->daysInMonth;
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0: Dom, 1: Lun...

        $calendarDays = [];

        // Relleno de días en blanco al inicio del mes
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendarDays[] = null;
        }

        // Generar estado de cada día
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $day);
            $dateStr = $date->format('Y-m-d');
            
            $status = $this->calculateDayStatus($dateStr, $date->dayOfWeek);

            $calendarDays[] = [
                'day' => $day,
                'date' => $dateStr,
                'status' => $status, // 'gray', 'green', 'yellow', 'red'
                'is_past' => $date->isPast() && !$date->isToday()
            ];
        }

        return $calendarDays;
    }

    private function calculateDayStatus($dateStr, $dayOfWeek)
    {
        // 1. Si es fecha pasada -> Gris
        if (Carbon::parse($dateStr)->isPast() && !Carbon::parse($dateStr)->isToday()) {
            return 'gray';
        }

        // 2. Si la cancha no opera este día o hay bloqueo global -> Gris
        $schedule = Schedule::where('field_id', $this->selectedField->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        $isBlocked = BlockedSchedule::where('date', $dateStr)
            ->where(function ($q) {
                $q->whereNull('field_id')->orWhere('field_id', $this->selectedField->id);
            })->exists();

        if (!$schedule || $isBlocked) {
            return 'gray';
        }

        // 3. Contar cupos totales y ocupados
        $duration = $this->selectedField->slot_duration ?? 60;
        $start = Carbon::parse($dateStr . ' ' . $schedule->start_time);
        $end = Carbon::parse($dateStr . ' ' . $schedule->end_time);

        $totalSlots = 0;
        while ($start->copy()->addMinutes($duration)->lte($end)) {
            $totalSlots++;
            $start->addMinutes($duration);
        }

        if ($totalSlots === 0) return 'gray';

        $bookedSlots = Booking::where('field_id', $this->selectedField->id)
            ->where('date', $dateStr)
            ->whereIn('status', ['confirmed', 'pending', 'completed'])
            ->count();

        $availableCount = $totalSlots - $bookedSlots;

        // Semáforo visual
        if ($availableCount <= 0) {
            return 'red'; // Sin disponibilidad
        } elseif ($availableCount <= ($totalSlots * 0.3)) {
            return 'yellow'; // Pocos turnos restantes
        }

        return 'green'; // Alta disponibilidad
    }

    private function generateSlotsForDate()
    {
        $dateObj = Carbon::parse($this->selectedDate);
        $schedule = Schedule::where('field_id', $this->selectedField->id)
            ->where('day_of_week', $dateObj->dayOfWeek)
            ->first();

        if (!$schedule) return [];

        $isBlocked = BlockedSchedule::where('date', $this->selectedDate)
            ->where(function ($q) {
                $q->whereNull('field_id')->orWhere('field_id', $this->selectedField->id);
            })->exists();

        if ($isBlocked) return [];

        $start = Carbon::parse($this->selectedDate . ' ' . $schedule->start_time);
        $end = Carbon::parse($this->selectedDate . ' ' . $schedule->end_time);
        $duration = $this->selectedField->slot_duration ?? 60;

        $existingBookings = Booking::where('field_id', $this->selectedField->id)
            ->where('date', $this->selectedDate)
            ->whereIn('status', ['confirmed', 'pending', 'completed'])
            ->get();

        $slots = [];

        while ($start->copy()->addMinutes($duration)->lte($end)) {
            $slotStart = $start->format('H:i');
            $slotEnd = $start->copy()->addMinutes($duration)->format('H:i');

            // Regla anti-solapamiento (startA < endB) AND (endA > startB)
            $isOccupied = $existingBookings->contains(function ($booking) use ($slotStart, $slotEnd) {
                return ($slotStart < $booking->end_time) && ($slotEnd > $booking->start_time);
            });

            $slots[] = [
                'start' => $slotStart,
                'end' => $slotEnd,
                'is_available' => !$isOccupied
            ];

            $start->addMinutes($duration);
        }

        return $slots;
    }
}