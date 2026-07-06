<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Usulan Buku</h1>
            <p class="text-xs text-muted-foreground mt-1">Kelola usulan pengadaan buku baru yang diajukan oleh dosen, mahasiswa, dan civitas akademika.</p>
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
                   placeholder="Cari judul, penulis, ISBN, atau nama pengusul..." 
                   class="w-full pl-9 pr-4 py-2 bg-background border border-border rounded-lg text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
        </div>
        
        <select wire:model.live="statusFilter" 
                class="px-3 py-2 bg-background border border-border rounded-lg text-sm text-foreground focus:outline-none focus:ring-1 focus:ring-ring min-w-[150px]">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Disetujui</option>
            <option value="purchased">Telah Dibeli</option>
        </select>
    </div>

    <!-- Data Table -->
    <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Detail Buku</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Pengusul & Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Kebutuhan Kuliah</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Alasan Pengusulan</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($desiderata as $item)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <!-- Detail Buku -->
                            <td class="p-4">
                                <div class="font-semibold text-foreground text-xs md:text-sm">{{ $item->title }}</div>
                                <div class="text-[11px] text-muted-foreground mt-1">
                                    Penulis: <span class="font-medium text-foreground">{{ $item->author ?? '-' }}</span> 
                                    @if($item->publisher) • Penerbit: <span class="font-medium text-foreground">{{ $item->publisher }}</span>@endif
                                    @if($item->year) ({{ $item->year }})@endif
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if($item->isbn)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                            ISBN: {{ $item->isbn }}
                                        </span>
                                    @endif
                                    @if($item->reference_url)
                                        <a href="{{ $item->reference_url }}" target="_blank" class="inline-flex items-center text-[10px] text-primary hover:underline font-semibold bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                            Link Referensi
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Pengusul & Status -->
                            <td class="p-4">
                                <div class="text-xs font-semibold text-foreground">{{ $item->proposer_name }}</div>
                                <div class="text-[10px] text-muted-foreground mt-0.5">{{ $item->proposer_email }}</div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-secondary text-secondary-foreground">
                                        {{ $item->proposer_status ?? 'Mahasiswa' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Kebutuhan Kuliah -->
                            <td class="p-4">
                                <div class="text-xs font-medium text-foreground">Matkul: {{ $item->course ?? '-' }}</div>
                                <div class="text-[10px] text-muted-foreground mt-0.5">Estimasi: {{ $item->estimated_students ?? '0' }} Mahasiswa</div>
                            </td>

                            <!-- Alasan Pengusulan -->
                            <td class="p-4">
                                <p class="text-xs text-muted-foreground max-w-[250px] leading-relaxed italic" title="{{ $item->reason }}">
                                    "{{ $item->reason ?? '-' }}"
                                </p>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                @if(($item->status ?? 'pending') === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        Pending
                                    </span>
                                @elseif($item->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        Telah Dibeli
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(($item->status ?? 'pending') === 'pending')
                                        <button wire:click="updateStatus({{ $item->id }}, 'approved')" 
                                                title="Setujui Usulan"
                                                class="p-1 rounded-md hover:bg-muted text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(($item->status ?? 'pending') === 'approved')
                                        <button wire:click="updateStatus({{ $item->id }}, 'purchased')" 
                                                title="Tandai Telah Dibeli"
                                                class="p-1 rounded-md hover:bg-muted text-emerald-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag">
                                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <button wire:click="deleteDesiderata({{ $item->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus data usulan ini?"
                                            title="Hapus"
                                            class="p-1 rounded-md hover:bg-muted text-muted-foreground hover:text-destructive transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                            <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted-foreground">
                                Tidak ada data usulan buku ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($desiderata->hasPages())
            <div class="p-4 border-t border-border bg-muted/10">
                {{ $desiderata->links() }}
            </div>
        @endif
    </div>
</div>
