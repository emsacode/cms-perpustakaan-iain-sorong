<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          sidebarOpen: false
      }" 
      x-init="
          if(darkMode) { document.documentElement.classList.add('dark'); }
          else { document.documentElement.classList.remove('dark'); }
          $watch('darkMode', val => { 
              if(val) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); }
              else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }
          })
      "
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'UPT Perpustakaan IAIN Sorong - Admin CMS' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-foreground min-h-screen flex">
    
    <!-- Sidebar Navigation -->
    <x-sidebar />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen lg:pl-0">
        <!-- Top Navbar Mobile Only -->
        <header class="h-16 border-b border-border bg-card flex items-center justify-between px-6 lg:hidden sticky top-0 z-30">
            <div class="flex items-center gap-2">
                <span class="font-bold text-lg bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">IAIN Sorong</span>
            </div>
            <button @click="sidebarOpen = true" class="p-2 -mr-2 text-muted-foreground hover:text-foreground rounded-md focus:outline-none">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </header>

        <!-- Main Dashboard View slot -->
        <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
