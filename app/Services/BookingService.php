<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BlockedSchedule;
use App\Models\Schedule;
use Carbon\Carbon;

class BookingService
{
    /**
     * Verifica si una cancha está disponible en la fecha y rango horario solicitados.
     */
    public static function isAvailable(int $fieldId, string $date, string $startTime, string $endTime): bool
    {
        $requestedStart = Carbon::parse("{$date} {$startTime}");
        $requestedEnd = Carbon::parse("{$date} {$endTime}");

        // 1. Validar que la fecha/hora no sea en el pasado
        if ($requestedStart->isPast()) {
            return false;
        }

        // 2. Validar que el día de la semana esté dentro del horario de operación de la cancha
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $operatingSchedule = Schedule::where('field_id', $fieldId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->exists();

        if (!$operatingSchedule) {
            return false;
        }

        // 3. Validar solapamientos con reservas activas (pending o confirmed)
        // Fórmula de traslape: (StartA < EndB) AND (EndA > StartB)
        $hasBookingConflict = Booking::where('field_id', $fieldId)
            ->where('date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($hasBookingConflict) {
            return false;
        }

        // 4. Validar solapamientos con horarios bloqueados (mantenimiento o eventos)
        $hasBlockedConflict = BlockedSchedule::where(function ($query) use ($fieldId) {
                $query->where('field_id', $fieldId)
                      ->orWhereNull('field_id'); // Bloqueo global
            })
            ->where('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();

        return !$hasBlockedConflict;
    }
}