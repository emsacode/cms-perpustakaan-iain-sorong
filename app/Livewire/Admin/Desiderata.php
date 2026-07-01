<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Desiderata extends Component
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
        if (in_array($newStatus, ['pending', 'approved', 'purchased'])) {
            DB::table('desiderata')
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'updated_at' => now()
                ]);
            session()->flash('message', 'Status usulan buku berhasil diperbarui.');
        }
    }

    public function deleteDesiderata($id)
    {
        DB::table('desiderata')->where('id', $id)->delete();
        session()->flash('message', 'Usulan buku berhasil dihapus.');
    }

    public function render()
    {
        $query = DB::table('desiderata');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('author', 'like', '%' . $this->search . '%')
                  ->orWhere('isbn', 'like', '%' . $this->search . '%')
                  ->orWhere('proposer_name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $desiderata = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.desiderata', [
            'desiderata' => $desiderata
        ])->layout('layouts.app');
    }
}
