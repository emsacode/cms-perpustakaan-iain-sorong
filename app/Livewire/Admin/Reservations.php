<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class Reservations extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteReservation($id)
    {
        Reservation::findOrFail($id)->delete();
        session()->flash('message', 'Reservasi berhasil dihapus.');
    }

    public function render()
    {
        // Jalankan pembatalan otomatis untuk pengajuan yang melewati batas toleransi 15 menit
        Reservation::checkAndCancelExpiredReservations();

        $query = Reservation::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nim_nip', 'like', '%' . $this->search . '%')
                  ->orWhere('room_name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $reservations = $query->orderBy('booking_date', 'desc')
            ->orderBy('session_time', 'asc')
            ->paginate(10);

        return view('livewire.admin.reservations', [
            'reservations' => $reservations
        ])->layout('layouts.app');
    }
}
