<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Pages extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $statusTab = 'all'; // all, mine, published, draft, scheduled, trash
    public $seoFilter = '';
    public $readabilityFilter = '';
    public $dateFilter = '';

    // Selection & Bulk actions
    public $selectedPages = [];
    public $bulkAction = '';
    public $selectAll = false;

    // Inline Quick Edit
    public $quickEditId = null;
    public $quickTitle = '';
    public $quickSlug = '';
    public $quickStatus = '';
    public $quickDate = '';

    // Full Edit Panel
    public $isEditing = false;
    public $editingId = null;
    public $editTitle = '';
    public $editSlug = '';
    public $editContent = '';
    public $editStatus = 'published';
    public $editSeoScore = 'none';
    public $editReadabilityScore = 'none';
    public $editPageBuilderType = 'custom';
    public $editViewsCount = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusTab' => ['except' => 'all'],
        'seoFilter' => ['except' => ''],
        'readabilityFilter' => ['except' => ''],
        'dateFilter' => ['except' => '']
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusTab() { $this->resetPage(); }
    public function updatingSeoFilter() { $this->resetPage(); }
    public function updatingReadabilityFilter() { $this->resetPage(); }
    public function updatingDateFilter() { $this->resetPage(); }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPages = Page::pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedPages = [];
        }
    }

    // Contextual actions
    public function duplicatePage($id)
    {
        $original = Page::findOrFail($id);
        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Duplikat)';
        $duplicate->slug = $original->slug . '-duplikat-' . Str::random(5);
        $duplicate->status = 'draft';
        $duplicate->views_count = 0;
        $duplicate->published_at = null;
        $duplicate->save();

        session()->flash('message', 'Halaman berhasil diduplikasi sebagai Draf.');
    }

    public function clearCachePage($id)
    {
        $page = Page::findOrFail($id);
        // Dummy Cache Clear
        session()->flash('message', 'Cache untuk halaman "' . $page->title . '" berhasil dibersihkan.');
    }

    public function trashPage($id)
    {
        $page = Page::findOrFail($id);
        if ($page->status === 'trash') {
            $page->delete();
            session()->flash('message', 'Halaman berhasil dihapus secara permanen.');
        } else {
            $page->status = 'trash';
            $page->save();
            session()->flash('message', 'Halaman dipindahkan ke Sampah.');
        }
    }

    public function restorePage($id)
    {
        $page = Page::findOrFail($id);
        $page->status = 'published';
        $page->save();
        session()->flash('message', 'Halaman berhasil dipulihkan.');
    }

    // Quick Edit
    public function openQuickEdit($id)
    {
        $page = Page::findOrFail($id);
        $this->quickEditId = $page->id;
        $this->quickTitle = $page->title;
        $this->quickSlug = $page->slug;
        $this->quickStatus = $page->status;
        $this->quickDate = $page->published_at ? date('Y-m-d\TH:i', strtotime($page->published_at)) : '';
    }

    public function closeQuickEdit()
    {
        $this->quickEditId = null;
    }

    public function saveQuickEdit()
    {
        $this->validate([
            'quickTitle' => 'required|string|max:255',
            'quickSlug' => 'required|string|max:255',
            'quickStatus' => 'required|in:draft,published,scheduled,trash',
        ]);

        $page = Page::findOrFail($this->quickEditId);
        $page->title = $this->quickTitle;
        $page->slug = Str::slug($this->quickSlug);
        $page->status = $this->quickStatus;
        if (!empty($this->quickDate)) {
            $page->published_at = date('Y-m-d H:i:s', strtotime($this->quickDate));
        }
        $page->save();

        $this->quickEditId = null;
        session()->flash('message', 'Halaman berhasil diperbarui (Edit Cepat).');
    }

    // Full Edit
    public function openEdit($id = null)
    {
        $this->isEditing = true;
        if ($id) {
            $page = Page::findOrFail($id);
            $this->editingId = $page->id;
            $this->editTitle = $page->title;
            $this->editSlug = $page->slug;
            $this->editContent = $page->content;
            $this->editStatus = $page->status;
            $this->editSeoScore = $page->seo_score;
            $this->editReadabilityScore = $page->readability_score;
            $this->editPageBuilderType = $page->page_builder_type;
            $this->editViewsCount = $page->views_count;
        } else {
            // New Page
            $this->editingId = null;
            $this->editTitle = '';
            $this->editSlug = '';
            $this->editContent = '';
            $this->editStatus = 'draft';
            $this->editSeoScore = 'none';
            $this->editReadabilityScore = 'none';
            $this->editPageBuilderType = 'custom';
            $this->editViewsCount = 0;
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
            'editContent' => 'required|string',
            'editStatus' => 'required|in:draft,published,scheduled,trash',
            'editSeoScore' => 'required|in:bad,ok,good,none',
            'editReadabilityScore' => 'required|in:bad,ok,good,none',
            'editPageBuilderType' => 'required|string',
        ]);

        if ($this->editingId) {
            $page = Page::findOrFail($this->editingId);
        } else {
            $page = new Page();
            $page->slug = Str::slug($this->editTitle);
            $page->author_id = auth()->id() ?? 1; // Fallback to 1
        }

        $page->title = $this->editTitle;
        if ($this->editSlug) {
            $page->slug = Str::slug($this->editSlug);
        }
        $page->content = $this->editContent;
        $page->status = $this->editStatus;
        $page->seo_score = $this->editSeoScore;
        $page->readability_score = $this->editReadabilityScore;
        $page->page_builder_type = $this->editPageBuilderType;
        $page->views_count = $this->editViewsCount;
        
        if ($this->editStatus === 'published' && !$page->published_at) {
            $page->published_at = now();
        } elseif ($this->editStatus === 'scheduled') {
            $page->published_at = now()->addDays(2);
        }

        $page->save();

        $this->isEditing = false;
        $this->editingId = null;
        session()->flash('message', 'Halaman berhasil disimpan.');
    }

    // Bulk Actions
    public function applyBulkAction()
    {
        if (empty($this->selectedPages) || empty($this->bulkAction)) {
            return;
        }

        if ($this->bulkAction === 'publish') {
            Page::whereIn('id', $this->selectedPages)->update([
                'status' => 'published',
                'published_at' => now()
            ]);
            session()->flash('message', 'Aksi massal: Halaman terpilih telah diterbitkan.');
        } elseif ($this->bulkAction === 'draft') {
            Page::whereIn('id', $this->selectedPages)->update([
                'status' => 'draft'
            ]);
            session()->flash('message', 'Aksi massal: Halaman terpilih diubah menjadi Draf.');
        } elseif ($this->bulkAction === 'trash') {
            Page::whereIn('id', $this->selectedPages)->update([
                'status' => 'trash'
            ]);
            session()->flash('message', 'Aksi massal: Halaman terpilih dipindahkan ke Sampah.');
        } elseif ($this->bulkAction === 'delete') {
            Page::whereIn('id', $this->selectedPages)->delete();
            session()->flash('message', 'Aksi massal: Halaman terpilih dihapus secara permanen.');
        }

        $this->selectedPages = [];
        $this->selectAll = false;
        $this->bulkAction = '';
    }

    public function render()
    {
        // Get available months/years for filter
        $dates = Page::selectRaw('strftime("%Y-%m", created_at) as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        $query = Page::with('author');

        // Search
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Tabs
        if ($this->statusTab === 'mine') {
            $query->where('author_id', auth()->id() ?? 1);
        } elseif ($this->statusTab !== 'all') {
            $query->where('status', $this->statusTab);
        }

        // Filters
        if (!empty($this->seoFilter)) {
            $query->where('seo_score', $this->seoFilter);
        }
        if (!empty($this->readabilityFilter)) {
            $query->where('readability_score', $this->readabilityFilter);
        }
        if (!empty($this->dateFilter)) {
            $query->whereRaw('strftime("%Y-%m", created_at) = ?', [$this->dateFilter]);
        }

        // Get counts
        $counts = [
            'all' => Page::count(),
            'mine' => Page::where('author_id', auth()->id() ?? 1)->count(),
            'published' => Page::where('status', 'published')->count(),
            'draft' => Page::where('status', 'draft')->count(),
            'scheduled' => Page::where('status', 'scheduled')->count(),
            'trash' => Page::where('status', 'trash')->count(),
        ];

        $pages = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.pages', [
            'pages' => $pages,
            'counts' => $counts,
            'availableDates' => $dates
        ])->layout('layouts.app');
    }
}
