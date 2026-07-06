<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }" 
      x-init="
          if(darkMode) { document.documentElement.classList.add('dark'); }
          else { document.documentElement.classList.remove('dark'); }
          $watch('darkMode', val => { 
              if(val) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); }
              else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }
          })
      }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'UPT Perpustakaan IAIN Sorong' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-foreground min-h-screen">
    {{ $slot }}
    @livewireScripts
</body>
</html>
