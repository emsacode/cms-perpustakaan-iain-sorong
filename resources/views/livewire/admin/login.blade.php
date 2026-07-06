<div class="min-h-screen flex flex-col md:flex-row bg-background">
    
    <!-- Left Panel: Banner / Image & Testimonial -->
    <div class="relative hidden md:flex md:w-[40%] lg:w-[35%] flex-col justify-between p-10 text-white overflow-hidden shrink-0 select-none bg-cover bg-center"
         style="background-image: url('{{ asset('images/library_login_left.jpg') }}');">
        
        <!-- Dark Overlay for Readability -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/80 z-0"></div>

        <!-- Top Header: Logo & Branding -->
        <div class="relative z-10 flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary text-primary-foreground shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-sm leading-tight">UPT Perpustakaan</span>
                <span class="text-xs font-semibold leading-none text-indigo-300">IAIN Sorong</span>
            </div>
        </div>

        <!-- Bottom Content: Quote -->
        <div class="relative z-10 space-y-5">
            <blockquote class="text-xl lg:text-2xl font-medium leading-relaxed italic text-white/95">
                "Perpustakaan adalah jantung universitas, tempat ilmu diproduksi, jendela dunia dibuka, dan inspirasi dikembangkan."
            </blockquote>
            <div class="border-l-2 border-primary pl-4">
                <p class="font-bold text-sm tracking-wide text-white">Dr. Hamzah Khaeriyah, M.Ag.</p>
                <p class="text-[11px] text-white/60 mt-0.5 uppercase tracking-wider font-semibold">Rektor IAIN Sorong</p>
            </div>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="flex-1 flex items-center justify-center p-8 sm:p-12 md:p-16 lg:p-20 xl:p-24 bg-card">
        <div class="w-full max-w-[400px] space-y-8">
            
            <!-- Mobile Header Only -->
            <div class="flex md:hidden items-center gap-3 mb-6">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary text-primary-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm leading-tight text-foreground">UPT Perpustakaan</span>
                    <span class="text-xs font-semibold leading-none text-primary">IAIN Sorong</span>
                </div>
            </div>

            <!-- Form Title -->
            <div class="space-y-2">
                <h2 class="text-2xl font-bold tracking-tight text-foreground">Selamat Datang Kembali</h2>
                <p class="text-xs text-muted-foreground leading-relaxed">Kelola data permohonan peminjaman ruangan, sirkulasi keanggotaan, dan katalog UPT Perpustakaan IAIN Sorong.</p>
            </div>

            <!-- Form -->
            <form wire:submit="login" class="space-y-5">
                
                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-bold text-muted-foreground block">Alamat Email</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground/80 lucide lucide-mail">
                            <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                        <input wire:model="email" 
                               type="email" 
                               id="email" 
                               placeholder="nama@iainsorong.ac.id" 
                               class="w-full pl-9 pr-4 py-2 text-sm bg-background border border-border rounded-lg text-foreground focus:outline-none focus:ring-1 focus:ring-ring"
                               required
                               autocomplete="email">
                    </div>
                    @error('email')
                        <span class="text-[11px] text-destructive font-medium block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-bold text-muted-foreground block">Kata Sandi</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground/80 lucide lucide-lock">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input wire:model="password" 
                               type="password" 
                               id="password" 
                               placeholder="Masukkan kata sandi..." 
                               class="w-full pl-9 pr-4 py-2 text-sm bg-background border border-border rounded-lg text-foreground focus:outline-none focus:ring-1 focus:ring-ring"
                               required
                               autocomplete="current-password">
                    </div>
                    @error('password')
                        <span class="text-[11px] text-destructive font-medium block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password Row -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-muted-foreground select-none">
                        <input wire:model="remember" type="checkbox" class="rounded border-border text-primary focus:ring-primary h-3.5 w-3.5">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="font-semibold text-primary hover:underline">Lupa password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-2.5 text-xs font-bold rounded-lg text-white bg-primary hover:bg-primary/90 transition-colors shadow cursor-pointer mt-2 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="login">Masuk ke Dashboard</span>
                    <span wire:loading wire:target="login" class="flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>

            <!-- Separator -->
            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-border"></div>
                </div>
                <div class="relative flex justify-center text-xs font-semibold uppercase">
                    <span class="bg-card px-3 text-[10px] text-muted-foreground tracking-wider">ATAU</span>
                </div>
            </div>

            <!-- Google Login Button -->
            <button type="button" 
                    class="w-full py-2.5 text-xs font-bold rounded-lg border border-border bg-background hover:bg-muted text-foreground transition-colors flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                <!-- Google Icon SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chrome">
                    <circle cx="12" cy="12" r="10"/><path d="M12 12 21.9 8.2"/><path d="m12 12-7.5 7.5"/><path d="M12 12V2.1"/><path d="M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"/>
                </svg>
                Masuk dengan SSO Google
            </button>

            <!-- Footer Signup Text -->
            <p class="text-center text-[11px] text-muted-foreground leading-relaxed">
                Belum memiliki akun? <a href="#" class="font-bold text-primary hover:underline">Hubungi Admin UPT</a> untuk pendaftaran akses petugas.
            </p>
        </div>
    </div>
</div>
