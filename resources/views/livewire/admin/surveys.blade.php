<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Survei Kepuasan (IKM)</h1>
            <p class="text-xs text-muted-foreground mt-1">Laporan indeks kepuasan masyarakat berdasarkan 9 unsur PermenPAN-RB No.14/2017 secara real-time.</p>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- IKM Score Card -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Nilai Indeks IKM</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500 lucide lucide-award">
                    <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/>
                    <circle cx="12" cy="8" r="6"/>
                </svg>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-extrabold text-foreground tracking-tight font-mono">
                    {{ number_format($ikmScore, 2) }}
                </div>
                <div class="mt-2 text-xs font-semibold">
                    Kinerja Layanan: 
                    @if($ikmScore >= 88.31)
                        <span class="text-emerald-500 uppercase tracking-wide font-bold">{{ $ikmGrade }}</span>
                    @elseif($ikmScore >= 76.61)
                        <span class="text-indigo-500 uppercase tracking-wide font-bold">{{ $ikmGrade }}</span>
                    @elseif($ikmScore >= 65.00)
                        <span class="text-amber-500 uppercase tracking-wide font-bold">{{ $ikmGrade }}</span>
                    @else
                        <span class="text-destructive uppercase tracking-wide font-bold">{{ $ikmGrade }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Total Respondents Card -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Total Responden</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500 lucide lucide-users-2">
                    <path d="M14 19a6 6 0 0 0-12 0"/>
                    <circle cx="8" cy="9" r="4"/>
                    <path d="M22 19a6 6 0 0 0-6-6 4 4 0 1 0 0-8"/>
                </svg>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-extrabold text-foreground tracking-tight font-mono">
                    {{ number_format($totalCount) }}
                </div>
                <div class="text-xs text-muted-foreground mt-2">Pemustaka aktif berpartisipasi</div>
            </div>
        </div>

        <!-- AI Recommendation Card -->
        <div class="bg-card text-card-foreground border border-border rounded-lg p-6 shadow-sm bg-gradient-to-br from-indigo-500/5 via-transparent to-pink-500/5 flex flex-col justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500">
                    <circle cx="12" cy="12" r="10"/><path d="m9.09 9 1-1a3 3 0 0 1 5.3 3c0 1.55-.5 2.1-1.39 3A2 2 0 0 0 13 15v1"/>
                </svg>
                <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">AI Kesimpulan</h3>
            </div>
            <div class="text-[11px] leading-relaxed text-muted-foreground mt-4">
                Nilai terendah terdeteksi pada unsur <strong>Sarana & Prasarana</strong>. Direkomendasikan untuk meninjau sistem pendingin udara (AC) di ruang baca utama untuk meningkatkan kenyamanan pengunjung.
            </div>
        </div>
    </div>

    <!-- Rating Per Unsur (9 Elements) -->
    <div class="bg-card text-card-foreground border border-border rounded-lg p-6 shadow-sm">
        <h2 class="text-sm font-semibold text-foreground uppercase tracking-wider border-b border-border pb-3 mb-5">Skor Rata-Rata per Unsur Layanan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            @foreach($averages as $i => $avg)
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-medium text-foreground truncate max-w-[280px]" title="U{{ $i }}: {{ $labels[$i] }}">
                            U{{ $i }}. {{ $labels[$i] }}
                        </span>
                        <span class="font-bold text-indigo-500 font-mono">{{ number_format($avg, 2) }} / 5.00</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    @php 
                        $percent = ($avg / 5) * 100;
                    @endphp
                    <div class="w-full bg-secondary h-2 rounded-full overflow-hidden border border-border">
                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Feedback & Comments List -->
    <div class="space-y-4">
        <h2 class="text-sm font-semibold text-foreground uppercase tracking-wider">Masukan & Kritik Pemustaka</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($surveys as $item)
                @if(!empty($item->feedback))
                    <div class="bg-card text-card-foreground border border-border rounded-lg p-5 shadow-sm space-y-3 relative">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-muted-foreground font-mono">
                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                            </span>
                            
                            <!-- Average score card for individual submission -->
                            @php 
                                $subtotal = 0;
                                for($k = 1; $k <= 9; $k++) {
                                    $p = "q$k";
                                    $subtotal += $item->$p;
                                }
                                $subavg = round($subtotal / 9, 2);
                            @endphp
                            <span class="text-[10px] font-mono font-semibold px-2 py-0.5 bg-secondary rounded border border-border text-indigo-500">
                                Rating: {{ $subavg }}
                            </span>
                        </div>
                        
                        <p class="text-xs text-foreground italic leading-relaxed">
                            "{{ $item->feedback }}"
                        </p>

                        <!-- Delete Button -->
                        <button wire:click="deleteSurvey({{ $item->id }})" 
                                wire:confirm="Apakah Anda yakin ingin menghapus data survei ini?"
                                class="absolute right-3 bottom-3 p-1 rounded hover:bg-muted text-muted-foreground hover:text-destructive transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                        </button>
                    </div>
                @endif
            @empty
                <div class="col-span-2 p-8 text-center text-xs text-muted-foreground border border-border border-dashed rounded-lg">
                    Belum ada ulasan kritik atau saran pemustaka.
                </div>
            @endforelse
        </div>

        @if($surveys->hasPages())
            <div class="mt-4">
                {{ $surveys->links() }}
            </div>
        @endif
    </div>
</div>
