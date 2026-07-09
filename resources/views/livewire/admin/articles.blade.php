<div class="space-y-6">
    @if($isEditing)
        <!-- Full Edit / Create New Post Form -->
        <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm">
            <div class="flex items-center justify-between border-b border-border p-6">
                <div>
                    <h2 class="text-lg font-bold tracking-tight text-foreground">
                        {{ $editingId ? 'Sunting Pos Berita' : 'Tambah Pos Berita Baru' }}
                    </h2>
                    <p class="text-xs text-muted-foreground mt-0.5">Tulis berita menarik dan atur pengelompokan taksonomi artikel.</p>
                </div>
                <button wire:click="closeEdit" class="text-xs font-semibold px-3 py-1.5 bg-secondary text-secondary-foreground rounded-lg border border-border hover:bg-accent transition-colors">
                    Kembali ke Daftar
                </button>
            </div>

            <form wire:submit.prevent="saveEdit" class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Main Content Column (2 Columns) -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="space-y-2">
                            <label for="editTitle" class="text-xs font-semibold text-foreground">Judul Berita</label>
                            <input wire:model="editTitle" 
                                   type="text" 
                                   id="editTitle" 
                                   placeholder="Tambahkan judul berita..."
                                   class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                            @error('editTitle') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="editSlug" class="text-xs font-semibold text-foreground">Slug URL</label>
                            <input wire:model="editSlug" 
                                   type="text" 
                                   id="editSlug" 
                                   placeholder="slug-url-berita"
                                   class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                        </div>

                        <div class="space-y-2"
                             x-data="{ 
                                content: @entangle('editContent'),
                                initCKEditor() {
                                    if (typeof CKEDITOR === 'undefined') {
                                        setTimeout(() => this.initCKEditor(), 100);
                                        return;
                                    }
                                    
                                    if (CKEDITOR.instances['editContentEditor']) {
                                        CKEDITOR.instances['editContentEditor'].destroy(true);
                                    }

                                    const editor = CKEDITOR.replace($refs.editor, {
                                        versionCheck: false,
                                        filebrowserImageUploadUrl: '/api/v1/editor/upload',
                                        toolbar: [
                                            { name: 'document', items: [ 'Source' ] },
                                            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'Undo', 'Redo' ] },
                                            { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                                            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat' ] },
                                            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                                            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                                            { name: 'links', items: [ 'Link', 'Unlink' ] },
                                            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule' ] },
                                            { name: 'tools', items: [ 'Maximize' ] }
                                        ],
                                        height: 350,
                                        removePlugins: 'easyimage,cloudservices',
                                    });

                                    // Set initial value
                                    editor.setData(this.content || '');

                                    // Sync from Editor to Livewire
                                    editor.on('change', () => {
                                        this.content = editor.getData();
                                    });

                                    // Watch Livewire state and update editor if out of sync
                                    this.$watch('content', value => {
                                        if (value !== editor.getData()) {
                                            editor.setData(value || '');
                                        }
                                    });
                                }
                             }"
                             x-init="initCKEditor()"
                             wire:ignore>
                            <label for="editContent" class="text-xs font-semibold text-foreground">Isi Berita (CKEditor 4)</label>
                            
                            <!-- Include CKEditor 4 Standard from CDN -->
                            <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

                            <div class="rounded-lg overflow-hidden border border-border">
                                <textarea id="editContentEditor" x-ref="editor" class="text-foreground bg-background"></textarea>
                            </div>
                            @error('editContent') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <!-- Excerpt / Ringkasan -->
                        <div class="space-y-2">
                            <label for="editExcerpt" class="text-xs font-semibold text-foreground">Ringkasan Berita (Excerpt)</label>
                            <textarea wire:model="editExcerpt" 
                                      id="editExcerpt" 
                                      rows="3" 
                                      placeholder="Ringkasan pendek berita untuk kartu berita..."
                                      class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring resize-y"></textarea>
                            @error('editExcerpt') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Right Sidebar Settings Column (1 Column) -->
                    <div class="bg-muted/40 border border-border rounded-lg p-5 space-y-5 h-fit">
                        <h3 class="text-xs font-bold text-foreground uppercase tracking-wider border-b border-border pb-2">Atribut & Taksonomi</h3>

                        <!-- Cover Image -->
                        <div class="space-y-2" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <label class="text-xs font-semibold text-muted-foreground block">Gambar Cover Berita</label>
                            
                            <!-- Preview -->
                            @if ($editImage)
                                <div class="relative w-full aspect-video rounded-lg overflow-hidden border border-border bg-muted mb-2 group">
                                    @if (is_object($editImage))
                                        <img src="{{ $editImage->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif (is_string($editImage))
                                        <img src="{{ Str::startsWith($editImage, '/') ? $editImage : asset('storage/' . $editImage) }}" class="w-full h-full object-cover">
                                    @endif
                                    <button type="button" wire:click="$set('editImage', null)" class="absolute top-2 right-2 bg-destructive text-destructive-foreground p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            @endif

                            <input type="file" wire:model="editImage" id="editImage" class="hidden" accept="image/*">
                            <label for="editImage" class="flex items-center justify-center gap-2 w-full px-3 py-2 border border-dashed border-border rounded-lg bg-background text-xs text-muted-foreground hover:text-foreground cursor-pointer hover:border-muted-foreground transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                <span>Pilih Gambar Cover</span>
                            </label>

                            <!-- Progress Bar -->
                            <div x-show="isUploading" class="w-full bg-muted rounded-full h-1.5 overflow-hidden">
                                <div class="bg-primary h-1.5" x-bind:style="'width: ' + progress + '%'"></div>
                            </div>
                            
                            @error('editImage') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label for="editStatus" class="text-xs font-semibold text-muted-foreground">Status Pos</label>
                            <select wire:model="editStatus" id="editStatus" class="w-full px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                                <option value="published">Telah Terbit</option>
                                <option value="draft">Draf</option>
                                <option value="scheduled">Terjadwal</option>
                                <option value="trash">Sampah</option>
                            </select>
                        </div>

                        <!-- Date -->
                        <div class="space-y-2">
                            <label for="editPublishedAt" class="text-xs font-semibold text-muted-foreground">Tanggal & Waktu Terbit</label>
                            <input wire:model="editPublishedAt" type="datetime-local" id="editPublishedAt" class="w-full px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                        </div>

                        <!-- Categories (Checkboxes) -->
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-muted-foreground block">Kategori Berita</label>
                            <div class="max-h-[140px] overflow-y-auto border border-border bg-background rounded-lg p-3 space-y-2">
                                @foreach($categoriesList as $cat)
                                    <label class="flex items-center gap-2 text-xs text-foreground cursor-pointer">
                                        <input type="checkbox" wire:model="editCategories" value="{{ $cat->id }}" class="rounded border-border text-primary focus:ring-ring">
                                        <span>{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>



                        <!-- Free text tags -->
                        <div class="space-y-2">
                            <label for="editTags" class="text-xs font-semibold text-muted-foreground">Tag Kata Kunci</label>
                            <input wire:model="editTags" type="text" id="editTags" placeholder="skripsi, literasi, perpus (pisahkan koma)" class="w-full px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                            <p class="text-[9px] text-muted-foreground">Pisahkan tag dengan tanda koma.</p>
                        </div>

                        <!-- SEO & Readability (Optional for review check) -->
                        <div class="grid grid-cols-2 gap-3 border-t border-border pt-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-muted-foreground uppercase">Skor SEO</label>
                                <select wire:model="editSeoScore" class="w-full px-2 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                    <option value="none">Tidak Ada</option>
                                    <option value="good">Baik</option>
                                    <option value="ok">Cukup</option>
                                    <option value="bad">Buruk</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-muted-foreground uppercase">Readability</label>
                                <select wire:model="editReadabilityScore" class="w-full px-2 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                    <option value="none">Tidak Ada</option>
                                    <option value="good">Baik</option>
                                    <option value="ok">Cukup</option>
                                    <option value="bad">Buruk</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2">
                            <label for="editViews" class="text-xs font-semibold text-muted-foreground">Simulasi Views</label>
                            <input wire:model="editViewsCount" type="number" id="editViews" class="w-full px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                        </div>

                        <div class="pt-4 border-t border-border flex gap-3">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                                Simpan Pos Berita
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @else
        <!-- Index Articles Table View -->
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">Semua Pos Berita</h1>
                    <p class="text-xs text-muted-foreground mt-1">Kelola artikel berita, pengumuman, dan kategori perpustakaan.</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <button wire:click="exportCsv" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-lg bg-secondary text-secondary-foreground border border-border hover:bg-accent transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5 lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Ekspor CSV
                    </button>
                    <button wire:click="openEdit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                        Tambah Pos Baru
                    </button>
                </div>
            </div>

            <!-- Flash Session Alert -->
            @if (session()->has('message'))
                <div class="p-3 text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle-2">
                        <circle cx="12" cy="12" r="10"/><path d="m9 11 3 3 6-6"/>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            <!-- Tabs Status Cepat -->
            <div class="flex flex-wrap items-center gap-2 border-b border-border pb-1">
                <button wire:click="$set('statusTab', 'all')" class="text-xs font-medium px-3 py-1.5 rounded-t-lg transition-colors border-b-2 {{ $statusTab === 'all' ? 'border-primary text-foreground font-semibold' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
                    Semua <span class="text-[10px] bg-secondary px-1.5 py-0.5 rounded border border-border text-muted-foreground">{{ $counts['all'] }}</span>
                </button>
                <button wire:click="$set('statusTab', 'mine')" class="text-xs font-medium px-3 py-1.5 rounded-t-lg transition-colors border-b-2 {{ $statusTab === 'mine' ? 'border-primary text-foreground font-semibold' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
                    Milikku <span class="text-[10px] bg-secondary px-1.5 py-0.5 rounded border border-border text-muted-foreground">{{ $counts['mine'] }}</span>
                </button>
                <button wire:click="$set('statusTab', 'published')" class="text-xs font-medium px-3 py-1.5 rounded-t-lg transition-colors border-b-2 {{ $statusTab === 'published' ? 'border-primary text-foreground font-semibold' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
                    Telah Terbit <span class="text-[10px] bg-secondary px-1.5 py-0.5 rounded border border-border text-muted-foreground">{{ $counts['published'] }}</span>
                </button>
                <button wire:click="$set('statusTab', 'draft')" class="text-xs font-medium px-3 py-1.5 rounded-t-lg transition-colors border-b-2 {{ $statusTab === 'draft' ? 'border-primary text-foreground font-semibold' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
                    Draf <span class="text-[10px] bg-secondary px-1.5 py-0.5 rounded border border-border text-muted-foreground">{{ $counts['draft'] }}</span>
                </button>
                <button wire:click="$set('statusTab', 'scheduled')" class="text-xs font-medium px-3 py-1.5 rounded-t-lg transition-colors border-b-2 {{ $statusTab === 'scheduled' ? 'border-primary text-foreground font-semibold' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
                    Terjadwal <span class="text-[10px] bg-secondary px-1.5 py-0.5 rounded border border-border text-muted-foreground">{{ $counts['scheduled'] }}</span>
                </button>
                <button wire:click="$set('statusTab', 'trash')" class="text-xs font-medium px-3 py-1.5 rounded-t-lg transition-colors border-b-2 {{ $statusTab === 'trash' ? 'border-primary text-foreground font-semibold' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
                    Sampah <span class="text-[10px] bg-secondary px-1.5 py-0.5 rounded border border-border text-muted-foreground">{{ $counts['trash'] }}</span>
                </button>
            </div>

            <!-- Filter Controls -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                <!-- Bulk Actions -->
                <div class="flex items-center gap-2">
                    <select wire:model="bulkAction" class="px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                        <option value="">Tindakan Massal</option>
                        <option value="publish">Terbitkan</option>
                        <option value="draft">Ubah Jadi Draf</option>
                        <option value="trash">Pindahkan ke Sampah</option>
                        <option value="delete">Hapus Permanen</option>
                    </select>
                    <button wire:click="applyBulkAction" class="px-3 py-1.5 bg-secondary text-secondary-foreground text-xs font-semibold rounded-lg border border-border hover:bg-accent transition-colors">
                        Terapkan
                    </button>
                </div>

                <!-- Filters dropdowns & Search -->
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Date Filter -->
                    <select wire:model.live="dateFilter" class="px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                        <option value="">Semua Tanggal</option>
                        @foreach($availableDates as $date)
                            <option value="{{ $date }}">{{ date('F Y', strtotime($date . '-01')) }}</option>
                        @endforeach
                    </select>

                    <!-- Category Filter -->
                    <select wire:model.live="categoryFilter" class="px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categoriesList as $cList)
                            <option value="{{ $cList->id }}">{{ $cList->name }}</option>
                        @endforeach
                    </select>

                    <!-- Search Input -->
                    <div class="relative min-w-[200px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground lucide lucide-search">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berita..." class="w-full pl-8 pr-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                    </div>
                </div>
            </div>

            <!-- Tabular Posts Data Table -->
            <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                                <th class="p-4 w-10">
                                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-border text-primary focus:ring-ring">
                                </th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Judul Berita</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Penulis</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Kategori</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tanggal & Status</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Views</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($articles as $a)
                                @if($quickEditId === $a->id)
                                    <!-- Inline Quick Edit Row -->
                                    <tr class="bg-muted/50 border-y-2 border-indigo-500/20">
                                        <td colspan="6" class="p-5">
                                            <div class="space-y-4">
                                                <h4 class="text-xs font-bold text-foreground uppercase tracking-wider">Edit Cepat Pos</h4>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <!-- Title & Slug -->
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Judul</label>
                                                        <input wire:model="quickTitle" type="text" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                    </div>
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Slug</label>
                                                        <input wire:model="quickSlug" type="text" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                    </div>
                                                    <!-- Date & Status -->
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Tanggal</label>
                                                        <input wire:model="quickDate" type="datetime-local" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <!-- Status -->
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Status</label>
                                                        <select wire:model="quickStatus" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                            <option value="published">Telah Terbit</option>
                                                            <option value="draft">Draf</option>
                                                            <option value="scheduled">Terjadwal</option>
                                                            <option value="trash">Sampah</option>
                                                        </select>
                                                    </div>

                                                    <!-- Categories checkbox options -->
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Kategori</label>
                                                        <div class="max-h-[100px] overflow-y-auto border border-border bg-background rounded p-2 space-y-1">
                                                            @foreach($categoriesList as $cat)
                                                                <label class="flex items-center gap-1.5 text-[11px] text-foreground cursor-pointer">
                                                                    <input type="checkbox" wire:model="quickCategories" value="{{ $cat->id }}" class="rounded scale-90 border-border text-primary focus:ring-ring">
                                                                    <span>{{ $cat->name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-end gap-2 border-t border-border pt-3">
                                                    <button type="button" wire:click="closeQuickEdit" class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded border border-border hover:bg-accent transition-colors">
                                                        Batal
                                                    </button>
                                                    <button type="button" wire:click="saveQuickEdit" class="px-3 py-1 bg-primary text-primary-foreground text-xs font-semibold rounded hover:bg-primary/95 transition-colors">
                                                        Perbarui
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <!-- Standard News Row with Hover Menu Actions -->
                                    <tr class="hover:bg-muted/30 transition-colors group">
                                        <td class="p-4 align-top">
                                            <input type="checkbox" wire:model.live="selectedArticles" value="{{ $a->id }}" class="rounded border-border text-primary focus:ring-ring">
                                        </td>
                                        <td class="p-4 align-top">
                                            <div class="flex items-center gap-2">
                                                <div class="font-semibold text-foreground text-sm hover:underline">
                                                    <a href="#" wire:click.prevent="openEdit({{ $a->id }})">{{ $a->title }}</a>
                                                </div>
                                                
                                                @if($a->status === 'draft')
                                                    <span class="text-[10px] font-semibold text-amber-500 font-mono">— Draf</span>
                                                @elseif($a->status === 'scheduled')
                                                    <span class="text-[10px] font-semibold text-indigo-500 font-mono">— Terjadwal</span>
                                                @elseif($a->status === 'trash')
                                                    <span class="text-[10px] font-semibold text-destructive font-mono">— Sampah</span>
                                                @endif
                                            </div>

                                            <!-- WordPress-Style Hover Menu Actions -->
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2.5 mt-1 text-[11px] text-muted-foreground font-medium">
                                                <button wire:click="openEdit({{ $a->id }})" class="hover:text-primary transition-colors">Sunting</button>
                                                <span class="text-border">|</span>
                                                <button wire:click="openQuickEdit({{ $a->id }})" class="hover:text-primary transition-colors">Edit Cepat</button>
                                                <span class="text-border">|</span>
                                                
                                                @if($a->status === 'trash')
                                                    <button wire:click="restoreArticle({{ $a->id }})" class="text-emerald-500 hover:underline">Pulihkan</button>
                                                    <span class="text-border">|</span>
                                                    <button wire:click="trashArticle({{ $a->id }})" wire:confirm="Hapus berita ini secara permanen dari database?" class="text-destructive hover:underline">Hapus Permanen</button>
                                                @else
                                                    <button wire:click="trashArticle({{ $a->id }})" class="text-destructive hover:underline">Buang</button>
                                                @endif
                                                
                                                <span class="text-border">|</span>
                                                <a href="/api/v1/berita/{{ $a->slug }}" target="_blank" class="hover:text-primary transition-colors">Lihat</a>
                                                <span class="text-border">|</span>
                                                <a href="#" wire:click.prevent="exportCsv" class="hover:text-primary transition-colors">Download csv</a>
                                                <span class="text-border">|</span>
                                                <button wire:click="duplicateArticle({{ $a->id }})" class="hover:text-primary transition-colors">Duplikat</button>
                                                <span class="text-border">|</span>
                                                <button wire:click="clearCacheArticle({{ $a->id }})" class="hover:text-primary transition-colors">Clear Cache</button>
                                            </div>
                                        </td>
                                        
                                        <!-- Author -->
                                        <td class="p-4 align-top text-xs text-muted-foreground">
                                            {{ $a->user->name ?? 'Admin' }}
                                        </td>

                                        <!-- Categories Multi list -->
                                        <td class="p-4 align-top text-xs">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($a->categories as $c)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded border border-border bg-secondary font-medium">{{ $c->name }}</span>
                                                @empty
                                                    <span class="text-muted-foreground text-[10px] italic">Tidak ada</span>
                                                @endforelse
                                            </div>
                                        </td>



                                        <!-- Date & Time -->
                                        <td class="p-4 align-top text-xs text-muted-foreground">
                                            @if($a->status === 'published' && $a->published_at)
                                                <div class="text-foreground">Telah Terbit</div>
                                                <div class="text-[10px] mt-0.5 font-mono">{{ date('Y/m/d \j\a\m H:i', strtotime($a->published_at)) }}</div>
                                            @elseif($a->status === 'scheduled' && $a->published_at)
                                                <div class="text-indigo-500 font-medium">Terjadwal</div>
                                                <div class="text-[10px] mt-0.5 font-mono">{{ date('Y/m/d \j\a\m H:i', strtotime($a->published_at)) }}</div>
                                            @else
                                                <div class="text-amber-500 font-medium">Terakhir Diubah</div>
                                                <div class="text-[10px] mt-0.5 font-mono">{{ date('Y/m/d \j\a\m H:i', strtotime($a->updated_at)) }}</div>
                                            @endif
                                        </td>

                                        <!-- Views Count -->
                                        <td class="p-4 align-top text-right font-mono font-medium text-xs text-foreground">
                                            {{ number_format($a->views_count) }}
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-xs text-muted-foreground">
                                        Tidak ada postingan berita ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($articles->hasPages())
                    <div class="p-4 border-t border-border bg-muted/10">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
