<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SettlementsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Booking::with(['field', 'user'])
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->where('status', 'confirmed')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Reserva',
            'Cancha',
            'Deporte',
            'Cliente',
            'Fecha',
            'Hora Inicio',
            'Hora Fin',
            'Monto ($)',
            'Estado',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->field->name ?? 'N/A',
            $booking->field->sport_type ?? 'N/A',
            $booking->user->name ?? 'Cliente Demo',
            $booking->date,
            $booking->start_time,
            $booking->end_time,
            $booking->total_price,
            strtoupper($booking->status),
        ];
    }
}