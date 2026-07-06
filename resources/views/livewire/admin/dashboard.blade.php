<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col gap-1.5 border-b border-border pb-5">
        <h1 class="text-3xl font-bold tracking-tight text-foreground">Dashboard</h1>
        <p class="text-xs text-muted-foreground">Analisis ringkasan statistik operasional, layanan interaktif, dan asisten kecerdasan buatan UPT Perpustakaan.</p>
    </div>

    <!-- Alert Message -->
    @if (session()->has('message'))
        <div class="p-3 text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-lg flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle-2">
                <circle cx="12" cy="12" r="10"/><path d="m9 11 3 3 6-6"/>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Stats Grid Grid (4 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat Card 1: Views -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm flex items-center justify-between transition-all hover:border-border/80">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-muted-foreground tracking-wider uppercase">Views Berita</span>
                <div class="text-2xl font-bold text-foreground font-mono">
                    <x-counting-number :value="$totalViews" />
                </div>
                <p class="text-[10px] text-muted-foreground">Dari {{ $totalArticles }} artikel terbit</p>
            </div>
            <div class="p-2.5 bg-indigo-500/10 text-indigo-500 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
        </div>

        <!-- Stat Card 2: Reservasi Pending -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm flex items-center justify-between transition-all hover:border-border/80">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-muted-foreground tracking-wider uppercase">Reservasi Pending</span>
                <div class="text-2xl font-bold font-mono {{ $pendingReservations > 0 ? 'text-amber-500' : 'text-foreground' }}">
                    <x-counting-number :value="$pendingReservations" />
                </div>
                <p class="text-[10px] text-muted-foreground">Peminjaman ruang diskusi</p>
            </div>
            <div class="p-2.5 {{ $pendingReservations > 0 ? 'bg-amber-500/10 text-amber-500' : 'bg-muted text-muted-foreground' }} rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
            </div>
        </div>

        <!-- Stat Card 3: Bebas Pustaka Pending -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm flex items-center justify-between transition-all hover:border-border/80">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-muted-foreground tracking-wider uppercase">Bebas Pustaka</span>
                <div class="text-2xl font-bold font-mono {{ $pendingClearances > 0 ? 'text-indigo-500' : 'text-foreground' }}">
                    <x-counting-number :value="$pendingClearances" />
                </div>
                <p class="text-[10px] text-muted-foreground">Persetujuan yudisium</p>
            </div>
            <div class="p-2.5 {{ $pendingClearances > 0 ? 'bg-indigo-500/10 text-indigo-500' : 'bg-muted text-muted-foreground' }} rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
                    <path d="M6 18.8v-4L2 13v6a1 1 0 0 0 1 1h3Z"/>
                    <path d="M12 14.5v6"/>
                </svg>
            </div>
        </div>

        <!-- Stat Card 4: IKM -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm flex items-center justify-between transition-all hover:border-border/80">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-muted-foreground tracking-wider uppercase">Indeks IKM</span>
                <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                    {{ $averageIkm }}
                </div>
                <p class="text-[10px] text-muted-foreground">
                    @if($averageIkm >= 81.25)
                        Sangat Baik (Mutu A)
                    @else
                        Baik (Mutu B)
                    @endif
                </p>
            </div>
            <div class="p-2.5 bg-emerald-500/10 text-emerald-500 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Area: Unified Queue Inbox (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-card text-card-foreground border border-border rounded-lg p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-border pb-3">
                    <div>
                        <h2 class="text-sm font-bold text-foreground uppercase tracking-wider">Antrean Kerja Terpadu (Operations Inbox)</h2>
                        <p class="text-[10px] text-muted-foreground mt-0.5">Tinjau dan proses semua pengajuan yang masuk secara real-time dari beranda.</p>
                    </div>
                    <span class="text-[10px] font-mono font-semibold px-2 py-0.5 bg-secondary text-secondary-foreground rounded border border-border">
                        {{ count($inboxQueue) }} Antrean
                    </span>
                </div>
                
                <!-- Queue Items -->
                <div class="space-y-3">
                    @forelse($inboxQueue as $item)
                        <div class="p-4 border border-border rounded-lg flex flex-col md:flex-row md:items-center justify-between gap-4 bg-muted/20 hover:bg-muted/40 transition-colors">
                            <div class="space-y-1 text-left">
                                <div class="flex items-center gap-2">
                                    @if($item['type'] === 'reservation')
                                        <span class="px-1.5 py-0.5 bg-amber-500/10 text-amber-600 rounded text-[9px] font-bold">RESERVASI</span>
                                    @elseif($item['type'] === 'clearance')
                                        <span class="px-1.5 py-0.5 bg-indigo-500/10 text-indigo-600 rounded text-[9px] font-bold">BEBAS PUSTAKA</span>
                                    @else
                                        <span class="px-1.5 py-0.5 bg-sky-500/10 text-sky-600 rounded text-[9px] font-bold">ANGGOTA</span>
                                    @endif
                                    <h4 class="text-xs font-bold text-foreground">{{ $item['title'] }}</h4>
                                </div>
                                <p class="text-xs font-semibold text-foreground mt-1">{{ $item['user'] }}</p>
                                <p class="text-[11px] text-muted-foreground font-mono">{{ $item['meta'] }}</p>
                                
                                @if(!empty($item['link_surat']))
                                    <div class="mt-1.5">
                                        <a href="{{ $item['link_surat'] }}" target="_blank" class="inline-flex items-center text-[10px] text-primary hover:underline font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                            Surat Permohonan (PDF)
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Fast Action Buttons -->
                            <div class="flex items-center gap-1.5 shrink-0 justify-end md:justify-start">
                                @if($item['type'] === 'reservation')
                                    <button wire:click="approveReservation({{ $item['id'] }})" class="px-2.5 py-1 bg-emerald-500 text-white rounded text-[11px] font-semibold hover:bg-emerald-600 transition-colors">
                                        Setujui
                                    </button>
                                    <button wire:click="rejectReservation({{ $item['id'] }})" class="px-2.5 py-1 bg-destructive text-white rounded text-[11px] font-semibold hover:bg-destructive/95 transition-colors">
                                        Tolak
                                    </button>
                                @else
                                    <button wire:click="approveClearance({{ $item['id'] }})" class="px-2.5 py-1 bg-emerald-500 text-white rounded text-[11px] font-semibold hover:bg-emerald-600 transition-colors">
                                        Setujui
                                    </button>
                                    <button wire:click="rejectClearance({{ $item['id'] }})" class="px-2.5 py-1 bg-destructive text-white rounded text-[11px] font-semibold hover:bg-destructive/95 transition-colors">
                                        Tolak
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 flex flex-col items-center justify-center text-center space-y-3 bg-muted/10 border border-dashed border-border rounded-lg">
                            <div class="w-12 h-12 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center border border-emerald-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle-2">
                                    <path d="m9 11 3 3 6-6"/><circle cx="12" cy="12" r="10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-foreground">Semua Pekerjaan Selesai!</h3>
                                <p class="text-[11px] text-muted-foreground mt-0.5">Tidak ada pengajuan pending yang memerlukan tindakan Anda saat ini.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar (Right 1 col) -->
        <div class="space-y-6">
            <!-- Compact AI Assistant Chat -->
            <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-border pb-2">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">Asisten AI Analitik</h3>
                    <span class="text-[9px] font-mono text-muted-foreground">Online</span>
                </div>
                
                <div class="max-h-60 overflow-y-auto space-y-2 text-xs">
                    @foreach($messages as $msg)
                        <div class="p-2 rounded-md {{ $msg['role'] === 'assistant' ? 'bg-muted/50 text-foreground' : 'bg-primary/5 text-foreground font-medium text-right' }}">
                            {!! $msg['content'] !!}
                        </div>
                    @endforeach
                </div>

                <form wire:submit.prevent="sendMessage" class="relative">
                    <input wire:model="prompt" type="text" placeholder="Tanya asisten AI..." class="w-full px-3 py-1.5 bg-background border border-border rounded-md text-xs focus:outline-none focus:ring-1 focus:ring-ring pr-10">
                    <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 p-1 text-primary hover:text-primary/85">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </button>
                </form>
            </div>

            <!-- Popular Articles Card -->
            <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-border pb-2">
                    <h2 class="text-xs font-bold text-foreground uppercase tracking-wider">Artikel Terpopuler</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-500">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                
                <div class="space-y-3">
                    @forelse($popularArticles as $article)
                        <div class="flex items-start justify-between gap-2 text-left text-xs">
                            <div class="space-y-0.5">
                                <h4 class="font-semibold text-foreground line-clamp-1 hover:underline">
                                    <a href="#">{{ $article->title }}</a>
                                </h4>
                                <p class="text-[9px] text-muted-foreground">{{ $article->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-[9px] font-mono px-1 bg-secondary text-secondary-foreground rounded border border-border shrink-0">
                                {{ number_format($article->views_count) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-[11px] text-muted-foreground py-2">Belum ada artikel populer.</div>
                    @endforelse
                </div>
            </div>

            <!-- AI Snapshot Card -->
            <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm space-y-3 bg-gradient-to-br from-indigo-500/5 via-transparent to-pink-500/5">
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-500">
                        <circle cx="12" cy="12" r="10"/><path d="m9.09 9 1-1a3 3 0 0 1 5.3 3c0 1.55-.5 2.1-1.39 3A2 2 0 0 0 13 15v1"/>
                    </svg>
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">Metrik Rekomendasi</h3>
                </div>
                <p class="text-[11px] leading-relaxed text-muted-foreground text-left">
                    Pantau dasbor <a href="{{ route('admin.analytics') }}" class="text-primary hover:underline font-bold">Analitik Akademik</a> secara berkala untuk tren riset Scopus & SINTA institusi dosen pengajar.
                </p>
            </div>
        </div>
    </div>
</div>
