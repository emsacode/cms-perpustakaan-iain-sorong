<template>
  <AdminLayout>
    <div class="space-y-6 text-slate-700 font-sans">
      
      <!-- Top Title Bar -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Monitoring Koran &amp; Majalah Digital
          </h1>
          <p class="text-slate-500 text-xs mt-1 font-medium">
            Pemantauan hasil scraping otomatis, verifikasi data edisi cetak elektronik, dan akses dokumen awan.
          </p>
        </div>
        <div>
          <button 
            @click="loadNewspapers" 
            :disabled="loading"
            class="px-4 py-2 rounded-lg text-xs font-bold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 transition-colors shadow-sm flex items-center gap-2 cursor-pointer disabled:opacity-50"
          >
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-4 w-4 text-slate-500" 
              :class="{ 'animate-spin': loading }"
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18" />
            </svg>
            {{ loading ? 'Memuat...' : 'Refresh Data' }}
          </button>
        </div>
      </div>

      <!-- SKELETON OR STATS BANNER -->
      <div v-if="loading && originalData.length === 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div v-for="i in 4" :key="i" class="bg-white border border-slate-200/80 p-5 rounded-2xl animate-pulse space-y-2">
          <div class="h-3 bg-slate-100 rounded w-1/3"></div>
          <div class="h-8 bg-slate-100 rounded w-2/3"></div>
        </div>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Koran -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between shadow-sm">
          <div class="space-y-1">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider block">Total Edisi Koran</span>
            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ stats.totalKoran }}</h3>
          </div>
          <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v6a2 2 0 01-2 2h-2m-4-6h.01M19 10h-6M19 14h-6M19 18h-6" />
            </svg>
          </div>
        </div>

        <!-- Card 2: Total Penerbit -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between shadow-sm">
          <div class="space-y-1">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider block">Total Kategori Media</span>
            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ stats.totalCategories }}</h3>
          </div>
          <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
          </div>
        </div>

        <!-- Card 3: Total Halaman -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between shadow-sm">
          <div class="space-y-1">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider block">Akumulasi Halaman</span>
            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ stats.totalPages }}</h3>
          </div>
          <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>

        <!-- Card 4: Terbaru -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex items-center justify-between shadow-sm">
          <div class="space-y-1 min-w-0 flex-1 pr-2">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider block">Edisi Terbaru Hari Ini</span>
            <h3 class="text-xs font-bold text-slate-900 truncate" :title="stats.latestNewspaper">
              {{ stats.latestNewspaper }}
            </h3>
          </div>
          <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- MAIN CONTAINER: TABLE & FILTERS -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
        
        <!-- Filters header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
          <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
            Daftar Koran Digital
            <span class="bg-slate-100 border border-slate-200 text-slate-600 font-mono text-[10px] px-2 py-0.5 rounded-full">
              {{ filteredLogs.length }} Data
            </span>
          </h2>
          
          <div class="flex items-center gap-2 self-end">
            <!-- Search field -->
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Cari judul..."
              class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 w-44 transition-colors focus:bg-white"
            />
            
            <!-- Category select -->
            <select 
              v-model="filterCategory"
              class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-emerald-500/50 cursor-pointer hover:bg-slate-100 transition-colors"
            >
              <option value="all">Semua Kategori</option>
              <option v-for="cat in uniqueCategories" :key="cat" :value="cat">
                {{ cat }}
              </option>
            </select>
          </div>
        </div>

        <!-- ERROR STATE -->
        <div v-if="error" class="text-center py-12 border border-dashed border-slate-200 rounded-xl space-y-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div class="text-sm font-bold text-slate-800">Koneksi API DDAG Gagal</div>
          <p class="text-xs text-slate-400 max-w-md mx-auto">Gagal menghubungkan data koran digital dari API Gateway Halsen.</p>
          <button 
            @click="loadNewspapers" 
            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition-colors cursor-pointer"
          >
            Coba Lagi
          </button>
        </div>

        <!-- SKELETON LOADER TABLE -->
        <div v-else-if="loading && originalData.length === 0" class="space-y-2">
          <div v-for="i in 5" :key="i" class="h-14 bg-slate-50 animate-pulse rounded-lg w-full"></div>
        </div>

        <!-- DATA TABLE -->
        <div v-else class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="text-slate-500 font-bold border-b border-slate-200 bg-slate-50/50 uppercase tracking-wider text-[10px]">
                <th class="py-3 px-4 w-16">Cover</th>
                <th class="py-3 px-4">Judul Koran</th>
                <th class="py-3 px-4">Kategori</th>
                <th class="py-3 px-4 text-center">Halaman</th>
                <th class="py-3 px-4">Tanggal Terbit</th>
                <th class="py-3 px-4 text-right">Berkas</th>
              </tr>
            </thead>
            <tbody>
              
              <!-- Empty state -->
              <tr v-if="paginatedData.length === 0">
                <td colspan="6" class="py-12 text-center text-slate-400 font-medium italic">
                  Tidak ada edisi koran digital yang ditemukan.
                </td>
              </tr>

              <!-- Rows -->
              <tr 
                v-else 
                v-for="item in paginatedData" 
                :key="item.book_id"
                class="border-b border-slate-100 hover:bg-slate-50/40 transition-colors text-slate-600"
              >
                <!-- Cover thumbnail -->
                <td class="py-2.5 px-4">
                  <div class="w-10 aspect-[3/4] rounded bg-slate-50 border border-slate-200/50 overflow-hidden shadow-sm">
                    <img 
                      :src="item.cover_url" 
                      :alt="item.title"
                      class="w-full h-full object-cover object-top"
                      loading="lazy"
                      onerror="this.src='/images/katalog-default.png'"
                    />
                  </div>
                </td>

                <!-- Title -->
                <td class="py-2.5 px-4 font-semibold text-slate-900 max-w-xs truncate">
                  {{ item.title }}
                </td>

                <!-- Category -->
                <td class="py-2.5 px-4">
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-blue-50 border border-blue-100 text-blue-700">
                    {{ item.category_name }}
                  </span>
                </td>

                <!-- Pages -->
                <td class="py-2.5 px-4 text-center font-mono font-medium">
                  {{ item.pages }} hal
                </td>

                <!-- Published Date -->
                <td class="py-2.5 px-4">
                  {{ formatIndoDate(item.published_at) }}
                </td>

                <!-- Action Button -->
                <td class="py-2.5 px-4 text-right">
                  <a 
                    :href="item.file_url" 
                    target="_blank"
                    class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 inline-flex items-center gap-1 transition-colors cursor-pointer"
                  >
                    Open PDF
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- CLIENT-SIDE PAGINATION FOOTER -->
        <div v-if="filteredLogs.length > 10" class="flex items-center justify-between border-t border-slate-100 pt-4 font-medium text-xs text-slate-500">
          <span>
            Menampilkan {{ (currentPage - 1) * 10 + 1 }} - {{ Math.min(currentPage * 10, filteredLogs.length) }} dari {{ filteredLogs.length }} edisi
          </span>
          <div class="flex items-center gap-1.5">
            <button 
              @click="prevPage" 
              :disabled="currentPage === 1"
              class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
            >
              Prev
            </button>
            <span class="px-2 text-slate-700 font-bold">
              {{ currentPage }} / {{ totalPages }}
            </span>
            <button 
              @click="nextPage" 
              :disabled="currentPage === totalPages"
              class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
            >
              Next
            </button>
          </div>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const loading = ref(false);
const error = ref(false);
const originalData = ref([]);

// Search & Filter state
const searchQuery = ref('');
const filterCategory = ref('all');
const currentPage = ref(1);

// Statistics computed state
const stats = computed(() => {
  if (originalData.value.length === 0) {
    return {
      totalKoran: 0,
      totalCategories: 0,
      totalPages: 0,
      latestNewspaper: '-'
    };
  }

  const totalKoran = originalData.value.length;
  const categories = new Set(originalData.value.map(item => item.category_name));
  const totalPages = originalData.value.reduce((sum, item) => sum + (item.pages || 0), 0);
  const latestNewspaper = originalData.value[0]?.title || '-';

  return {
    totalKoran,
    totalCategories: categories.size,
    totalPages,
    latestNewspaper
  };
});

// Unique categories computed for filtering select
const uniqueCategories = computed(() => {
  return [...new Set(originalData.value.map(item => item.category_name))].sort();
});

// Fetch function
const loadNewspapers = async () => {
  loading.value = true;
  error.value = false;

  try {
    // Fetch from local Laravel proxy endpoint to bypass CORS and hide API secrets
    const dataResponse = await fetch('/admin/newspapers/data');

    if (!dataResponse.ok) throw new Error('Data fetch failed');
    const result = await dataResponse.json();

    if (result.success && Array.isArray(result.data)) {
      originalData.value = result.data;
      currentPage.value = 1;
    } else {
      throw new Error('Invalid API response');
    }
  } catch (err) {
    console.error('Koran Digital API loading error:', err);
    error.value = true;
  } finally {
    loading.value = false;
  }
};

// Filtered array computed
const filteredLogs = computed(() => {
  return originalData.value.filter(item => {
    const matchesSearch = item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          item.category_name.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesCategory = filterCategory.value === 'all' || item.category_name === filterCategory.value;
    return matchesSearch && matchesCategory;
  });
});

// Paginated computed list (10 rows)
const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * 10;
  return filteredLogs.value.slice(start, start + 10);
});

// Total pages computed
const totalPages = computed(() => {
  return Math.ceil(filteredLogs.value.length / 10) || 1;
});

// Page controls
const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};
const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

// Indonesian date utility
const formatIndoDate = (dateStr) => {
  if (!dateStr) return '-';
  const date = new Date(dateStr);
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

// Trigger fetch on mount
onMounted(() => {
  loadNewspapers();
});
</script>
