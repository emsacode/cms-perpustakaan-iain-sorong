<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Kategori Berita</h1>
            <p class="text-xs text-muted-foreground mt-1">Atur pengelompokan hierarki utama untuk berita dan pengumuman.</p>
        </div>
        <a href="{{ route('admin.articles') }}" class="text-xs font-semibold px-3 py-1.5 bg-secondary text-secondary-foreground rounded-lg border border-border hover:bg-accent transition-colors">
            Kembali ke Semua Pos
        </a>
    </div>

    <!-- Flash message -->
    @if (session()->has('message'))
        <div class="p-3 text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left Side: Add New Category Form -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-5 space-y-4 h-fit">
            <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2">Tambah Kategori Baru</h3>
            
            <form wire:submit.prevent="addCategory" class="space-y-4">
                <div class="space-y-1.5">
                    <label for="newCategoryName" class="text-xs font-semibold text-foreground">Nama Kategori</label>
                    <input wire:model="newCategoryName" type="text" id="newCategoryName" placeholder="Contoh: News & Update" class="w-full px-3 py-2 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                    @error('newCategoryName') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="newCategorySlug" class="text-xs font-semibold text-foreground">Slug URL (Opsional)</label>
                    <input wire:model="newCategorySlug" type="text" id="newCategorySlug" placeholder="news-update" class="w-full px-3 py-2 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                    <p class="text-[9px] text-muted-foreground">“slug” adalah versi ramah-URL dari nama kategori. Biasanya ditulis dalam huruf kecil dan hanya berisi huruf, angka, serta tanda hubung.</p>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                    Tambah Kategori Baru
                </button>
            </form>
        </div>

        <!-- Right Side: Categories List Table -->
        <div class="md:col-span-2 bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider">Nama</th>
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider">Slug</th>
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Jumlah Pos</th>
                            <th class="p-4 text-xs font-semibold uppercase tracking-wider text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-muted/30 transition-colors">
                                <td class="p-4 font-semibold text-foreground">
                                    {{ $cat->name }}
                                </td>
                                <td class="p-4 font-mono text-xs text-muted-foreground">
                                    {{ $cat->slug }}
                                </td>
                                <td class="p-4 text-right font-mono text-xs text-foreground">
                                    {{ $cat->articles_count }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($cat->slug !== 'news-update')
                                        <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Hapus kategori ini? Berita di dalamnya tidak akan dihapus." class="text-xs text-destructive font-medium hover:underline">
                                            Hapus
                                        </button>
                                    @else
                                        <span class="text-xs text-muted-foreground italic">Bawaan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-xs text-muted-foreground">
                                    Belum ada kategori yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-border bg-muted/10">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
