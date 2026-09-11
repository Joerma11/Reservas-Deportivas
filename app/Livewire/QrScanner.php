<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;

class QrScanner extends Component
{
    public $qrCodeInput = '';
    public $bookingFound = null;
    public $message = '';
    public $messageType = ''; // 'success' o 'error'

    public function searchBooking()
    {
        $this->reset(['bookingFound', 'message', 'messageType']);

        if (empty($this->qrCodeInput)) {
            $this->message = 'Por favor ingrese o escanee un código de reserva.';
            $this->messageType = 'error';
            return;
        }

        // Buscar reserva por el patrón del hash guardado en la ruta del QR
        $booking = Booking::with(['field', 'user'])
            ->where('qr_code_path', 'LIKE', "%{$this->qrCodeInput}%")
            ->first();

        if (!$booking) {
            $this->message = 'Código QR no válido o reserva no encontrada.';
            $this->messageType = 'error';
            return;
        }

        $this->bookingFound = $booking;
    }

    public function markAsCompleted()
    {
        if ($this->bookingFound) {
            $this->bookingFound->update(['status' => 'completed']);
            $this->message = '¡Check-in realizado con éxito! La reserva ha sido completada.';
            $this->messageType = 'success';
        }
    }

    public function render()
    {
        return view('livewire.qr-scanner');
    }
}