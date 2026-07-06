<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Reservasi Ruangan</h1>
            <p class="text-xs text-muted-foreground mt-1">Kelola permohonan peminjaman ruangan, serah terima kunci, inventaris alat, serta audit transaksi peminjaman.</p>
        </div>
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

    <!-- Controls -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground lucide lucide-search">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Cari nama, NIM, atau nama ruangan..." 
                   class="w-full pl-9 pr-4 py-2 bg-background border border-border rounded-lg text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
        </div>
        
        <select wire:model.live="statusFilter" 
                class="px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring min-w-[150px]">
            <option value="">Semua Status</option>
            <option value="pending">Pending (Menunggu)</option>
            <option value="approved">Approved (Disetujui)</option>
            <option value="key_picked_up">Kunci Diambil</option>
            <option value="returned">Kunci Dikembalikan</option>
            <option value="rejected">Rejected (Ditolak)</option>
            <option value="overdue">Terlambat</option>
            <option value="cancelled">Dibatalkan</option>
        </select>
    </div>

    <!-- Data Table -->
    <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Pemustaka</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Ruangan</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Tanggal & Sesi</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($reservations as $res)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="p-4">
                                <div class="font-semibold text-foreground">{{ $res->name }}</div>
                                <div class="text-xs text-muted-foreground mt-0.5">{{ $res->nim_nip }} • {{ $res->email }}</div>
                                @if(!empty($res->link_surat))
                                    <div class="mt-1">
                                        <a href="{{ $res->link_surat }}" target="_blank" class="inline-flex items-center text-[11px] text-primary hover:underline font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 lucide lucide-file-text"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                            Surat Permohonan (PDF)
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-secondary text-secondary-foreground text-xs font-medium rounded border border-border">
                                    {{ $res->room_name }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="text-xs text-foreground font-medium font-mono">{{ \Carbon\Carbon::parse($res->booking_date)->format('d M Y') }}</div>
                                <div class="text-[10px] text-muted-foreground mt-0.5 font-mono">{{ $res->session_time }}</div>
                            </td>
                            <td class="p-4">
                                @if($res->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        Menunggu Persetujuan
                                    </span>
                                @elseif($res->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        Disetujui
                                    </span>
                                @elseif($res->status === 'key_picked_up')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                        Kunci Diambil
                                    </span>
                                @elseif($res->status === 'returned')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                                        Kunci Dikembalikan
                                    </span>
                                @elseif($res->status === 'overdue')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        Terlambat
                                    </span>
                                @elseif($res->status === 'cancelled')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                                        Dibatalkan
                                    </span>
                                @elseif($res->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-destructive/10 text-destructive border border-destructive/20">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right border-l-0">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.reservations.detail', $res->id) }}" 
                                       class="inline-flex items-center justify-center px-2.5 py-1.5 text-xs font-semibold rounded-lg bg-secondary text-secondary-foreground border border-border hover:bg-accent transition-colors gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Lihat Detail
                                    </a>

                                    <button wire:click="deleteReservation({{ $res->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus data reservasi ini?"
                                            title="Hapus"
                                            class="p-1.5 rounded-md hover:bg-muted text-muted-foreground hover:text-destructive transition-colors cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                            <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-xs text-muted-foreground">
                                Tidak ada data reservasi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reservations->hasPages())
            <div class="p-4 border-t border-border bg-muted/10">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
