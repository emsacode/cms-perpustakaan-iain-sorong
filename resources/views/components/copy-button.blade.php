@props(['value'])

<button x-data="{ copied: false }" 
        x-on:click="navigator.clipboard.writeText('{{ $value }}'); copied = true; setTimeout(() => copied = false, 2000)"
        type="button"
        class="inline-flex items-center justify-center p-1.5 rounded-lg border border-border bg-background text-muted-foreground hover:text-foreground transition-all focus:outline-none hover:bg-accent hover:text-accent-foreground"
        title="Salin ke Clipboard">
    
    <!-- Clipboard Icon -->
    <svg x-show="!copied" 
         class="w-3.5 h-3.5 transition-opacity" 
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
    </svg>
    
    <!-- Checked Success Icon (Green) -->
    <svg x-show="copied" 
         style="display: none;"
         x-cloak
         class="w-3.5 h-3.5 text-emerald-500 animate-in fade-in zoom-in duration-200" 
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
    </svg>
</button>
