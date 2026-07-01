<?php

namespace App\Livewire\Admin;

use App\Models\Clearance;
use Livewire\Component;
use Livewire\WithPagination;

class Clearances extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function updateStatus($id, $newStatus)
    {
        if (in_array($newStatus, ['pending', 'approved', 'rejected'])) {
            $clear = Clearance::findOrFail($id);
            $clear->status = $newStatus;
            if ($newStatus === 'approved') {
                $clear->receipt_file = 'uploads/receipts/clearance_receipt_' . $clear->nim_nidn . '.pdf';
            }
            $clear->save();
            session()->flash('message', 'Status Bebas Pustaka #' . $clear->nim_nidn . ' berhasil diperbarui.');
        }
    }

    public function deleteClearance($id)
    {
        Clearance::findOrFail($id)->delete();
        session()->flash('message', 'Pengajuan Bebas Pustaka berhasil dihapus.');
    }

    public function render()
    {
        $clearances = Clearance::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nim_nidn', 'like', '%' . $this->search . '%')
                        ->orWhere('program_studi', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.clearances', [
            'clearances' => $clearances
        ])->layout('layouts.app');
    }
}
