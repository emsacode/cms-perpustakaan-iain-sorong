<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
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

    public function updateStatus($id, $newStatus)
    {
        if (in_array($newStatus, ['pending', 'approved', 'rejected', 'completed'])) {
            DB::table('reservations')
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'updated_at' => now()
                ]);
            session()->flash('message', 'Status reservasi berhasil diperbarui.');
        }
    }

    public function deleteReservation($id)
    {
        DB::table('reservations')->where('id', $id)->delete();
        session()->flash('message', 'Reservasi berhasil dihapus.');
    }

    public function render()
    {
        $query = DB::table('reservations');

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
