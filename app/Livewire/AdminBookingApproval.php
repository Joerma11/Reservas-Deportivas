<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;

class AdminBookingApproval extends Component
{
    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);
    }

    public function rejectBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
    }

    public function render()
    {
        $pendingBookings = Booking::with(['field', 'user'])
            ->where('status', 'pending')
            ->orderBy('date', 'asc')
            ->get();

        $confirmedBookings = Booking::with(['field', 'user'])
            ->where('status', 'confirmed')
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        return view('livewire.admin-booking-approval', compact('pendingBookings', 'confirmedBookings'));
    }
}