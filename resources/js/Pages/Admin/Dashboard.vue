<template>
  <AdminLayout>
    <div class="space-y-6 text-slate-700 font-sans">
      
      <!-- Top Title Bar -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Selamat Datang, Admin!
          </h1>
          <p class="text-slate-500 text-xs mt-1 font-medium">
            Dasbor Utama - IAIN Sorong • {{ currentDate }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <div class="relative">
            <button class="w-9.5 h-9.5 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all shadow-sm relative">
              <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Main Overview Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Stats Grid (8 cols) -->
        <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
          
          <!-- Card 1: Total Buku -->
          <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between hover:border-emerald-500/20 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="space-y-1">
              <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Total Buku</span>
              <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ stats.total_books || 0 }}</h3>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                  </svg>
                  +2.5%
                </span>
              </div>
            </div>
            <div class="w-11.5 h-11.5 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
          </div>

          <!-- Card 2: Total Jurnal -->
          <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between hover:border-teal-500/20 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="space-y-1">
              <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Total Jurnal</span>
              <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ stats.total_journals || 0 }}</h3>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                  </svg>
                  +1.1%
                </span>
              </div>
            </div>
            <div class="w-11.5 h-11.5 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>

          <!-- Card 3: Total Skripsi -->
          <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between hover:border-indigo-500/20 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="space-y-1">
              <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Total Skripsi</span>
              <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ stats.total_theses || 0 }}</h3>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                  </svg>
                  +0.8%
                </span>
              </div>
            </div>
            <div class="w-11.5 h-11.5 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
              </svg>
            </div>
          </div>

          <!-- Card 4: Anggota Aktif -->
          <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between hover:border-amber-500/20 transition-all duration-300 shadow-sm hover:shadow-md">
            <div class="space-y-1">
              <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider block">Anggota Aktif</span>
              <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ stats.active_members || 0 }}</h3>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                  </svg>
                  +4.2%
                </span>
              </div>
            </div>
            <div class="w-11.5 h-11.5 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H22V18C22 15.7909 20.2091 14 18 14C17.1593 14 16.3813 14.2583 15.7355 14.7011M17 20H13.7355M17 20V18C17 16.9248 16.5752 15.9488 15.885 15.228M13.7355 20C13.9048 19.37 14 18.7004 14 18C14 15.0065 11.9935 12.5807 9.22799 12.0911M9.22799 12.0911C10.2798 11.5303 11 10.3475 11 9C11 6.79086 9.20914 5 7 5C4.79086 5 3 6.79086 3 9C3 10.3475 3.72025 11.5303 4.77201 12.0911M9.22799 12.0911C8.61866 12.3811 7.85408 12.55 7 12.55C6.14592 12.55 5.38134 12.3811 4.77201 12.0911M4.77201 12.0911C2.00651 12.5807 0 15.0065 0 18V20H13.7355" />
              </svg>
            </div>
          </div>

        </div>

        <!-- Right: Charts & Trends (4 cols) -->
        <div class="lg:col-span-4">
          <div class="bg-white border border-slate-200/80 p-5 rounded-2xl h-full flex flex-col justify-between shadow-sm">
            <div>
              <h4 class="text-sm font-bold text-slate-800 tracking-tight">Statistik Perpustakaan Bulan Ini</h4>
              <p class="text-slate-400 text-xs mt-0.5 font-medium">Trends</p>
            </div>
            
            <!-- Beautiful inline SVG Line Chart -->
            <div class="h-32 w-full mt-4 flex items-end">
              <svg class="w-full h-full text-emerald-500" viewBox="0 0 100 40" preserveAspectRatio="none">
                <!-- Grid lines -->
                <line x1="0" y1="10" x2="100" y2="10" stroke="#f1f5f9" stroke-dasharray="2 2" stroke-width="0.8"></line>
                <line x1="0" y1="20" x2="100" y2="20" stroke="#f1f5f9" stroke-dasharray="2 2" stroke-width="0.8"></line>
                <line x1="0" y1="30" x2="100" y2="30" stroke="#f1f5f9" stroke-dasharray="2 2" stroke-width="0.8"></line>

                <!-- Gradient area under curve -->
                <defs>
                  <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.15"></stop>
                    <stop offset="100%" stop-color="#10b981" stop-opacity="0"></stop>
                  </linearGradient>
                </defs>
                <path d="M 0,35 Q 20,25 40,28 T 80,15 T 100,5 L 100,40 L 0,40 Z" fill="url(#chartGradient)"></path>

                <!-- Line path -->
                <path d="M 0,35 Q 20,25 40,28 T 80,15 T 100,5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                
                <!-- Pulsing indicator point -->
                <circle cx="100" cy="5" r="2.2" fill="#10b981" class="animate-pulse"></circle>
              </svg>
            </div>

            <!-- Small legend info -->
            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-4 border-t border-slate-100 pt-3 font-medium">
              <span>Minggu 1</span>
              <span>Minggu 2</span>
              <span>Minggu 3</span>
              <span>Minggu 4</span>
            </div>
          </div>
        </div>

      </div>

      <!-- Bottom Failed Harvest Logs Panel -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-4">
          <div class="flex items-center gap-2.5">
            <div class="w-3.5 h-3.5 rounded-full bg-red-50 border border-red-100 flex items-center justify-center">
              <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
            </div>
            <h2 class="text-md font-bold text-slate-900 tracking-tight">
              Catatan Panen Gagal Terbaru (Failed Harvest Logs)
            </h2>
          </div>
          
          <!-- Search & Filter Controls -->
          <div class="flex items-center gap-2 self-end">
            <div class="relative">
              <input 
                type="text" 
                v-model="searchQuery" 
                placeholder="Cari..."
                class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 w-44 transition-colors focus:bg-white"
              />
            </div>
            <select 
              v-model="filterSource"
              class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-emerald-500/50 cursor-pointer hover:bg-slate-100 transition-colors"
            >
              <option value="all">Semua</option>
              <option value="eprints">EPrints</option>
              <option value="slims">SLiMS</option>
              <option value="ojs">OJS</option>
            </select>
          </div>
        </div>

        <!-- Table view -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="text-slate-500 font-bold border-b border-slate-200 bg-slate-50/50">
                <th class="py-2.5 px-4">ID Panen</th>
                <th class="py-2.5 px-4">Sumber Data</th>
                <th class="py-2.5 px-4">Tanggal</th>
                <th class="py-2.5 px-4">Jam</th>
                <th class="py-2.5 px-4">Keterangan Gagal</th>
                <th class="py-2.5 px-4 text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              
              <!-- Empty state -->
              <tr v-if="filteredLogs.length === 0">
                <td colspan="6" class="py-12 text-center text-slate-400 font-medium italic">
                  Tidak ada catatan kegagalan pemanenan data.
                </td>
              </tr>

              <!-- Log Rows -->
              <tr 
                v-else 
                v-for="log in filteredLogs" 
                :key="log.id"
                class="border-b border-slate-100 hover:bg-slate-50/40 transition-colors text-slate-600"
              >
                <td class="py-3 px-4 font-mono text-slate-500">HW00{{ log.id }}</td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 border border-slate-200 text-slate-600">
                    {{ log.source_type }}
                  </span>
                </td>
                <td class="py-3 px-4 text-slate-700">{{ formatOnlyDate(log.failed_at || log.created_at) }}</td>
                <td class="py-3 px-4 text-slate-500">{{ formatOnlyTime(log.failed_at || log.created_at) }}</td>
                <td class="py-3 px-4 max-w-xs truncate text-slate-700" :title="log.error_message">
                  {{ log.error_message }}
                </td>
                <td class="py-3 px-4 text-center">
                  <span 
                    class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase border"
                    :class="[
                      log.source_type === 'ojs' 
                        ? 'bg-red-50 text-red-600 border-red-100' 
                        : 'bg-amber-50 text-amber-700 border-amber-200/50'
                    ]"
                  >
                    {{ log.source_type === 'ojs' ? 'GAGAL' : 'Warning' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  stats: Object,
  failedHarvests: Array
});

// Search & Filter state
const searchQuery = ref('');
const filterSource = ref('all');

// Current date formatted dynamically
const currentDate = new Date().toLocaleDateString('id-ID', {
  day: '2-digit',
  month: '2-digit',
  year: 'numeric'
}).replace(/\//g, '.');

// Filtered logs computed property
const filteredLogs = computed(() => {
  if (!props.failedHarvests) return [];
  return props.failedHarvests.filter(log => {
    const matchesSearch = log.error_message.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          log.source_type.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesSource = filterSource.value === 'all' || log.source_type === filterSource.value;
    return matchesSearch && matchesSource;
  });
});

// Formatting functions
const formatOnlyDate = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  }); // e.g. "24 Oct 2023"
};

const formatOnlyTime = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit'
  }); // e.g. "14:15"
};
</script>
