<nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto">
    <!-- Dashboard Analitik AI Link -->
    @php 
        $isDashboard = request()->routeIs('admin.dashboard') || request()->is('admin') || request()->is('admin/dashboard');
    @endphp
    <a href="{{ route('admin.dashboard') }}" 
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isDashboard ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        @if($isDashboard)
            <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles">
            <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275Z"/>
            <path d="m5 3 1 2.5L8.5 6 6 7 5 9.5 4 7 1.5 6 4 5.5Z"/>
            <path d="m19 17 1 2.5 2.5.5-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1Z"/>
        </svg>
        <span>Dashboard AI</span>
    </a>

    <!-- CMS Berita (Collapsible Group) -->
    @php
        $isCmsOpen = request()->is('admin/articles*') || request()->routeIs('admin.articles*');
    @endphp
    <div x-data="{ open: {{ $isCmsOpen ? 'true' : 'false' }} }" class="space-y-1">
        <button @click="open = !open" 
                class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-sm font-medium transition-colors text-muted-foreground hover:bg-accent hover:text-accent-foreground">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper">
                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                    <path d="M18 14h-8"/>
                    <path d="M15 18h-5"/>
                    <path d="M10 6h8v4h-8V6Z"/>
                </svg>
                <span>CMS Berita</span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" 
                 width="16" 
                 height="16" 
                 viewBox="0 0 24 24" 
                 fill="none" 
                 stroke="currentColor" 
                 stroke-width="2" 
                 stroke-linecap="round" 
                 stroke-linejoin="round" 
                 :class="open ? 'rotate-180' : ''" 
                 class="transition-transform duration-200 lucide lucide-chevron-down">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </button>
        
        <!-- Sub-menu Items -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="pl-8 space-y-1"
             style="display: none;"
             x-cloak>
            @php 
                $isAllPos = request()->is('admin/articles') && !request()->has('action');
                $isTambahPos = request()->get('action') === 'create';
                $isKategori = request()->get('action') === 'categories';
                $isTag = request()->get('action') === 'tags';
                $isSdgs = request()->get('action') === 'sdgs';
            @endphp
            <a href="{{ route('admin.articles') }}" 
               class="flex items-center gap-2 py-1.5 px-3 rounded-lg text-xs font-medium relative {{ $isAllPos ? 'text-foreground font-semibold bg-accent' : 'text-muted-foreground hover:text-foreground' }}">
                @if($isAllPos)
                    <span class="absolute left-0 top-1/3 bottom-1/3 w-0.5 bg-primary rounded-r"></span>
                @endif
                <span>Semua Pos</span>
            </a>
            
            <a href="{{ route('admin.articles', ['action' => 'create']) }}" 
               class="flex items-center gap-2 py-1.5 px-3 rounded-lg text-xs font-medium relative {{ $isTambahPos ? 'text-foreground font-semibold bg-accent' : 'text-muted-foreground hover:text-foreground' }}">
                @if($isTambahPos)
                    <span class="absolute left-0 top-1/3 bottom-1/3 w-0.5 bg-primary rounded-r"></span>
                @endif
                <span>Tambah Pos</span>
            </a>

            <a href="{{ route('admin.articles', ['action' => 'categories']) }}" 
               class="flex items-center gap-2 py-1.5 px-3 rounded-lg text-xs font-medium relative {{ $isKategori ? 'text-foreground font-semibold bg-accent' : 'text-muted-foreground hover:text-foreground' }}">
                @if($isKategori)
                    <span class="absolute left-0 top-1/3 bottom-1/3 w-0.5 bg-primary rounded-r"></span>
                @endif
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.articles', ['action' => 'tags']) }}" 
               class="flex items-center gap-2 py-1.5 px-3 rounded-lg text-xs font-medium relative {{ $isTag ? 'text-foreground font-semibold bg-accent' : 'text-muted-foreground hover:text-foreground' }}">
                @if($isTag)
                    <span class="absolute left-0 top-1/3 bottom-1/3 w-0.5 bg-primary rounded-r"></span>
                @endif
                <span>Tag</span>
            </a>

            <a href="{{ route('admin.articles', ['action' => 'sdgs']) }}" 
               class="flex items-center gap-2 py-1.5 px-3 rounded-lg text-xs font-medium relative {{ $isSdgs ? 'text-foreground font-semibold bg-accent' : 'text-muted-foreground hover:text-foreground' }}">
                @if($isSdgs)
                    <span class="absolute left-0 top-1/3 bottom-1/3 w-0.5 bg-primary rounded-r"></span>
                @endif
                <span>SDGs Tag</span>
            </a>
        </div>
    </div>

    <!-- Analitik & Statistik Link -->
    @php 
        $isAnalytics = request()->is('admin/analytics*') || request()->routeIs('admin.analytics*');
    @endphp
    <a href="{{ route('admin.analytics') }}" 
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isAnalytics ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        @if($isAnalytics)
            <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up">
            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
        </svg>
        <span>Analitik & Statistik</span>
    </a>

    <!-- Manajemen Pengguna Link -->
    @php 
        $isUsers = request()->is('admin/users*') || request()->routeIs('admin.users*');
    @endphp
    <a href="{{ route('admin.users') }}" 
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isUsers ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        @if($isUsers)
            <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        <span>Manajemen Pengguna</span>
    </a>

    <!-- Divider -->
    <hr class="border-border my-2 mx-3">

    <!-- Section Heading: Publikasi & Konten -->
    <div class="px-3 py-1 text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Konten & Publikasi</div>

    <!-- CMS Halaman Statis Link -->
    @php 
        $isPages = request()->is('admin/pages*') || request()->routeIs('admin.pages*');
    @endphp
    <a href="{{ route('admin.pages') }}" 
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isPages ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        @if($isPages)
            <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-files">
            <path d="M15.5 2H8.62a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V5.4a2 2 0 0 0-.59-1.42l-2.1-2.1A2 2 0 0 0 15.5 2Z"/>
            <path d="M8 18v2a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-2"/>
            <path d="M12 11h4"/><path d="M12 7h4"/>
        </svg>
        <span>CMS Halaman Statis</span>
    </a>

    <!-- Podcast Literasi Link -->
    @php 
        $isPodcasts = request()->is('admin/podcasts*') || request()->routeIs('admin.podcasts*');
    @endphp
    <a href="{{ route('admin.podcasts') }}" 
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isPodcasts ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        @if($isPodcasts)
            <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic">
            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
            <line x1="12" x2="12" y1="19" y2="22"/>
        </svg>
        <span>Podcast Literasi</span>
    </a>

    <!-- Divider -->
    <hr class="border-border my-2 mx-3">

    <!-- Section Heading: Layanan Interaktif -->
    <div class="px-3 py-1 text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Layanan Interaktif</div>

    <!-- Manajemen Reservasi Link -->
    @php 
        $isReservations = request()->is('admin/reservations*') || request()->routeIs('admin.reservations*');
        $pendingReservations = \Illuminate\Support\Facades\DB::table('reservations')->where('status', 'pending')->count();
    @endphp
    <a href="{{ route('admin.reservations') }}" 
       class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isReservations ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        <div class="flex items-center gap-3">
            @if($isReservations)
                <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days">
                <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>
            </svg>
            <span>Reservasi Ruangan</span>
        </div>
        @if($pendingReservations > 0)
            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-destructive text-destructive-foreground animate-pulse">
                {{ $pendingReservations }}
            </span>
        @endif
    </a>

    <!-- Usulan Buku (Desiderata) Link -->
    @php 
        $isDesiderata = request()->is('admin/desiderata*') || request()->routeIs('admin.desiderata*');
    @endphp
    <a href="{{ route('admin.desiderata') }}" 
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isDesiderata ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        @if($isDesiderata)
            <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox">
            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
            <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
        </svg>
        <span>Usulan Buku</span>
    </a>

    <!-- Bebas Pustaka Link -->
    @php 
        $isClearances = request()->is('admin/clearances*') || request()->routeIs('admin.clearances*');
        $pendingClearances = \App\Models\Clearance::where('status', 'pending')->count();
    @endphp
    <a href="{{ route('admin.clearances') }}" 
       class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isClearances ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        <div class="flex items-center gap-3">
            @if($isClearances)
                <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap">
                <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
                <path d="M6 18.8v-4L2 13v6a1 1 0 0 0 1 1h3Z"/>
                <path d="M12 14.5v6"/>
            </svg>
            <span>Bebas Pustaka</span>
        </div>
        @if($pendingClearances > 0)
            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-destructive text-destructive-foreground animate-pulse">
                {{ $pendingClearances }}
            </span>
        @endif
    </a>

    <!-- Anggota Online Link -->
    @php 
        $isMemberships = request()->is('admin/memberships*') || request()->routeIs('admin.memberships*');
        $pendingMemberships = \App\Models\Membership::where('status', 'pending')->count();
    @endphp
    <a href="{{ route('admin.memberships') }}" 
       class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isMemberships ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        <div class="flex items-center gap-3">
            @if($isMemberships)
                <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-contact">
                <path d="M17 18a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2"/>
                <rect width="18" height="18" x="3" y="4" rx="2"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            <span>Anggota Online</span>
        </div>
        @if($pendingMemberships > 0)
            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-destructive text-destructive-foreground animate-pulse">
                {{ $pendingMemberships }}
            </span>
        @endif
    </a>

    <!-- Indeks Kepuasan (Survei IKM) Link -->
    @php 
        $isSurveys = request()->is('admin/surveys*') || request()->routeIs('admin.surveys*');
    @endphp
    <a href="{{ route('admin.surveys') }}" 
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors relative {{ $isSurveys ? 'bg-secondary text-secondary-foreground font-semibold' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
        @if($isSurveys)
            <span class="absolute left-0 top-1/4 bottom-1/4 w-0.5 bg-primary rounded-r"></span>
        @endif
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3">
            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
        </svg>
        <span>Survei IKM</span>
    </a>
</nav>
