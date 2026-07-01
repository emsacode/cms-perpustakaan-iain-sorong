<?php

namespace App\Livewire\Admin;

use App\Models\Membership;
use Livewire\Component;
use Livewire\WithPagination;

class Memberships extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }

    public function updateStatus($id, $newStatus)
    {
        if (in_array($newStatus, ['pending', 'active', 'rejected'])) {
            $member = Membership::findOrFail($id);
            $member->status = $newStatus;
            $member->save();
            session()->flash('message', 'Status Anggota Online #' . $member->nim_nip . ' berhasil diperbarui.');
        }
    }

    public function deleteMembership($id)
    {
        Membership::findOrFail($id)->delete();
        session()->flash('message', 'Registrasi anggota online berhasil dihapus.');
    }

    public function render()
    {
        $memberships = Membership::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nim_nip', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($q) {
                $q->where('member_type', $this->typeFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.memberships', [
            'memberships' => $memberships
        ])->layout('layouts.app');
    }
}
