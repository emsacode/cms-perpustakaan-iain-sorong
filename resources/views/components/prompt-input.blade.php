@props(['placeholder' => 'Ketik instruksi analitik AI...', 'model' => ''])

<div x-data="{ 
         resize() { 
             $refs.textarea.style.height = 'auto'; 
             $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px'; 
         } 
     }" 
     x-init="resize()"
     class="relative flex items-center w-full bg-background border border-border rounded-lg shadow-sm focus-within:ring-1 focus-within:ring-ring">
    
    <textarea x-ref="textarea"
              x-on:input="resize()"
              wire:model.defer="{{ $model }}"
              placeholder="{{ $placeholder }}"
              rows="1"
              class="w-full pl-4 pr-12 py-3 bg-transparent text-foreground text-sm focus:outline-none resize-none overflow-hidden min-h-[44px] max-h-[160px]"
              {{ $attributes }}></textarea>
              
    <!-- Submit Button inside input -->
    <div class="absolute right-2 bottom-2">
        <button type="submit" 
                class="flex items-center justify-center w-7 h-7 rounded-md bg-primary text-primary-foreground hover:bg-primary/95 transition-colors focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up">
                <path d="m5 12 7-7 7 7"/>
                <path d="M12 19V5"/>
            </svg>
        </button>
    </div>
</div>
