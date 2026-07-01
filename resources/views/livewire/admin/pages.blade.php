<div class="space-y-6">
    @if($isEditing)
        <!-- Full Edit / Create New Page Form -->
        <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm">
            <div class="flex items-center justify-between border-b border-border p-6">
                <div>
                    <h2 class="text-lg font-bold tracking-tight text-foreground">
                        {{ $editingId ? 'Sunting Halaman' : 'Tambah Halaman Baru' }}
                    </h2>
                    <p class="text-xs text-muted-foreground mt-0.5">Tulis, desain, dan sesuaikan metadata halaman statis Anda.</p>
                </div>
                <button wire:click="closeEdit" class="text-xs font-semibold px-3 py-1.5 bg-secondary text-secondary-foreground rounded-lg border border-border hover:bg-accent transition-colors">
                    Kembali ke Daftar
                </button>
            </div>

            <form wire:submit.prevent="saveEdit" class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Main Form Content (2 Columns) -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="space-y-2">
                            <label for="editTitle" class="text-xs font-semibold text-foreground">Judul Halaman</label>
                            <input wire:model="editTitle" 
                                   type="text" 
                                   id="editTitle" 
                                   placeholder="Tambahkan judul..."
                                   class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                            @error('editTitle') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="editSlug" class="text-xs font-semibold text-foreground">Slug URL</label>
                            <input wire:model="editSlug" 
                                   type="text" 
                                   id="editSlug" 
                                   placeholder="url-halaman-statis"
                                   class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                        </div>

                        <div class="space-y-2" wire:ignore>
                            <label for="editContent" class="text-xs font-semibold text-foreground">Isi Konten (HTML / Rich Text)</label>
                            <textarea wire:model="editContent" 
                                      id="editContent" 
                                      rows="15" 
                                      placeholder="Tulis isi halaman di sini..."
                                      class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm font-mono text-foreground focus:outline-none focus:ring-1 focus:ring-ring resize-y min-h-[300px]"></textarea>
                            @error('editContent') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Right Sidebar Settings (1 Column) -->
                    <div class="bg-muted/40 border border-border rounded-lg p-5 space-y-5 h-fit">
                        <h3 class="text-xs font-bold text-foreground uppercase tracking-wider border-b border-border pb-2">Pengaturan Halaman</h3>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label for="editStatus" class="text-xs font-semibold text-muted-foreground">Status Publikasi</label>
                            <select wire:model="editStatus" id="editStatus" class="w-full px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                                <option value="published">Telah Terbit</option>
                                <option value="draft">Draf</option>
                                <option value="scheduled">Terjadwal</option>
                                <option value="trash">Sampah</option>
                            </select>
                        </div>

                        <!-- Views Count (Optional) -->
                        <div class="space-y-2">
                            <label for="editViews" class="text-xs font-semibold text-muted-foreground">Simulasi Views</label>
                            <input wire:model="editViewsCount" type="number" id="editViews" class="w-full px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                        </div>

                        <div class="pt-4 border-t border-border flex gap-3">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                                Simpan Halaman
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @else
        <!-- Index Halaman Utama (Daftar Halaman) -->
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">Halaman</h1>
                    <p class="text-xs text-muted-foreground mt-1">Kelola seluruh halaman statis portal, sesuaikan hirarki, status optimasi SEO, dan publikasi.</p>
                </div>
                <button wire:click="openEdit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                    Tambah Halaman Baru
                </button>
            </div>

            <!-- Session Message -->
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

                <!-- Filters & Search -->
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Date Filter -->
                    <select wire:model.live="dateFilter" class="px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                        <option value="">Semua Tanggal</option>
                        @foreach($availableDates as $date)
                            <option value="{{ $date }}">{{ date('F Y', strtotime($date . '-01')) }}</option>
                        @endforeach
                    </select>



                    <!-- Search -->
                    <div class="relative min-w-[200px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground lucide lucide-search">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari halaman..." class="w-full pl-8 pr-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                    </div>
                </div>
            </div>

            <!-- Tabular Data Table -->
            <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                                <th class="p-4 w-10">
                                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-border text-primary focus:ring-ring">
                                </th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Judul</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Penulis</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tanggal & Status</th>

                                <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Views</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($pages as $p)
                                @if($quickEditId === $p->id)
                                    <!-- Inline Quick Edit Row -->
                                    <tr class="bg-muted/50 border-y-2 border-indigo-500/20">
                                        <td colspan="7" class="p-5">
                                            <div class="space-y-4">
                                                <h4 class="text-xs font-bold text-foreground uppercase tracking-wider">Edit Cepat</h4>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Judul</label>
                                                        <input wire:model="quickTitle" type="text" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                    </div>
                                                    
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Slug</label>
                                                        <input wire:model="quickSlug" type="text" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                    </div>

                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Tanggal</label>
                                                        <input wire:model="quickDate" type="datetime-local" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                    </div>

                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-semibold text-muted-foreground uppercase">Status</label>
                                                        <select wire:model="quickStatus" class="w-full px-2.5 py-1 bg-background border border-border rounded text-xs text-foreground focus:outline-none">
                                                            <option value="published">Telah Terbit</option>
                                                            <option value="draft">Draf</option>
                                                            <option value="scheduled">Terjadwal</option>
                                                            <option value="trash">Sampah</option>
                                                        </select>
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
                                    <!-- Standard Row with Contextual Hover Actions -->
                                    <tr class="hover:bg-muted/30 transition-colors group">
                                        <td class="p-4 align-top">
                                            <input type="checkbox" wire:model.live="selectedPages" value="{{ $p->id }}" class="rounded border-border text-primary focus:ring-ring">
                                        </td>
                                        <td class="p-4 align-top">
                                            <div class="flex items-center gap-2">
                                                <div class="font-semibold text-foreground text-sm hover:underline">
                                                    <a href="#" wire:click.prevent="openEdit({{ $p->id }})">{{ $p->title }}</a>
                                                </div>
                                                
                                                @if($p->status === 'draft')
                                                    <span class="text-[10px] font-semibold text-amber-500 font-mono">— Draf</span>
                                                @elseif($p->status === 'scheduled')
                                                    <span class="text-[10px] font-semibold text-indigo-500 font-mono">— Terjadwal</span>
                                                @elseif($p->status === 'trash')
                                                    <span class="text-[10px] font-semibold text-destructive font-mono">— Sampah</span>
                                                @endif
                                            </div>

                                            <!-- Contextual Hover Action Bar (WordPress-style) -->
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2.5 mt-1 text-[11px] text-muted-foreground font-medium">
                                                <button wire:click="openEdit({{ $p->id }})" class="hover:text-primary transition-colors">Sunting</button>
                                                <span class="text-border">|</span>
                                                <button wire:click="openQuickEdit({{ $p->id }})" class="hover:text-primary transition-colors">Edit Cepat</button>
                                                <span class="text-border">|</span>
                                                
                                                @if($p->status === 'trash')
                                                    <button wire:click="restorePage({{ $p->id }})" class="text-emerald-500 hover:underline">Pulihkan</button>
                                                    <span class="text-border">|</span>
                                                    <button wire:click="trashPage({{ $p->id }})" wire:confirm="Apakah Anda yakin ingin menghapus halaman ini secara permanen?" class="text-destructive hover:underline">Hapus Permanen</button>
                                                @else
                                                    <button wire:click="trashPage({{ $p->id }})" class="text-destructive hover:underline">Buang</button>
                                                @endif
                                                
                                                <span class="text-border">|</span>
                                                <a href="/api/v1/pages/{{ $p->slug }}" target="_blank" class="hover:text-primary transition-colors">Lihat</a>
                                                <span class="text-border">|</span>
                                                <button wire:click="duplicatePage({{ $p->id }})" class="hover:text-primary transition-colors">Duplikat</button>
                                                <span class="text-border">|</span>
                                                <button wire:click="clearCachePage({{ $p->id }})" class="hover:text-primary transition-colors">Bersihkan Cache</button>
                                            </div>
                                        </td>
                                        
                                        <!-- Author -->
                                        <td class="p-4 align-top text-xs text-muted-foreground">
                                            {{ $p->author->name ?? 'Admin' }}
                                        </td>

                                        <!-- Date & Time -->
                                        <td class="p-4 align-top text-xs text-muted-foreground">
                                            @if($p->status === 'published' && $p->published_at)
                                                <div class="text-foreground">Telah Terbit</div>
                                                <div class="text-[10px] mt-0.5 font-mono">{{ date('Y/m/d \j\a\m H:i', strtotime($p->published_at)) }}</div>
                                            @elseif($p->status === 'scheduled' && $p->published_at)
                                                <div class="text-indigo-500 font-medium">Terjadwal</div>
                                                <div class="text-[10px] mt-0.5 font-mono">{{ date('Y/m/d \j\a\m H:i', strtotime($p->published_at)) }}</div>
                                            @else
                                                <div class="text-amber-500 font-medium">Terakhir Diubah</div>
                                                <div class="text-[10px] mt-0.5 font-mono">{{ date('Y/m/d \j\a\m H:i', strtotime($p->updated_at)) }}</div>
                                            @endif
                                        </td>



                                        <!-- Views Count -->
                                        <td class="p-4 align-top text-right font-mono font-medium text-xs text-foreground">
                                            {{ number_format($p->views_count) }}
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-xs text-muted-foreground">
                                        Tidak ada data halaman statis ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pages->hasPages())
                    <div class="p-4 border-t border-border bg-muted/10">
                        {{ $pages->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
