@props(['messages' => []])

<div x-data="{ 
         messages: @js($messages),
         scrollDown() {
             this.$nextTick(() => {
                 const el = this.$refs.chatbox;
                 if (el) {
                     el.scrollTop = el.scrollHeight;
                 }
             });
         }
     }"
     x-init="
         scrollDown();
         $watch('messages', () => scrollDown());
     "
     class="flex flex-col h-[480px] border border-border rounded-lg bg-card overflow-hidden shadow-sm">
    
    <!-- Chat Header -->
    <div class="px-4 py-3 border-b border-border bg-muted/20 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-semibold text-foreground">Asisten Analitik AI</span>
        </div>
        <span class="text-[10px] text-muted-foreground font-mono">Status: Connected</span>
    </div>
    
    <!-- Messages Box -->
    <div x-ref="chatbox" 
         class="flex-1 p-4 overflow-y-auto space-y-4 scroll-smooth bg-card">
         
        <template x-for="(msg, index) in messages" :key="index">
            <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                <div :class="msg.role === 'user' ? 'bg-primary text-primary-foreground rounded-br-none' : 'bg-muted text-foreground rounded-bl-none'"
                     class="max-w-[85%] rounded-lg px-4 py-2.5 text-xs shadow-sm leading-relaxed border border-border/40">
                     
                    <!-- Sender Header -->
                    <div class="text-[9px] font-semibold mb-1 opacity-75"
                         x-text="msg.role === 'user' ? 'Anda' : 'Analitik AI'"></div>
                         
                    <!-- Content -->
                    <div class="space-y-2 whitespace-pre-line" x-html="msg.content"></div>
                </div>
            </div>
        </template>
        
        <!-- Empty State -->
        <template x-if="messages.length === 0">
            <div class="h-full flex flex-col items-center justify-center text-center text-muted-foreground p-6">
                <div class="p-3 bg-secondary rounded-full border border-border mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500">
                        <path d="M12 8V4H8"/>
                        <rect width="16" height="12" x="4" y="8" rx="2"/>
                        <path d="M2 14h2"/>
                        <path d="M20 14h2"/>
                        <path d="M15 13v2"/>
                        <path d="M9 13v2"/>
                    </svg>
                </div>
                <h4 class="text-xs font-semibold text-foreground">Asisten Analitik AI Aktif</h4>
                <p class="text-[10px] mt-1 max-w-[200px] leading-relaxed">Ketik instruksi analitik di bawah untuk menganalisis statistik pembaca, status berita, dan pertumbuhan pengunjung.</p>
            </div>
        </template>
    </div>
</div>
