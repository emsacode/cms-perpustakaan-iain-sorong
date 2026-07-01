<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Bebas Pustaka Online</h1>
            <p class="text-xs text-muted-foreground mt-1">Verifikasi berkas tugas akhir mahasiswa dan kelola surat keterangan bebas perpustakaan.</p>
        </div>
    </div>

    <!-- Alert -->
    @if (session()->has('message'))
        <div class="p-3 text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Controls -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Status Filters -->
        <div class="flex items-center gap-2">
            <select wire:model.live="statusFilter" class="px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Verifikasi</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>

        <!-- Search -->
        <div class="relative min-w-[240px]">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground lucide lucide-search">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, NIM/NIDN..." class="w-full pl-8 pr-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Mahasiswa</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">NIM / NIDN</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Program Studi</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Berkas Tugas Akhir</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Nomor Kontak</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-center">Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($clearances as $clear)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="p-4 font-semibold text-foreground">
                                {{ $clear->name }}
                            </td>
                            <td class="p-4 font-mono text-xs text-muted-foreground">
                                {{ $clear->nim_nidn }}
                            </td>
                            <td class="p-4 text-xs text-foreground">
                                {{ $clear->program_studi }}
                            </td>
                            <td class="p-4 text-xs">
                                @if($clear->thesis_file)
                                    <a href="#" onclick="alert('Membuka file: {{ $clear->thesis_file }}')" class="inline-flex items-center text-primary hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 lucide lucide-file-text"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                        Lihat Draft TA (PDF)
                                    </a>
                                @else
                                    <span class="text-muted-foreground italic text-[11px]">Belum diunggah</span>
                                @endif
                            </td>
                            <td class="p-4 font-mono text-xs text-muted-foreground">
                                {{ $clear->phone }}
                            </td>
                            <td class="p-4 text-center">
                                @if($clear->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30">
                                        Pending
                                    </span>
                                @elseif($clear->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-destructive/10 text-destructive border border-destructive/30">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-1.5">
                                @if($clear->status === 'pending')
                                    <button wire:click="updateStatus({{ $clear->id }}, 'approved')" class="px-2 py-1 bg-emerald-500 text-white rounded text-xs hover:bg-emerald-600 font-semibold transition-colors">
                                        Setujui
                                    </button>
                                    <button wire:click="updateStatus({{ $clear->id }}, 'rejected')" class="px-2 py-1 bg-destructive text-white rounded text-xs hover:bg-destructive/95 font-semibold transition-colors">
                                        Tolak
                                    </button>
                                @endif
                                <button wire:click="deleteClearance({{ $clear->id }})" wire:confirm="Hapus pengajuan ini?" class="text-xs text-muted-foreground hover:text-destructive font-medium transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-xs text-muted-foreground">
                                Tidak ada pengajuan bebas pustaka ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clearances->hasPages())
            <div class="p-4 border-t border-border bg-muted/10">
                {{ $clearances->links() }}
            </div>
        @endif
    </div>
</div>
