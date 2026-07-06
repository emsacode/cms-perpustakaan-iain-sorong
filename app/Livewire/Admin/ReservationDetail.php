<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use App\Mail\ReservationStatusMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ReservationDetail extends Component
{
    public $reservationId;
    public $rejectionReasonText = '';
    public $notesInventoryText = '';
    public $showRejectInput = false;

    public function mount($id)
    {
        $reservation = Reservation::findOrFail($id);
        $this->reservationId = $id;
        $this->rejectionReasonText = $reservation->rejection_reason ?? '';
        $this->notesInventoryText = $reservation->notes_inventory ?? '';
    }

    public function getRoomFacilities($roomName)
    {
        $facilities = [
            'Ruang Diskusi Kelompok 1' => ['AC', 'Remote AC', 'Meja & Kursi Diskusi', 'Whiteboard', 'Spidol'],
            'Ruang Home Theater' => ['AC', 'Remote AC', 'Proyektor Epson', 'Remote Proyektor', 'Sound System Yamaha', 'Sofa/Kursi Theater'],
            'Ruang Multimedia' => ['AC', 'Remote AC', 'Smart TV / Screen proyektor', 'Remote TV', 'Sound System', '2 Wireless Microphones', 'Kabel HDMI'],
        ];

        return $facilities[$roomName] ?? ['Fasilitas standar ruangan'];
    }

    public function approveReservation()
    {
        $reservation = Reservation::findOrFail($this->reservationId);
        $reservation->update([
            'status' => 'approved',
            'updated_at' => now()
        ]);

        try {
            Mail::to($reservation->email)->send(new ReservationStatusMail($reservation));
        } catch (\Exception $e) {
            logger()->error('Gagal mengirim email reservasi disetujui: ' . $e->getMessage());
        }

        session()->flash('message', 'Reservasi berhasil disetujui dan notifikasi email dikirim.');
    }

    public function rejectReservation()
    {
        $this->validate([
            'rejectionReasonText' => 'required|string|max:1000'
        ], [
            'rejectionReasonText.required' => 'Alasan penolakan wajib diisi.'
        ]);

        $reservation = Reservation::findOrFail($this->reservationId);
        $reservation->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReasonText,
            'updated_at' => now()
        ]);

        try {
            Mail::to($reservation->email)->send(new ReservationStatusMail($reservation));
        } catch (\Exception $e) {
            logger()->error('Gagal mengirim email reservasi ditolak: ' . $e->getMessage());
        }

        session()->flash('message', 'Reservasi berhasil ditolak dan notifikasi email dikirim.');
    }

    public function releaseKey()
    {
        $reservation = Reservation::findOrFail($this->reservationId);
        $reservation->update([
            'status' => 'key_picked_up',
            'picked_up_at' => now(),
            'notes_inventory' => $this->notesInventoryText ?: null,
            'updated_at' => now()
        ]);

        session()->flash('message', 'Kunci ruangan berhasil diserahkan.');
    }

    public function returnKey()
    {
        $reservation = Reservation::findOrFail($this->reservationId);
        $reservation->update([
            'status' => 'returned',
            'returned_at' => now(),
            'notes_inventory' => $this->notesInventoryText ?: null,
            'updated_at' => now()
        ]);

        session()->flash('message', 'Kunci ruangan berhasil dikembalikan. Transaksi peminjaman selesai.');
    }

    public function render()
    {
        $reservation = Reservation::findOrFail($this->reservationId);
        $facilities = $this->getRoomFacilities($reservation->room_name);

        return view('livewire.admin.reservation-detail', [
            'reservation' => $reservation,
            'facilities' => $facilities
        ])->layout('layouts.app');
    }
}
