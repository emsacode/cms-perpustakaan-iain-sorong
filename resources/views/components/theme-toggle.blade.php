<button x-on:click="darkMode = !darkMode" 
        type="button"
        class="relative flex items-center justify-center p-1.5 rounded-md border border-border bg-background text-muted-foreground hover:text-foreground transition-colors focus:outline-none"
        aria-label="Toggle Theme">
    
    <!-- Sun Icon (Show in Dark Mode) -->
    <svg x-show="darkMode" 
         style="display: none;"
         class="w-4 h-4 text-amber-400 transition-transform duration-300 rotate-0" 
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 2v2"/>
        <path d="M12 20v2"/>
        <path d="M4.93 4.93l1.41 1.41"/>
        <path d="M17.66 17.66l1.41 1.41"/>
        <path d="M2 12h2"/>
        <path d="M20 12h2"/>
        <path d="M6.34 17.66l-1.41 1.41"/>
        <path d="M19.07 4.93l-1.41 1.41"/>
    </svg>
    
    <!-- Moon Icon (Show in Light Mode) -->
    <svg x-show="!darkMode" 
         style="display: none;"
         class="w-4 h-4 text-slate-700 transition-transform duration-300 rotate-0" 
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
    </svg>
</button>
