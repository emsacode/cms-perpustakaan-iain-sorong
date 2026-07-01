<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col md:flex-row font-sans">
    
    <!-- Sidebar (Desktop) / Drawer (Mobile) -->
    <aside :class="[
      'w-full md:w-60 bg-white border-b md:border-b-0 md:border-r border-slate-200 shadow-sm transition-all duration-300 md:block',
      mobileMenuOpen ? 'block' : 'hidden md:block'
    ]">
      <div class="h-14 flex items-center justify-between px-5 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
          <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center font-bold text-white text-md shadow-md shadow-emerald-600/10">
            P
          </div>
          <span class="font-extrabold text-md text-emerald-800">
            IAIN Sorong
          </span>
        </div>
        <!-- Close Mobile Menu -->
        <button @click="mobileMenuOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Navigation Links -->
      <nav class="p-3 space-y-0.5">
        <Link 
          href="/admin" 
          :class="[
            'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all duration-200 group border',
            $page.component === 'Admin/Dashboard' 
              ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50 font-semibold' 
              : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-transparent'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
          </svg>
          Dasbor Utama
        </Link>

        <Link 
          href="/admin/reservations" 
          :class="[
            'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all duration-200 group border',
            $page.component === 'Admin/Reservations' 
              ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50 font-semibold' 
              : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-transparent'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          Reservasi Ruangan
        </Link>

        <Link 
          href="/admin/pages" 
          :class="[
            'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all duration-200 group border',
            $page.component === 'Admin/Pages' 
              ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50 font-semibold' 
              : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-transparent'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Halaman Dinamis
        </Link>

        <Link 
          href="/admin/newspapers" 
          :class="[
            'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all duration-200 group border',
            $page.component === 'Admin/Newspapers' 
              ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50 font-semibold' 
              : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-transparent'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:scale-105 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v6a2 2 0 01-2 2h-2m-4-6h.01M19 10h-6M19 14h-6M19 18h-6" />
          </svg>
          Koran Digital
        </Link>
      </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      
      <!-- Top Header / Navigation Bar -->
      <header class="h-14 bg-white/80 border-b border-slate-200/80 backdrop-blur-md flex items-center justify-between px-6 sticky top-0 z-20 shadow-sm/10">
        
        <!-- Toggle button (Mobile Only) -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-slate-500 hover:text-slate-800 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div class="hidden md:block text-slate-500 text-xs font-medium">
          <span>Sistem Manajemen Perpustakaan IAIN Sorong</span>
        </div>

        <!-- Profile / Status -->
        <div class="flex items-center gap-3">
          <div class="flex flex-col text-right">
            <span class="text-xs font-bold text-slate-800">Administrator</span>
            <span class="text-[10px] text-emerald-600 font-semibold">Pustakawan Utama</span>
          </div>
          <div class="w-8.5 h-8.5 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm shadow-sm">
            AD
          </div>
        </div>
      </header>

      <!-- Main Body -->
      <main class="flex-1 p-5 md:p-6 overflow-y-auto">
        <slot />
      </main>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);
</script>
