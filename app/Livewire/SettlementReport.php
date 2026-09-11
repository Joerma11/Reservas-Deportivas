<?php

namespace App\Livewire;

use Livewire\Component;
use App\Exports\SettlementsExport;
use App\Models\Booking;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SettlementReport extends Component
{
    public $startDate;
    public $endDate;
    public $totalRevenue = 0;
    public $totalBookings = 0;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->calculateMetrics();
    }

    public function updated($propertyName)
    {
        $this->calculateMetrics();
    }

    public function calculateMetrics()
    {
        $bookings = Booking::whereBetween('date', [$this->startDate, $this->endDate])
            ->where('status', 'confirmed');

        $this->totalBookings = $bookings->count();
        $this->totalRevenue = $bookings->sum('total_price');
    }

    public function exportExcel()
    {
        $fileName = "reporte_liquidaciones_{$this->startDate}_al_{$this->endDate}.xlsx";
        return Excel::download(new SettlementsExport($this->startDate, $this->endDate), $fileName);
    }

    public function render()
    {
        $recentBookings = Booking::with('field')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->where('status', 'confirmed')
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        return view('livewire.settlement-report', compact('recentBookings'));
    }
}