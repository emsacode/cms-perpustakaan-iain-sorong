<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SdgsTag;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Articles extends Component
{
    use WithPagination;

    // URL Query string parameters
    public $action = ''; // '', 'create', 'categories', 'tags', 'sdgs'
    public $search = '';
    public $statusTab = 'all';
    public $dateFilter = '';
    public $categoryFilter = '';
    public $seoFilter = '';
    public $readabilityFilter = '';

    protected $queryString = [
        'action' => ['except' => ''],
        'search' => ['except' => ''],
        'statusTab' => ['except' => 'all'],
        'dateFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'seoFilter' => ['except' => ''],
        'readabilityFilter' => ['except' => ''],
    ];

    // Bulk selection state
    public $selectedArticles = [];
    public $selectAll = false;
    public $bulkAction = '';

    // Quick Edit state
    public $quickEditId = null;
    public $quickTitle = '';
    public $quickSlug = '';
    public $quickDate = '';
    public $quickStatus = '';
    public $quickCategories = [];
    public $quickSdgs = [];

    // Full Edit / Create state
    public $isEditing = false;
    public $editingId = null;
    public $editTitle = '';
    public $editSlug = '';
    public $editContent = '';
    public $editStatus = 'draft';
    public $editSeoScore = 'none';
    public $editReadabilityScore = 'none';
    public $editViewsCount = 0;
    public $editCategories = [];
    public $editTags = ''; // comma-separated
    public $editSdgs = [];
    public $editPublishedAt = '';

    // Taxonomy Manager state
    public $newCategoryName = '';
    public $newCategorySlug = '';
    public $newTagName = '';
    public $newTagSlug = '';

    public function mount()
    {
        if ($this->action === 'create') {
            $this->openEdit();
        }
    }

    public function updatedAction($value)
    {
        if ($value === 'create') {
            $this->openEdit();
        } else {
            $this->isEditing = false;
        }
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusTab() { $this->resetPage(); }
    public function updatingDateFilter() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingSeoFilter() { $this->resetPage(); }
    public function updatingReadabilityFilter() { $this->resetPage(); }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedArticles = Article::query()
                ->when($this->statusTab !== 'all', function ($q) {
                    if ($this->statusTab === 'mine') {
                        $q->where('user_id', auth()->id());
                    } else {
                        $q->where('status', $this->statusTab);
                    }
                })
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedArticles = [];
        }
    }

    public function applyBulkAction()
    {
        if (empty($this->selectedArticles) || !$this->bulkAction) {
            return;
        }

        $query = Article::whereIn('id', $this->selectedArticles);

        if ($this->bulkAction === 'publish') {
            $query->update(['status' => 'published', 'published_at' => now()]);
            session()->flash('message', 'Artikel terpilih berhasil diterbitkan.');
        } elseif ($this->bulkAction === 'draft') {
            $query->update(['status' => 'draft']);
            session()->flash('message', 'Artikel terpilih diubah menjadi draf.');
        } elseif ($this->bulkAction === 'trash') {
            $query->update(['status' => 'trash']);
            session()->flash('message', 'Artikel terpilih dipindahkan ke Sampah.');
        } elseif ($this->bulkAction === 'delete') {
            $query->delete();
            session()->flash('message', 'Artikel terpilih dihapus secara permanen.');
        }

        $this->selectedArticles = [];
        $this->selectAll = false;
        $this->bulkAction = '';
    }

    public function trashArticle($id)
    {
        $art = Article::findOrFail($id);
        if ($art->status === 'trash') {
            $art->delete();
            session()->flash('message', 'Artikel berhasil dihapus secara permanen.');
        } else {
            $art->status = 'trash';
            $art->save();
            session()->flash('message', 'Artikel dipindahkan ke Sampah.');
        }
    }

    public function restoreArticle($id)
    {
        $art = Article::findOrFail($id);
        $art->status = 'draft';
        $art->save();
        session()->flash('message', 'Artikel berhasil dipulihkan sebagai Draf.');
    }

    public function duplicateArticle($id)
    {
        $art = Article::findOrFail($id);
        $newArt = $art->replicate();
        $newArt->title = $art->title . ' (Salinan)';
        $newArt->slug = Str::slug($newArt->title) . '-' . Str::random(5);
        $newArt->status = 'draft';
        $newArt->views_count = 0;
        $newArt->published_at = null;
        $newArt->save();

        // Sync relationships
        $newArt->categories()->sync($art->categories->pluck('id')->toArray());
        $newArt->tags()->sync($art->tags->pluck('id')->toArray());
        $newArt->sdgsTags()->sync($art->sdgsTags->pluck('id')->toArray());

        session()->flash('message', 'Artikel berhasil diduplikat sebagai Draf.');
    }

    public function clearCacheArticle($id)
    {
        session()->flash('message', 'Cache untuk artikel #' . $id . ' berhasil dibersihkan.');
    }

    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Judul', 'Slug', 'Penulis', 'Kategori', 'SDGs Tag', 'Status', 'Views', 'SEO', 'Keterbacaan', 'Tanggal Terbit']);

            $articles = Article::with(['user', 'categories', 'sdgsTags'])
                ->when(!empty($this->selectedArticles), function($q) {
                    $q->whereIn('id', $this->selectedArticles);
                })->get();

            foreach ($articles as $art) {
                fputcsv($handle, [
                    $art->id,
                    $art->title,
                    $art->slug,
                    $art->user->name ?? 'Admin',
                    $art->categories->pluck('name')->join(', '),
                    $art->sdgsTags->pluck('name')->join(', '),
                    $art->status,
                    $art->views_count,
                    $art->seo_score,
                    $art->readability_score,
                    $art->published_at ? $art->published_at : '-'
                ]);
            }
            fclose($handle);
        }, 'berita-export-' . now()->format('Ymd') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    // Quick Edit Methods
    public function openQuickEdit($id)
    {
        $art = Article::with(['categories', 'sdgsTags'])->findOrFail($id);
        $this->quickEditId = $id;
        $this->quickTitle = $art->title;
        $this->quickSlug = $art->slug;
        $this->quickDate = $art->published_at ? date('Y-m-d\TH:i', strtotime($art->published_at)) : '';
        $this->quickStatus = $art->status;
        $this->quickCategories = $art->categories->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->quickSdgs = $art->sdgsTags->pluck('id')->map(fn($id) => (string)$id)->toArray();
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

        $art = Article::findOrFail($this->quickEditId);
        $art->title = $this->quickTitle;
        $art->slug = Str::slug($this->quickSlug);
        $art->status = $this->quickStatus;
        $art->published_at = $this->quickDate ? date('Y-m-d H:i:s', strtotime($this->quickDate)) : null;
        $art->save();

        $art->categories()->sync($this->quickCategories);
        $art->sdgsTags()->sync($this->quickSdgs);

        $this->quickEditId = null;
        session()->flash('message', 'Artikel berhasil diperbarui via Edit Cepat.');
    }

    // Full Edit Methods
    public function openEdit($id = null)
    {
        $this->isEditing = true;
        if ($id) {
            $art = Article::with(['categories', 'tags', 'sdgsTags'])->findOrFail($id);
            $this->editingId = $id;
            $this->editTitle = $art->title;
            $this->editSlug = $art->slug;
            $this->editContent = $art->content;
            $this->editStatus = $art->status;
            $this->editSeoScore = $art->seo_score;
            $this->editReadabilityScore = $art->readability_score;
            $this->editViewsCount = $art->views_count;
            $this->editCategories = $art->categories->pluck('id')->map(fn($id) => (string)$id)->toArray();
            $this->editTags = $art->tags->pluck('name')->join(', ');
            $this->editSdgs = $art->sdgsTags->pluck('id')->map(fn($id) => (string)$id)->toArray();
            $this->editPublishedAt = $art->published_at ? date('Y-m-d\TH:i', strtotime($art->published_at)) : '';
        } else {
            $this->editingId = null;
            $this->editTitle = '';
            $this->editSlug = '';
            $this->editContent = '';
            $this->editStatus = 'draft';
            $this->editSeoScore = 'none';
            $this->editReadabilityScore = 'none';
            $this->editViewsCount = 0;
            $this->editCategories = [];
            $this->editTags = '';
            $this->editSdgs = [];
            $this->editPublishedAt = '';
        }
    }

    public function closeEdit()
    {
        $this->isEditing = false;
        $this->editingId = null;
        if ($this->action === 'create') {
            $this->action = '';
        }
    }

    public function saveEdit()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editContent' => 'required',
            'editStatus' => 'required|in:draft,published,scheduled,trash',
        ]);

        $slug = $this->editSlug ?: Str::slug($this->editTitle);

        $art = Article::updateOrCreate(
            ['id' => $this->editingId],
            [
                'user_id' => auth()->id() ?: 1, // Fallback to user id 1
                'title' => $this->editTitle,
                'slug' => $slug,
                'content' => $this->editContent,
                'status' => $this->editStatus,
                'views_count' => $this->editViewsCount ?: 0,
                'seo_score' => $this->editSeoScore ?: 'none',
                'readability_score' => $this->editReadabilityScore ?: 'none',
                'published_at' => $this->editPublishedAt ? date('Y-m-d H:i:s', strtotime($this->editPublishedAt)) : null,
            ]
        );

        // Sync Categories
        $art->categories()->sync($this->editCategories);

        // Process and Sync Tags
        $tagIds = [];
        if (!empty($this->editTags)) {
            $names = array_map('trim', explode(',', $this->editTags));
            foreach ($names as $name) {
                if ($name !== '') {
                    $t = Tag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
                    $tagIds[] = $t->id;
                }
            }
        }
        $art->tags()->sync($tagIds);

        // Sync SDGs Tags
        $art->sdgsTags()->sync($this->editSdgs);

        $this->closeEdit();
        session()->flash('message', $this->editingId ? 'Artikel berhasil diperbarui.' : 'Artikel baru berhasil dibuat.');
    }

    // Taxonomy Management Methods
    public function addCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:100',
        ]);

        $slug = $this->newCategorySlug ?: Str::slug($this->newCategoryName);
        Category::create([
            'name' => $this->newCategoryName,
            'slug' => $slug,
        ]);

        $this->newCategoryName = '';
        $this->newCategorySlug = '';
        session()->flash('message', 'Kategori baru berhasil ditambahkan.');
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    public function addTag()
    {
        $this->validate([
            'newTagName' => 'required|string|max:100',
        ]);

        $slug = $this->newTagSlug ?: Str::slug($this->newTagName);
        Tag::create([
            'name' => $this->newTagName,
            'slug' => $slug,
        ]);

        $this->newTagName = '';
        $this->newTagSlug = '';
        session()->flash('message', 'Tag baru berhasil ditambahkan.');
    }

    public function deleteTag($id)
    {
        Tag::findOrFail($id)->delete();
        session()->flash('message', 'Tag berhasil dihapus.');
    }

    public function render()
    {
        // 1. If taxonomy views are requested
        if ($this->action === 'categories') {
            $categories = Category::withCount('articles')->orderBy('name', 'asc')->paginate(15);
            return view('livewire.admin.articles-categories', [
                'categories' => $categories
            ])->layout('layouts.app');
        }

        if ($this->action === 'tags') {
            $tags = Tag::withCount('articles')->orderBy('name', 'asc')->paginate(15);
            return view('livewire.admin.articles-tags', [
                'tags' => $tags
            ])->layout('layouts.app');
        }

        if ($this->action === 'sdgs') {
            $sdgs = SdgsTag::withCount('articles')->orderBy('id', 'asc')->paginate(20);
            return view('livewire.admin.articles-sdgs', [
                'sdgs' => $sdgs
            ])->layout('layouts.app');
        }

        // 2. Fetch sidebar stats
        $counts = [
            'all' => Article::count(),
            'mine' => Article::where('user_id', auth()->id() ?: 1)->count(),
            'published' => Article::where('status', 'published')->count(),
            'draft' => Article::where('status', 'draft')->count(),
            'scheduled' => Article::where('status', 'scheduled')->count(),
            'trash' => Article::where('status', 'trash')->count(),
        ];

        // 3. Dates for filters
        $availableDates = Article::selectRaw("strftime('%Y-%m', created_at) as month_year")
            ->distinct()
            ->orderBy('month_year', 'desc')
            ->pluck('month_year')
            ->toArray();

        // 4. Categories list
        $categoriesList = Category::orderBy('name', 'asc')->get();
        $sdgsList = SdgsTag::orderBy('name', 'asc')->get();

        // 5. Query Articles
        $articles = Article::with(['user', 'categories', 'sdgsTags'])
            ->when($this->statusTab !== 'all', function ($q) {
                if ($this->statusTab === 'mine') {
                    $q->where('user_id', auth()->id() ?: 1);
                } else {
                    $q->where('status', $this->statusTab);
                }
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('content', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateFilter, function ($q) {
                $q->whereRaw("strftime('%Y-%m', created_at) = ?", [$this->dateFilter]);
            })
            ->when($this->categoryFilter, function ($q) {
                $q->whereHas('categories', function ($sub) {
                    $sub->where('categories.id', $this->categoryFilter);
                });
            })
            ->when($this->seoFilter, function ($q) {
                $q->where('seo_score', $this->seoFilter);
            })
            ->when($this->readabilityFilter, function ($q) {
                $q->where('readability_score', $this->readabilityFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('livewire.admin.articles', [
            'articles' => $articles,
            'counts' => $counts,
            'availableDates' => $availableDates,
            'categoriesList' => $categoriesList,
            'sdgsList' => $sdgsList,
        ])->layout('layouts.app');
    }
}
