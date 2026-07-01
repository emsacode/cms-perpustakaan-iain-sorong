<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Dasbor Analitik & Statistik Akademik</h1>
            <p class="text-xs text-muted-foreground mt-1">Pemantauan metrik publikasi ilmiah global (Scopus), nasional (Sinta), dan statistik operasional perpustakaan.</p>
        </div>
    </div>

    <!-- Tabs selector -->
    <div class="flex items-center gap-2 border-b border-border pb-1">
        <button wire:click="changeTab('visitors')" class="text-sm font-semibold px-4 py-2 rounded-t-lg border-b-2 transition-colors {{ $activeTab === 'visitors' ? 'border-primary text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
            Statistik Pengunjung
        </button>
        <button wire:click="changeTab('scopus')" class="text-sm font-semibold px-4 py-2 rounded-t-lg border-b-2 transition-colors {{ $activeTab === 'scopus' ? 'border-primary text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
            Publikasi Scopus (Global)
        </button>
        <button wire:click="changeTab('sinta')" class="text-sm font-semibold px-4 py-2 rounded-t-lg border-b-2 transition-colors {{ $activeTab === 'sinta' ? 'border-primary text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground' }}">
            Publikasi SINTA (Nasional)
        </button>
    </div>

    <!-- Tab Contents -->
    @if($activeTab === 'visitors')
        <!-- TAB 1: Visitor & Operations Stats -->
        <div class="space-y-6">
            <!-- Metric Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm">
                    <div class="text-xs font-semibold text-muted-foreground uppercase">Pengunjung Bulan Ini</div>
                    <div class="text-2xl font-bold mt-1 text-foreground font-mono">{{ number_format($visitorStats['total_visitors_this_month']) }}</div>
                    <div class="text-[10px] text-emerald-500 font-semibold mt-1">▲ 14.2% dibanding bulan lalu</div>
                </div>

                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm">
                    <div class="text-xs font-semibold text-muted-foreground uppercase">Sirkulasi Peminjaman</div>
                    <div class="text-2xl font-bold mt-1 text-foreground font-mono">{{ number_format($visitorStats['total_borrowings_this_month']) }}</div>
                    <div class="text-[10px] text-emerald-500 font-semibold mt-1">▲ 8.7% dibanding bulan lalu</div>
                </div>

                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm">
                    <div class="text-xs font-semibold text-muted-foreground uppercase">Anggota Aktif</div>
                    <div class="text-2xl font-bold mt-1 text-foreground font-mono">{{ number_format($visitorStats['active_members']) }}</div>
                    <div class="text-[10px] text-muted-foreground mt-1">94% mahasiswa terdaftar</div>
                </div>

                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm">
                    <div class="text-xs font-semibold text-muted-foreground uppercase">Total Koleksi Buku</div>
                    <div class="text-2xl font-bold mt-1 text-foreground font-mono">{{ number_format($visitorStats['total_books']) }}</div>
                    <div class="text-[10px] text-muted-foreground mt-1">Terdiri dari cetak & digital</div>
                </div>
            </div>

            <!-- Visualization Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Bar chart visitors (2 Columns) -->
                <div class="lg:col-span-2 bg-card text-card-foreground border border-border rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2 mb-4">Tren Kunjungan Bulanan (Semester Ini)</h3>
                    
                    <!-- SVG Bar Chart -->
                    <div class="h-64 flex items-end justify-between gap-4 pt-6 px-4">
                        @foreach($visitorStats['visitors_chart'] as $chart)
                            @php
                                $height = ($chart['value'] / 5000) * 100;
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-2 group">
                                <div class="w-full bg-primary/20 group-hover:bg-primary/30 transition-all rounded-t-sm relative flex flex-col justify-end" style="height: 100%;">
                                    <div class="bg-primary group-hover:bg-primary/95 transition-all rounded-t-sm relative" style="height: {{ $height }}%;">
                                        <span class="opacity-0 group-hover:opacity-100 transition-opacity absolute -top-8 left-1/2 -translate-x-1/2 bg-popover text-popover-foreground text-[10px] font-semibold px-1.5 py-0.5 rounded border border-border shadow-sm font-mono whitespace-nowrap">{{ $chart['value'] }}</span>
                                    </div>
                                </div>
                                <span class="text-xs text-muted-foreground font-semibold">{{ $chart['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Demographics (1 Column) -->
                <div class="bg-card text-card-foreground border border-border rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2 mb-4">Demografi Anggota</h3>
                    <div class="space-y-4">
                        @foreach($visitorStats['demographics'] as $dem)
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-medium text-foreground">
                                    <span>{{ $dem['label'] }}</span>
                                    <span class="font-mono text-muted-foreground">{{ number_format($dem['value']) }} ({{ $dem['percentage'] }}%)</span>
                                </div>
                                <div class="w-full bg-muted h-2 rounded-full overflow-hidden">
                                    <div class="bg-primary h-full rounded-full" style="width: {{ $dem['percentage'] }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @elseif($activeTab === 'scopus')
        <!-- TAB 2: Scopus Global Publications -->
        <div class="space-y-6">
            <!-- Scopus Metric Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-muted-foreground uppercase">Dokumen Terindeks Scopus</div>
                        <div class="text-3xl font-bold mt-1 text-foreground font-mono">{{ $scopusStats['total_publications'] }}</div>
                    </div>
                    <span class="p-2.5 bg-indigo-500/10 text-indigo-500 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
                    </span>
                </div>

                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-muted-foreground uppercase">Total Sitasi Scopus</div>
                        <div class="text-3xl font-bold mt-1 text-foreground font-mono">{{ $scopusStats['total_citations'] }}</div>
                    </div>
                    <span class="p-2.5 bg-emerald-500/10 text-emerald-500 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </span>
                </div>

                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-muted-foreground uppercase">Institusi H-Index</div>
                        <div class="text-3xl font-bold mt-1 text-foreground font-mono">{{ $scopusStats['h_index'] }}</div>
                    </div>
                    <span class="p-2.5 bg-sky-500/10 text-sky-500 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </span>
                </div>
            </div>

            <!-- Scopus Subjects & Authors -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Subject Areas -->
                <div class="bg-card text-card-foreground border border-border rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2 mb-4">Distribusi Bidang Riset Scopus (Subject Area)</h3>
                    
                    <div class="space-y-4">
                        @foreach($scopusStats['subject_areas'] as $sub)
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-medium text-foreground">
                                    <span>{{ $sub['subject'] }}</span>
                                    <span class="font-mono text-muted-foreground font-bold">{{ $sub['count'] }} dokumen</span>
                                </div>
                                <div class="w-full bg-muted h-2 rounded-full overflow-hidden">
                                    <div class="{{ $sub['color'] }} h-full rounded-full" style="width: {{ ($sub['count'] / 34) * 100 }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Productive Authors -->
                <div class="bg-card text-card-foreground border border-border rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2 mb-4">Penulis Paling Produktif terindeks Scopus</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="border-b border-border text-muted-foreground font-bold">
                                    <th class="pb-2">Nama Peneliti</th>
                                    <th class="pb-2 text-right">Dokumen</th>
                                    <th class="pb-2 text-right">Sitasi</th>
                                    <th class="pb-2 text-right">Scopus ID</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($scopusStats['productive_authors'] as $aut)
                                    <tr class="hover:bg-muted/10">
                                        <td class="py-2.5 font-semibold text-foreground">{{ $aut['name'] }}</td>
                                        <td class="py-2.5 text-right font-mono font-bold">{{ $aut['docs'] }}</td>
                                        <td class="py-2.5 text-right font-mono text-muted-foreground">{{ $aut['citations'] }}</td>
                                        <td class="py-2.5 text-right font-mono text-[10px] text-primary hover:underline cursor-pointer">{{ $aut['scopus_id'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @elseif($activeTab === 'sinta')
        <!-- TAB 3: Sinta National Publications -->
        <div class="space-y-6">
            <!-- Sinta metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-muted-foreground uppercase">SINTA Score 3 Years</div>
                        <div class="text-3xl font-bold mt-1 text-foreground font-mono">{{ number_format($sintaStats['total_sinta_score_3yr']) }}</div>
                    </div>
                    <span class="p-2.5 bg-teal-500/10 text-teal-600 rounded-lg">SINTA</span>
                </div>

                <div class="bg-card text-card-foreground border border-border p-5 rounded-lg shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-muted-foreground uppercase">SINTA Score Overall</div>
                        <div class="text-3xl font-bold mt-1 text-foreground font-mono">{{ number_format($sintaStats['total_sinta_score_overall']) }}</div>
                    </div>
                    <span class="p-2.5 bg-teal-500/10 text-teal-600 rounded-lg">OVERALL</span>
                </div>
            </div>

            <!-- Sinta details grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sinta Jurnal Counts -->
                <div class="bg-card text-card-foreground border border-border rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2 mb-4">Distribusi Publikasi Jurnal Nasional (S1 - S6)</h3>
                    
                    <div class="space-y-4">
                        @foreach($sintaStats['sinta_journals'] as $jou)
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-medium text-foreground">
                                    <span>{{ $jou['level'] }}</span>
                                    <span class="font-mono text-muted-foreground font-bold">{{ $jou['count'] }} artikel</span>
                                </div>
                                <div class="w-full bg-muted h-2 rounded-full overflow-hidden">
                                    <div class="{{ $jou['color'] }} h-full rounded-full" style="width: {{ ($jou['count'] / 24) * 100 }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Sinta Authors -->
                <div class="bg-card text-card-foreground border border-border rounded-lg p-5">
                    <h3 class="text-sm font-semibold text-foreground border-b border-border pb-2 mb-4">Dosen dengan Skor SINTA Tertinggi</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="border-b border-border text-muted-foreground font-bold">
                                    <th class="pb-2">Nama Dosen</th>
                                    <th class="pb-2 text-right">SINTA Score 3 Yr</th>
                                    <th class="pb-2 text-right">SINTA Score Overall</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($sintaStats['top_sinta_authors'] as $aut)
                                    <tr class="hover:bg-muted/10">
                                        <td class="py-2.5 font-semibold text-foreground">{{ $aut['name'] }}</td>
                                        <td class="py-2.5 text-right font-mono font-bold text-teal-600">{{ $aut['score_3yr'] }}</td>
                                        <td class="py-2.5 text-right font-mono text-muted-foreground font-bold">{{ $aut['score_overall'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
