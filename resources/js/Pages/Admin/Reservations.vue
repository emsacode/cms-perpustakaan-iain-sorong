<template>
  <AdminLayout>
    <div class="space-y-6 text-slate-700 font-sans">
      
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Persetujuan Reservasi Ruangan
          </h1>
          <p class="text-slate-500 text-xs mt-1 font-medium">
            Konfirmasi, setujui, atau tolak permohonan peminjaman ruangan multimedia dan ruang diskusi mahasiswa.
          </p>
        </div>
      </div>

      <!-- Main Panel / Table -->
      <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
        
        <!-- Table view (Desktop) -->
        <div class="overflow-x-auto hidden md:block">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50/50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <th class="px-6 py-3.5">Pemohon</th>
                <th class="px-6 py-3.5">Ruangan</th>
                <th class="px-6 py-3.5">Tanggal Booking</th>
                <th class="px-6 py-3.5">Sesi Waktu</th>
                <th class="px-6 py-3.5">Status</th>
                <th class="px-6 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="reservations.data.length === 0">
                <td colspan="6" class="text-center py-12 text-slate-400 font-medium italic">
                  Belum ada pengajuan reservasi ruangan saat ini.
                </td>
              </tr>
              <tr v-for="item in reservations.data" :key="item.id" class="hover:bg-slate-50/40 transition-colors text-slate-600">
                <!-- User Info -->
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-semibold text-slate-900 text-sm">{{ item.name }}</span>
                    <span class="text-xs text-slate-500 mt-0.5">{{ item.nim_nip }} • {{ item.email }}</span>
                  </div>
                </td>
                
                <!-- Room -->
                <td class="px-6 py-4 text-sm text-slate-800 font-semibold">
                  {{ item.room_name }}
                </td>
                
                <!-- Date -->
                <td class="px-6 py-4 text-sm text-slate-700">
                  {{ formatDate(item.booking_date) }}
                </td>

                <!-- Session -->
                <td class="px-6 py-4 text-sm text-slate-700">
                  <span class="px-2 py-1 rounded bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-600">
                    {{ item.session_time }}
                  </span>
                </td>

                <!-- Status Badge -->
                <td class="px-6 py-4">
                  <span class="px-2.5 py-1 rounded-full text-xs font-bold tracking-wider inline-flex items-center gap-1.5 border" :class="[
                    item.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200/50' : '',
                    item.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/50' : '',
                    item.status === 'rejected' ? 'bg-red-50 text-red-700 border-red-200/50' : '',
                    item.status === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200/50' : ''
                  ]">
                    <span class="w-1.5 h-1.5 rounded-full" :class="[
                      item.status === 'pending' ? 'bg-amber-500' : '',
                      item.status === 'approved' ? 'bg-emerald-500' : '',
                      item.status === 'rejected' ? 'bg-red-500' : '',
                      item.status === 'completed' ? 'bg-blue-500' : ''
                    ]"></span>
                    {{ item.status }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2" v-if="item.status === 'pending'">
                    <button 
                      @click="updateStatus(item.id, 'approved')" 
                      class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-sm cursor-pointer"
                    >
                      Setujui
                    </button>
                    <button 
                      @click="updateStatus(item.id, 'rejected')" 
                      class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 transition-colors cursor-pointer"
                    >
                      Tolak
                    </button>
                  </div>
                  <span v-else class="text-xs text-slate-400 font-medium">Selesai diproses</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Card View (Mobile) -->
        <div class="md:hidden divide-y divide-slate-100 p-4">
          <div v-if="reservations.data.length === 0" class="text-center py-8 text-slate-400 text-sm italic">
            Belum ada pengajuan reservasi ruangan saat ini.
          </div>
          <div v-for="item in reservations.data" :key="item.id" class="py-4 space-y-3 first:pt-0 last:pb-0 text-slate-600">
            <div class="flex items-start justify-between">
              <div>
                <h4 class="font-semibold text-slate-900 text-sm">{{ item.name }}</h4>
                <p class="text-xs text-slate-500 mt-0.5">{{ item.nim_nip }} ({{ item.email }})</p>
              </div>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-1 border" :class="[
                item.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200/50' : '',
                item.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/50' : '',
                item.status === 'rejected' ? 'bg-red-50 text-red-700 border-red-200/50' : '',
                item.status === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200/50' : ''
              ]">
                {{ item.status }}
              </span>
            </div>
            
            <div class="grid grid-cols-2 gap-2 text-xs text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100">
              <div>
                <span class="text-slate-400 font-medium block">Ruangan</span>
                <span class="font-bold text-slate-800">{{ item.room_name }}</span>
              </div>
              <div>
                <span class="text-slate-400 font-medium block">Tanggal / Sesi</span>
                <span class="font-bold text-slate-800">{{ formatDate(item.booking_date) }} - {{ item.session_time }}</span>
              </div>
            </div>

            <div class="flex gap-2" v-if="item.status === 'pending'">
              <button 
                @click="updateStatus(item.id, 'approved')" 
                class="flex-1 py-2 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-sm cursor-pointer"
              >
                Setujui
              </button>
              <button 
                @click="updateStatus(item.id, 'rejected')" 
                class="flex-1 py-2 rounded-lg text-xs font-bold bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 transition-colors cursor-pointer"
              >
                Tolak
              </button>
            </div>
          </div>
        </div>

      </div>

      <!-- Pagination Footer -->
      <div v-if="reservations.links && reservations.links.length > 3" class="flex items-center justify-between border-t border-slate-200 pt-4 font-medium">
        <span class="text-xs text-slate-500">
          Menampilkan {{ reservations.from }} - {{ reservations.to }} dari {{ reservations.total }} pengajuan
        </span>
        <div class="flex items-center gap-1">
          <Link
            v-for="(link, k) in reservations.links"
            :key="k"
            :href="link.url || '#'"
            v-html="link.label"
            :class="[
              'px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all duration-200',
              link.active 
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200/50 font-bold' 
                : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 border-transparent',
              !link.url ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer'
            ]"
          />
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  reservations: Object
});

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const updateStatus = (id, status) => {
  if (confirm(`Apakah Anda yakin ingin memperbarui status reservasi ini menjadi "${status}"?`)) {
    router.post(`/admin/reservations/${id}/status`, { status }, {
      preserveScroll: true,
      onSuccess: () => {
        // Notification is automatically flashed in session
      }
    });
  }
};
</script>
