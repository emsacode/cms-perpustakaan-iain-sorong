@props(['index' => null, 'title' => '', 'url' => '#', 'code' => ''])

@if($index !== null)
    <!-- Inline Citation Citation -->
    <span x-data="{ open: false }" class="inline-block relative">
        <span @mouseenter="open = true" 
              @mouseleave="open = false"
              class="align-super text-[9px] font-bold text-indigo-500 hover:text-indigo-600 select-none px-1 py-0.5 bg-indigo-500/10 rounded ml-0.5 mr-0.5 cursor-pointer">
            [{{ $index }}]
        </span>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-150 transform"
             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-100 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
             class="absolute bottom-5 left-1/2 -translate-x-1/2 bg-popover text-popover-foreground border border-border px-3 py-2 rounded-lg shadow-md z-30 text-[10px] w-48 text-left leading-snug"
             style="display: none;"
             x-cloak>
            <div class="font-semibold text-foreground mb-0.5">Rujukan Artikel</div>
            <div class="text-muted-foreground line-clamp-2">{{ $title }}</div>
        </div>
    </span>
@else
    <!-- Web Preview Component -->
    <div x-data="{ activeTab: 'preview' }" class="border border-border rounded-lg bg-card overflow-hidden my-4 shadow-sm">
        <!-- Tabs Header -->
        <div class="flex items-center justify-between px-4 py-2 border-b border-border bg-muted/30">
            <div class="flex gap-2">
                <button @click="activeTab = 'preview'" 
                        type="button"
                        :class="activeTab === 'preview' ? 'bg-background text-foreground shadow-sm font-medium' : 'text-muted-foreground hover:text-foreground'"
                        class="px-2.5 py-1 rounded-md text-xs transition-all focus:outline-none">
                    Preview
                </button>
                <button @click="activeTab = 'code'" 
                        type="button"
                        :class="activeTab === 'code' ? 'bg-background text-foreground shadow-sm font-medium' : 'text-muted-foreground hover:text-foreground'"
                        class="px-2.5 py-1 rounded-md text-xs transition-all focus:outline-none">
                    Code
                </button>
            </div>
            
            <!-- Copy button for code -->
            <x-copy-button :value="$code" />
        </div>
        
        <!-- Tab Content -->
        <div>
            <!-- Preview Panel -->
            <div x-show="activeTab === 'preview'" class="p-4 bg-muted/5 min-h-[160px] flex items-center justify-center">
                <!-- Sandbox layout wrapper -->
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
            
            <!-- Code Panel -->
            <div x-show="activeTab === 'code'" style="display: none;" x-cloak>
                <pre class="p-4 text-xs font-mono bg-muted/20 overflow-x-auto text-muted-foreground leading-relaxed"><code>{{ $code }}</code></pre>
            </div>
        </div>
    </div>
@endif
