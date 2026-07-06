<div class="space-y-6">
    <!-- Breadcrumbs / Back Link -->
    <div class="flex items-center justify-between border-b border-border pb-5">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reservations') }}" 
               class="inline-flex items-center text-xs font-semibold text-muted-foreground hover:text-foreground transition-colors gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Daftar Reservasi
            </a>
        </div>
        <div>
            <span class="text-xs text-muted-foreground font-mono">Kode Booking: #ROOM-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Columns: Details Information -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Pemohon Details -->
            <div class="bg-card text-card-foreground border border-border rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-foreground border-b border-border pb-2.5">Informasi Pemohon</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-muted-foreground block mb-1">Nama Lengkap</span>
                        <span class="font-bold text-foreground text-sm">{{ $reservation->name }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground block mb-1">NIM / NIP</span>
                        <span class="font-bold text-foreground text-sm">{{ $reservation->nim_nip }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground block mb-1">Alamat Email</span>
                        <span class="font-bold text-foreground text-sm">{{ $reservation->email }}</span>
                    </div>
                    @if(!empty($reservation->link_surat))
                        <div>
                            <span class="text-muted-foreground block mb-1">Dokumen Lampiran</span>
                            <a href="{{ $reservation->link_surat }}" target="_blank" 
                               class="inline-flex items-center text-xs font-bold text-primary hover:underline gap-1 mt-0.5">
                                Unduh Surat Permohonan (PDF)
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Details & Static Room Facilities -->
            <div class="bg-card text-card-foreground border border-border rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-foreground border-b border-border pb-2.5">Rincian Booking & Fasilitas Ruangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-muted-foreground block mb-1">Ruangan</span>
                        <span class="font-bold text-foreground text-sm">{{ $reservation->room_name }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground block mb-1">Tanggal Sewa</span>
                        <span class="font-bold text-foreground text-sm">{{ \Carbon\Carbon::parse($reservation->booking_date)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground block mb-1">Sesi Waktu</span>
                        <span class="font-bold text-foreground text-sm">{{ $reservation->session_time }}</span>
                    </div>
                </div>

                <!-- Facilities -->
                <div class="pt-4 border-t border-border space-y-2">
                    <h4 class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Fasilitas Standar dalam Ruangan:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($facilities as $facility)
                            <div class="flex items-center text-xs text-foreground gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 lucide lucide-check-square-2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                                <span>{{ $facility }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Audit Logs / Key Lifecycle Logs -->
            @if(!empty($reservation->picked_up_at) || !empty($reservation->returned_at) || !empty($reservation->notes_inventory))
                <div class="bg-card text-card-foreground border border-border rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-bold text-foreground border-b border-border pb-2.5">Log Audit & Serah Terima Kunci</h3>
                    <div class="space-y-3 text-xs">
                        @if(!empty($reservation->picked_up_at))
                            <div class="flex items-start gap-3">
                                <div class="p-1 rounded bg-indigo-500/10 text-indigo-600 border border-indigo-500/20 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 1.5 1.5M15.5 7.5 14 6"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold text-foreground block">Kunci Diserahkan</span>
                                    <span class="text-[11px] text-muted-foreground">{{ \Carbon\Carbon::parse($reservation->picked_up_at)->translatedFormat('d F Y, H:i') }} WIT</span>
                                </div>
                            </div>
                        @endif

                        @if(!empty($reservation->returned_at))
                            <div class="flex items-start gap-3">
                                <div class="p-1 rounded bg-sky-500/10 text-sky-600 border border-sky-500/20 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold text-foreground block">Kunci Dikembalikan</span>
                                    <span class="text-[11px] text-muted-foreground">{{ \Carbon\Carbon::parse($reservation->returned_at)->translatedFormat('d F Y, H:i') }} WIT</span>
                                </div>
                            </div>
                        @endif

                        @if(!empty($reservation->notes_inventory))
                            <div class="pt-2 border-t border-border">
                                <span class="text-muted-foreground block mb-1">Catatan Tambahan / Kerusakan / Kehilangan:</span>
                                <div class="bg-muted/40 p-3 rounded-lg border border-border text-foreground font-medium italic">
                                    "{{ $reservation->notes_inventory }}"
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- Right 1 Column: Decision & Status Actions -->
        <div class="space-y-6">
            
            <!-- Status Card -->
            <div class="bg-card text-card-foreground border border-border rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-foreground border-b border-border pb-2.5">Status Transaksi</h3>
                <div class="flex flex-col items-center justify-center py-4 space-y-3">
                    @if($reservation->status === 'pending')
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 tracking-wide uppercase">
                            Menunggu Persetujuan
                        </span>
                    @elseif($reservation->status === 'approved')
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 tracking-wide uppercase">
                            Disetujui
                        </span>
                    @elseif($reservation->status === 'key_picked_up')
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 tracking-wide uppercase">
                            Kunci Diambil
                        </span>
                    @elseif($reservation->status === 'returned')
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20 tracking-wide uppercase">
                            Kunci Dikembalikan
                        </span>
                    @elseif($reservation->status === 'overdue')
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 tracking-wide uppercase">
                            Terlambat
                        </span>
                    @elseif($reservation->status === 'cancelled')
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20 tracking-wide uppercase">
                            Dibatalkan
                        </span>
                    @else
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-destructive/10 text-destructive border border-destructive/20 tracking-wide uppercase">
                            Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action panel based on status -->
            <div class="bg-card text-card-foreground border border-border rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-foreground border-b border-border pb-2.5">Tindakan Petugas</h3>
                
                <!-- Action 1: Pending -->
                @if($reservation->status === 'pending')
                    <div class="space-y-4">
                        @if($showRejectInput)
                            <div class="space-y-3">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-muted-foreground block">Alasan Penolakan (Wajib)</label>
                                    <textarea 
                                        wire:model="rejectionReasonText"
                                        placeholder="Tulis alasan penolakan... (misal: Ruangan sedang dibooking untuk rapat rektorat)" 
                                        class="w-full text-xs p-3 bg-background border border-border rounded-lg focus:outline-none focus:ring-1 focus:ring-ring h-24 resize-none"></textarea>
                                    @error('rejectionReasonText')
                                        <span class="text-xs text-destructive font-medium block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex gap-2">
                                    <button 
                                        wire:click="rejectReservation" 
                                        class="flex-1 py-2 text-xs font-bold rounded-lg text-white bg-destructive hover:bg-destructive/90 transition-colors cursor-pointer">
                                        Kirim Penolakan
                                    </button>
                                    <button 
                                        wire:click="$set('showRejectInput', false)" 
                                        class="px-3 py-2 text-xs font-bold rounded-lg border border-border bg-background text-muted-foreground hover:bg-muted transition-colors cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col gap-2.5">
                                <button 
                                    wire:click="approveReservation" 
                                    class="w-full py-2.5 text-xs font-bold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors cursor-pointer">
                                    Setujui Permohonan
                                </button>
                                <button 
                                    wire:click="$set('showRejectInput', true)" 
                                    class="w-full py-2.5 text-xs font-bold rounded-lg text-destructive bg-destructive/10 hover:bg-destructive/20 border border-destructive/20 transition-colors cursor-pointer">
                                    Tolak Permohonan
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Action 2: Approved (Key pickup) -->
                @if($reservation->status === 'approved')
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-muted-foreground block">Catatan Kelengkapan Tambahan (Opsional)</label>
                            <input 
                                wire:model="notesInventoryText"
                                type="text"
                                placeholder="Contoh: Remote AC dipinjamkan"
                                class="w-full text-xs px-3 py-2 bg-background border border-border rounded-lg focus:outline-none focus:ring-1 focus:ring-ring"
                            />
                        </div>
                        <button 
                            wire:click="releaseKey" 
                            class="w-full py-2.5 text-xs font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors cursor-pointer">
                            Serahkan Kunci Ruangan
                        </button>
                    </div>
                @endif

                <!-- Action 3: Key Picked Up (Key return) -->
                @if($reservation->status === 'key_picked_up')
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-muted-foreground block">Catatan Pengembalian (Opsional)</label>
                            <input 
                                wire:model="notesInventoryText"
                                type="text"
                                placeholder="Contoh: Remote AC dikembalikan lengkap"
                                class="w-full text-xs px-3 py-2 bg-background border border-border rounded-lg focus:outline-none focus:ring-1 focus:ring-ring"
                            />
                        </div>
                        <button 
                            wire:click="returnKey" 
                            class="w-full py-2.5 text-xs font-bold rounded-lg text-white bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer">
                            Konfirmasi Pengembalian Kunci
                        </button>
                    </div>
                @endif

                <!-- Action 4: Rejected / Returned / Cancelled (Completed flow) -->
                @if(in_array($reservation->status, ['rejected', 'returned', 'cancelled']))
                    <div class="text-xs text-muted-foreground text-center py-4 italic">
                        Transaksi ini sudah diproses dan selesai secara administratif.
                    </div>
                @endif
            </div>

            <!-- Rejection details (If rejected) -->
            @if($reservation->status === 'rejected' && !empty($reservation->rejection_reason))
                <div class="bg-red-500/10 border border-red-500/20 text-destructive p-5 rounded-xl shadow-sm text-xs leading-relaxed font-semibold">
                    <span class="block font-bold text-sm mb-1.5">Catatan Alasan Penolakan:</span>
                    "{{ $reservation->rejection_reason }}"
                </div>
            @endif
            
            <!-- Cancellation details (If cancelled) -->
            @if($reservation->status === 'cancelled' && !empty($reservation->rejection_reason))
                <div class="bg-slate-500/10 border border-slate-500/20 text-slate-700 dark:text-slate-400 p-5 rounded-xl shadow-sm text-xs leading-relaxed font-semibold">
                    <span class="block font-bold text-sm mb-1.5">Catatan Pembatalan Otomatis:</span>
                    "{{ $reservation->rejection_reason }}"
                </div>
            @endif
            
        </div>

    </div>
</div>
