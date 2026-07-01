<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Pendaftaran Anggota Online (k-online)</h1>
            <p class="text-xs text-muted-foreground mt-1">Verifikasi registrasi kartu anggota perpustakaan yang diajukan secara online.</p>
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
        <!-- Status & Type Filters -->
        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="statusFilter" class="px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="active">Aktif</option>
                <option value="rejected">Ditolak</option>
            </select>

            <select wire:model.live="typeFilter" class="px-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground focus:outline-none">
                <option value="">Semua Tipe Anggota</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
                <option value="staff">Staf/Karyawan</option>
                <option value="umum">Umum</option>
            </select>
        </div>

        <!-- Search -->
        <div class="relative min-w-[240px]">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground lucide lucide-search">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, NIM/NIP..." class="w-full pl-8 pr-3 py-1.5 bg-background border border-border rounded-lg text-xs text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Foto</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Nama Lengkap</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">NIM / NIP</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Jenis Anggota</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Kontak / Email</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-center">Status</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($memberships as $member)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="p-4">
                                <div class="w-10 h-10 rounded border border-border bg-muted flex items-center justify-center overflow-hidden">
                                    @if($member->photo_path)
                                        <div class="text-[9px] font-mono text-muted-foreground">FOTO</div>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 font-semibold text-foreground">
                                {{ $member->name }}
                            </td>
                            <td class="p-4 font-mono text-xs text-muted-foreground">
                                {{ $member->nim_nip }}
                            </td>
                            <td class="p-4 text-xs text-foreground capitalize">
                                {{ $member->member_type }}
                            </td>
                            <td class="p-4 text-xs">
                                <div class="text-foreground">{{ $member->email }}</div>
                                <div class="text-[10px] text-muted-foreground mt-0.5 font-mono">{{ $member->phone }}</div>
                            </td>
                            <td class="p-4 text-center">
                                @if($member->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30">
                                        Pending
                                    </span>
                                @elseif($member->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-destructive/10 text-destructive border border-destructive/30">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-1.5">
                                @if($member->status === 'pending')
                                    <button wire:click="updateStatus({{ $member->id }}, 'active')" class="px-2 py-1 bg-emerald-500 text-white rounded text-xs hover:bg-emerald-600 font-semibold transition-colors">
                                        Aktifkan
                                    </button>
                                    <button wire:click="updateStatus({{ $member->id }}, 'rejected')" class="px-2 py-1 bg-destructive text-white rounded text-xs hover:bg-destructive/95 font-semibold transition-colors">
                                        Tolak
                                    </button>
                                @endif
                                <button wire:click="deleteMembership({{ $member->id }})" wire:confirm="Hapus pendaftaran kartu anggota ini?" class="text-xs text-muted-foreground hover:text-destructive font-medium transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-xs text-muted-foreground">
                                Tidak ada pendaftaran anggota online ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($memberships->hasPages())
            <div class="p-4 border-t border-border bg-muted/10">
                {{ $memberships->links() }}
            </div>
        @endif
    </div>
</div>
