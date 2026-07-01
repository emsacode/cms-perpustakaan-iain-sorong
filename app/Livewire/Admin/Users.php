<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();
        session()->flash('message', 'Status pengguna berhasil diperbarui.');
    }

    public function changeRole($id, $newRole)
    {
        $user = User::findOrFail($id);
        if (in_array($newRole, ['admin', 'editor'])) {
            $user->role = $newRole;
            $user->save();
            session()->flash('message', 'Peran pengguna berhasil diperbarui.');
        }
    }

    public function render()
    {
        $users = User::where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
