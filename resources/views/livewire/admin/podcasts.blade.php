<div class="space-y-6">
    @if($isEditing)
        <!-- Create / Edit Form -->
        <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm">
            <div class="flex items-center justify-between border-b border-border p-6">
                <div>
                    <h2 class="text-lg font-bold tracking-tight text-foreground">
                        {{ $editingId ? 'Sunting Episode Podcast' : 'Tambah Episode Baru' }}
                    </h2>
                    <p class="text-xs text-muted-foreground mt-0.5">Kelola klip audio/video bincang literasi perpustakaan.</p>
                </div>
                <button wire:click="closeEdit" class="text-xs font-semibold px-3 py-1.5 bg-secondary text-secondary-foreground rounded-lg border border-border hover:bg-accent transition-colors">
                    Kembali ke Daftar
                </button>
            </div>

            <form wire:submit.prevent="saveEdit" class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="editTitle" class="text-xs font-semibold text-foreground">Judul Episode</label>
                        <input wire:model="editTitle" type="text" id="editTitle" placeholder="Bincang Literasi #..." class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                        @error('editTitle') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="editSlug" class="text-xs font-semibold text-foreground">Slug URL (Opsional)</label>
                        <input wire:model="editSlug" type="text" id="editSlug" placeholder="bincang-literasi-x" class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label for="editAudioUrl" class="text-xs font-semibold text-foreground">Pranala Spotify (Audio)</label>
                        <input wire:model="editAudioUrl" type="url" id="editAudioUrl" placeholder="https://open.spotify.com/episode/..." class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label for="editVideoUrl" class="text-xs font-semibold text-foreground">Pranala YouTube (Video)</label>
                        <input wire:model="editVideoUrl" type="url" id="editVideoUrl" placeholder="https://youtube.com/watch?v=..." class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label for="editDuration" class="text-xs font-semibold text-foreground">Durasi</label>
                        <input wire:model="editDuration" type="text" id="editDuration" placeholder="Contoh: 24:15" class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none">
                        @error('editDuration') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="editDescription" class="text-xs font-semibold text-foreground">Ringkasan Episode / Transkrip</label>
                    <textarea wire:model="editDescription" id="editDescription" rows="8" placeholder="Tulis sinopsis narasumber dan transkrip singkat..." class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none resize-y min-h-[150px]"></textarea>
                    @error('editDescription') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="editPublishedAt" class="text-xs font-semibold text-foreground">Tanggal Rilis</label>
                    <input wire:model="editPublishedAt" type="datetime-local" id="editPublishedAt" class="w-full px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none">
                </div>

                <div class="pt-4 border-t border-border flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                        Simpan Episode
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- Index Table -->
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">Podcast Literasi</h1>
                    <p class="text-xs text-muted-foreground mt-1">Daftar rekaman audio/video sosialisasi dan bimbingan literasi pemustaka UPT Perpustakaan.</p>
                </div>
                <button wire:click="openEdit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
                    Tambah Episode Baru
                </button>
            </div>

            <!-- Alert -->
            @if (session()->has('message'))
                <div class="p-3 text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Search controls -->
            <div class="flex items-center justify-end">
                <div class="relative min-w-[240px]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground lucide lucide-search">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul..." class="w-full pl-8 pr-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground placeholder:text-muted-foreground focus:outline-none">
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Judul Episode</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Durasi</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Klip Spotify</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Klip YouTube</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tanggal Terbit</th>
                                <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($podcasts as $pod)
                                <tr class="hover:bg-muted/30 transition-colors">
                                    <td class="p-4">
                                        <div class="font-semibold text-foreground text-sm">{{ $pod->title }}</div>
                                        <div class="text-xs text-muted-foreground line-clamp-1 mt-0.5 max-w-[400px]">{{ $pod->description }}</div>
                                    </td>
                                    <td class="p-4 font-mono text-xs text-muted-foreground">
                                        {{ $pod->duration }}
                                    </td>
                                    <td class="p-4 text-xs">
                                        @if($pod->audio_url)
                                            <a href="{{ $pod->audio_url }}" target="_blank" class="inline-flex items-center text-primary hover:underline font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 lucide lucide-music"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                                                Buka Spotify
                                            </a>
                                        @else
                                            <span class="text-muted-foreground italic text-[11px]">—</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs">
                                        @if($pod->video_url)
                                            <a href="{{ $pod->video_url }}" target="_blank" class="inline-flex items-center text-rose-500 hover:underline font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 lucide lucide-play"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                                Buka YouTube
                                            </a>
                                        @else
                                            <span class="text-muted-foreground italic text-[11px]">—</span>
                                        @endif
                                    </td>
                                    <td class="p-4 font-mono text-xs text-muted-foreground">
                                        {{ $pod->published_at ? date('Y/m/d H:i', strtotime($pod->published_at)) : '-' }}
                                    </td>
                                    <td class="p-4 text-right space-x-1.5">
                                        <button wire:click="openEdit({{ $pod->id }})" class="text-xs text-primary font-medium hover:underline">
                                            Sunting
                                        </button>
                                        <button wire:click="deletePodcast({{ $pod->id }})" wire:confirm="Hapus episode podcast ini?" class="text-xs text-destructive font-medium hover:underline">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-xs text-muted-foreground">
                                        Tidak ada episode podcast ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($podcasts->hasPages())
                    <div class="p-4 border-t border-border bg-muted/10">
                        {{ $podcasts->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
