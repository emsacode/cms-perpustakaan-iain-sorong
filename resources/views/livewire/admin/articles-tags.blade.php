<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Tag Berita</h1>
            <p class="text-xs text-muted-foreground mt-1">Kelola penanda kata kunci bebas untuk artikel dan postingan berita.</p>
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
        <!-- Left Side: Add New Tag Form -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-5 space-y-4 h-fit">
            <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2">Tambah Tag Baru</h3>
            
            <form wire:submit.prevent="addTag" class="space-y-4">
                <div class="space-y-1.5">
                    <label for="newTagName" class="text-xs font-semibold text-foreground">Nama Tag</label>
                    <input wire:model="newTagName" type="text" id="newTagName" placeholder="Contoh: skripsi" class="w-full px-3 py-2 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                    @error('newTagName') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="newTagSlug" class="text-xs font-semibold text-foreground">Slug URL (Opsional)</label>
                    <input wire:model="newTagSlug" type="text" id="newTagSlug" placeholder="skripsi" class="w-full px-3 py-2 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                    Tambah Tag Baru
                </button>
            </form>
        </div>

        <!-- Right Side: Tags List Table -->
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
                        @forelse($tags as $tag)
                            <tr class="hover:bg-muted/30 transition-colors">
                                <td class="p-4 font-semibold text-foreground">
                                    {{ $tag->name }}
                                </td>
                                <td class="p-4 font-mono text-xs text-muted-foreground">
                                    {{ $tag->slug }}
                                </td>
                                <td class="p-4 text-right font-mono text-xs text-foreground">
                                    {{ $tag->articles_count }}
                                </td>
                                <td class="p-4 text-center">
                                    <button wire:click="deleteTag({{ $tag->id }})" wire:confirm="Hapus tag ini secara permanen?" class="text-xs text-destructive font-medium hover:underline">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-xs text-muted-foreground">
                                    Belum ada tag yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tags->hasPages())
                <div class="p-4 border-t border-border bg-muted/10">
                    {{ $tags->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
