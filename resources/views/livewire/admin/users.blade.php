<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col gap-1.5 text-left">
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Manajemen Pengguna</h1>
            <p class="text-sm text-muted-foreground">Kelola hak akses editor dan admin untuk penulisan artikel perpustakaan.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none shadow-sm">
                Tambah Anggota Staf
            </button>
        </div>
    </div>

    <!-- Feedback message -->
    @if(session()->has('message'))
        <div class="p-3 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded-lg text-xs font-medium">
            {{ session('message') }}
        </div>
    @endif

    <!-- Control & Search Row -->
    <div class="flex items-center justify-between gap-4">
        <!-- Debounced Search (Matches PRD Modul D: Modul 1) -->
        <div class="relative w-full max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
            </div>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari nama atau email staf..." 
                   class="w-full pl-9 pr-4 py-2 border border-border rounded-lg bg-card text-foreground text-xs focus:outline-none focus:ring-1 focus:ring-ring placeholder-muted-foreground shadow-sm">
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border bg-muted/20 text-[10px] font-semibold text-muted-foreground uppercase tracking-wider">
                        <th class="px-6 py-3.5">Nama</th>
                        <th class="px-6 py-3.5">Email</th>
                        <th class="px-6 py-3.5">User ID</th>
                        <th class="px-6 py-3.5">Peran</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-xs">
                    @forelse($users as $user)
                        <tr class="hover:bg-muted/10 transition-colors">
                            <!-- Avatar & Name -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center font-bold text-xs text-secondary-foreground border border-border">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-foreground">{{ $user->name }}</span>
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-6 py-4 text-muted-foreground">
                                {{ $user->email }}
                            </td>
                            
                            <!-- User ID Copy Button (Matches PRD Modul B: Modul 1) -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono text-[10px] text-muted-foreground">{{ $user->id }}</span>
                                    <x-copy-button :value="$user->id" />
                                </div>
                            </td>
                            
                            <!-- Role Badge -->
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-indigo-500/10 text-indigo-500 border border-indigo-500/20">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-secondary text-secondary-foreground border border-border">
                                        Editor
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Status Active/Inactive toggle check -->
                            <td class="px-6 py-4">
                                <button type="button" 
                                        wire:click="toggleStatus({{ $user->id }})"
                                        class="inline-flex items-center gap-1.5 focus:outline-none">
                                    @if($user->status === 'active')
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                        <span class="text-emerald-500 font-semibold text-[10px] hover:underline">Active</span>
                                    @else
                                        <div class="w-1.5 h-1.5 rounded-full bg-muted-foreground"></div>
                                        <span class="text-muted-foreground text-[10px] hover:underline">Inactive</span>
                                    @endif
                                </button>
                            </td>
                            
                            <!-- Actions Dropdown with Outside Click Close (Matches PRD Modul D: Modul 2) -->
                            <td class="px-6 py-4 text-right relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        type="button"
                                        class="p-1.5 rounded-md hover:bg-accent text-muted-foreground hover:text-foreground focus:outline-none inline-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown list -->
                                <div x-show="open" 
                                     x-on:click.outside="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     class="absolute right-6 mt-1 w-36 bg-popover text-popover-foreground border border-border rounded-lg shadow-md py-1 z-30 text-left"
                                     style="display: none;"
                                     x-cloak>
                                    
                                    <div class="px-2.5 py-1 text-[9px] font-semibold text-muted-foreground uppercase tracking-wider">Ubah Peran</div>
                                    <button wire:click="changeRole({{ $user->id }}, 'admin')" @click="open = false" class="block w-full px-3 py-1.5 text-left text-xs hover:bg-accent">Jadikan Admin</button>
                                    <button wire:click="changeRole({{ $user->id }}, 'editor')" @click="open = false" class="block w-full px-3 py-1.5 text-left text-xs hover:bg-accent">Jadikan Editor</button>
                                    
                                    <hr class="border-border my-1">
                                    
                                    <button wire:click="toggleStatus({{ $user->id }})" @click="open = false" class="block w-full px-3 py-1.5 text-left text-xs hover:bg-accent">
                                        {{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-muted-foreground">Tidak ditemukan pengguna/staf.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-border bg-muted/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
