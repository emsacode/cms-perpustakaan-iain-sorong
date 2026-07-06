<div class="mt-auto p-4 border-t border-border flex flex-col gap-2 bg-card relative" x-data="{ open: false }">
    <!-- User Info & Dropdown Trigger -->
    <div @click="open = !open" 
         class="flex items-center justify-between p-2 rounded-lg hover:bg-accent hover:text-accent-foreground cursor-pointer transition-colors">
        <div class="flex items-center gap-3">
            <!-- User Avatar -->
            <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center font-bold text-xs text-secondary-foreground border border-border">
                AD
            </div>
            <!-- Name & Role -->
            <div class="flex flex-col text-left">
                <span class="text-xs font-semibold text-foreground truncate max-w-[120px]">Admin Perpustakaan</span>
                <span class="text-[10px] text-muted-foreground">admin@iainsorong.ac.id</span>
            </div>
        </div>
        
        <!-- Dropdown Ikon (Double Chevron vertical) -->
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground lucide lucide-chevrons-up-down">
            <path d="m7 15 5 5 5-5"/>
            <path d="m7 9 5-5 5 5"/>
        </svg>
    </div>

    <!-- Melayang ke Atas Popover Dropdown (dengan click.outside) -->
    <div x-show="open" 
         x-on:click.outside="open = false"
         x-transition:enter="transition ease-out duration-100 transform"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-75 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute bottom-16 left-4 right-4 bg-popover text-popover-foreground border border-border rounded-lg shadow-md p-1.5 z-30 flex flex-col gap-1"
         style="display: none;"
         x-cloak>
        
        <!-- Theme Toggle Row -->
        <div class="flex items-center justify-between px-2.5 py-1.5 rounded-md hover:bg-accent text-xs">
            <span>Tema</span>
            <x-theme-toggle />
        </div>
        
        <!-- Divider -->
        <hr class="border-border my-1">
        
        <a href="#" class="flex items-center gap-2 px-2.5 py-1.5 rounded-md hover:bg-accent hover:text-accent-foreground text-xs text-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <span>Pengaturan</span>
        </a>

        <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-2 w-full px-2.5 py-1.5 rounded-md hover:bg-destructive/10 text-destructive text-xs text-left">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</div>
