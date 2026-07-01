<!-- Desktop Sidebar -->
<aside class="w-64 bg-card border-r border-border sticky top-0 h-screen hidden lg:flex flex-col z-20">
    <x-sidebar-header />
    <x-sidebar-menu />
    <x-sidebar-footer />
</aside>

<!-- Mobile Slide-over Sidebar (Sheet Component) -->
<div x-show="sidebarOpen" 
     class="fixed inset-0 z-50 flex lg:hidden" 
     style="display: none;" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop Blur -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm" 
         aria-hidden="true"></div>

    <!-- Sidebar Slide Transition -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative flex w-full max-w-xs flex-1 flex-col bg-card border-r border-border pt-5 pb-4">
         
        <!-- Close Button -->
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <x-sidebar-header />
        <x-sidebar-menu />
        <x-sidebar-footer />
    </div>
</div>
