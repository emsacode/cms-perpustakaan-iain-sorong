@props(['name', 'title' => '', 'show' => false])

<div x-data="{ show: @js($show) }"
     x-show="show"
     x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
     x-on:close-modal.window="if ($event.detail.name === '{{ $name }}') show = false"
     x-on:keydown.escape.window="show = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display: none;"
     x-cloak>
    
    <!-- Backdrop Overlay -->
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="show = false"
         class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Modal Dialog Content -->
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="bg-card text-card-foreground border border-border w-full max-w-lg rounded-lg shadow-lg overflow-hidden relative z-10 p-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-border mb-4">
            <h3 class="text-lg font-semibold text-foreground">{{ $title }}</h3>
            <button @click="show = false" class="text-muted-foreground hover:text-foreground p-1 rounded-md hover:bg-accent">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="text-sm">
            {{ $slot }}
        </div>
    </div>
</div>
