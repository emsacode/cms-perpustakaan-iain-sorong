<?php

namespace App\Livewire\Admin;

use App\Models\Podcast;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Podcasts extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    // Form states
    public $isEditing = false;
    public $editingId = null;
    public $editTitle = '';
    public $editSlug = '';
    public $editAudioUrl = '';
    public $editVideoUrl = '';
    public $editDescription = '';
    public $editDuration = '20:00';
    public $editPublishedAt = '';

    public function updatingSearch() { $this->resetPage(); }

    public function openEdit($id = null)
    {
        $this->isEditing = true;
        if ($id) {
            $pod = Podcast::findOrFail($id);
            $this->editingId = $id;
            $this->editTitle = $pod->title;
            $this->editSlug = $pod->slug;
            $this->editAudioUrl = $pod->audio_url;
            $this->editVideoUrl = $pod->video_url;
            $this->editDescription = $pod->description;
            $this->editDuration = $pod->duration;
            $this->editPublishedAt = $pod->published_at ? date('Y-m-d\TH:i', strtotime($pod->published_at)) : '';
        } else {
            $this->editingId = null;
            $this->editTitle = '';
            $this->editSlug = '';
            $this->editAudioUrl = '';
            $this->editVideoUrl = '';
            $this->editDescription = '';
            $this->editDuration = '20:00';
            $this->editPublishedAt = '';
        }
    }

    public function closeEdit()
    {
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function saveEdit()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'required|string',
            'editDuration' => 'required|string',
        ]);

        $slug = $this->editSlug ?: Str::slug($this->editTitle);

        Podcast::updateOrCreate(
            ['id' => $this->editingId],
            [
                'title' => $this->editTitle,
                'slug' => $slug,
                'audio_url' => $this->editAudioUrl,
                'video_url' => $this->editVideoUrl,
                'description' => $this->editDescription,
                'duration' => $this->editDuration,
                'published_at' => $this->editPublishedAt ? date('Y-m-d H:i:s', strtotime($this->editPublishedAt)) : null,
            ]
        );

        $this->closeEdit();
        session()->flash('message', $this->editingId ? 'Episode podcast berhasil diperbarui.' : 'Episode podcast baru berhasil ditambahkan.');
    }

    public function deletePodcast($id)
    {
        Podcast::findOrFail($id)->delete();
        session()->flash('message', 'Episode podcast berhasil dihapus.');
    }

    public function render()
    {
        $podcasts = Podcast::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.podcasts', [
            'podcasts' => $podcasts
        ])->layout('layouts.app');
    }
}
