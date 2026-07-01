@props(['logs' => []])

<div x-data="{ open: false }" class="border border-border rounded-lg bg-muted/30 overflow-hidden mb-3">
    <!-- Accordion Trigger Button -->
    <button @click="open = !open" 
            type="button"
            class="flex items-center justify-between w-full px-4 py-2.5 text-xs font-semibold text-muted-foreground hover:text-foreground hover:bg-muted/70 transition-colors focus:outline-none">
        
        <div class="flex items-center gap-2">
            <!-- Pulsing brain/cpu icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500 animate-pulse lucide lucide-brain-circuit">
                <path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"/>
                <path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"/>
                <path d="M9 13h6"/>
                <path d="M12 9v8"/>
            </svg>
            <span>Thinking Process</span>
        </div>
        
        <!-- Rotating Chevron -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             width="14" 
             height="14" 
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
    
    <!-- Accordion Content -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="px-4 pb-3 pt-1 border-t border-border/30 text-xs font-mono text-muted-foreground space-y-1.5 bg-muted/10"
         style="display: none;"
         x-cloak>
         
        @if(count($logs) > 0)
            @foreach($logs as $log)
                <div class="flex gap-2">
                    <span class="text-indigo-400 select-none">&gt;</span>
                    <span>{{ $log }}</span>
                </div>
            @endforeach
        @else
            <!-- Default placeholder sql reasoning logs -->
            <div class="flex gap-2">
                <span class="text-indigo-400 select-none">&gt;</span>
                <span>Parsing user request: "Hitung total view berita bulan ini dan prediksi pertumbuhan."</span>
            </div>
            <div class="flex gap-2">
                <span class="text-indigo-400 select-none">&gt;</span>
                <span>Executing: SELECT SUM(views_count) FROM articles WHERE status = 'published';</span>
            </div>
            <div class="flex gap-2">
                <span class="text-indigo-400 select-none">&gt;</span>
                <span>Calculating linear regression for next 30 days based on views_count historical data...</span>
            </div>
            <div class="flex gap-2 text-emerald-500">
                <span class="select-none">[✔]</span>
                <span>Result compiled: 45,102 views found. Growth trend +12.4% predicted.</span>
            </div>
        @endif
    </div>
</div>
