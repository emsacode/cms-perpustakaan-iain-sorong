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
                    item.status === 'key_picked_up' ? 'bg-indigo-50 text-indigo-700 border-indigo-200/50' : '',
                    item.status === 'returned' ? 'bg-sky-50 text-sky-700 border-sky-200/50' : '',
                    item.status === 'overdue' ? 'bg-rose-50 text-rose-700 border-rose-200/50' : '',
                    item.status === 'cancelled' ? 'bg-slate-100 text-slate-600 border-slate-200/50' : '',
                    item.status === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200/50' : ''
                  ]">
                    <span class="w-1.5 h-1.5 rounded-full" :class="[
                      item.status === 'pending' ? 'bg-amber-500' : '',
                      item.status === 'approved' ? 'bg-emerald-500' : '',
                      item.status === 'rejected' ? 'bg-red-500' : '',
                      item.status === 'key_picked_up' ? 'bg-indigo-500' : '',
                      item.status === 'returned' ? 'bg-sky-500' : '',
                      item.status === 'overdue' ? 'bg-rose-500' : '',
                      item.status === 'cancelled' ? 'bg-slate-400' : '',
                      item.status === 'completed' ? 'bg-blue-500' : ''
                    ]"></span>
                    {{ getStatusLabel(item.status) }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right">
                  <button 
                    @click="viewDetail(item)" 
                    class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200/80 text-slate-700 border border-slate-200 transition-colors cursor-pointer inline-flex items-center gap-1.5"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/><circle cx="12" cy="12" r="3"/></svg>
                    Lihat Detail
                  </button>
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
                item.status === 'key_picked_up' ? 'bg-indigo-50 text-indigo-700 border-indigo-200/50' : '',
                item.status === 'returned' ? 'bg-sky-50 text-sky-700 border-sky-200/50' : '',
                item.status === 'overdue' ? 'bg-rose-50 text-rose-700 border-rose-200/50' : '',
                item.status === 'cancelled' ? 'bg-slate-100 text-slate-600 border-slate-200/50' : '',
                item.status === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200/50' : ''
              ]">
                {{ getStatusLabel(item.status) }}
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

            <div class="flex gap-2">
              <button 
                @click="viewDetail(item)" 
                class="w-full py-2 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 transition-colors cursor-pointer flex items-center justify-center gap-1.5"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/><circle cx="12" cy="12" r="3"/></svg>
                Lihat Detail
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

      <!-- Detail Modal -->
      <div v-if="isOpenDetail && selectedItem" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          
          <!-- Background backdrop -->
          <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="closeDetail" aria-hidden="true"></div>

          <!-- Space-holding element for centering -->
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

          <!-- Modal panel -->
          <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
            
            <!-- Close Button -->
            <button @click="closeDetail" class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-50 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>

            <div class="px-6 pt-6 pb-4 border-b border-slate-100">
              <h3 class="text-lg font-bold text-slate-900" id="modal-title">
                Detail Permohonan Peminjaman
              </h3>
              <p class="text-xs text-slate-500 mt-0.5 font-medium">
                Rincian data permohonan booking ruangan oleh mahasiswa / staf.
              </p>
            </div>

            <div class="p-6 space-y-5">
              
              <!-- Pemohon Info -->
              <div class="space-y-2">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Informasi Pemohon</h4>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/40 space-y-2.5">
                  <div class="grid grid-cols-3 gap-1 text-xs">
                    <span class="text-slate-400 font-medium">Nama</span>
                    <span class="col-span-2 font-bold text-slate-800">{{ selectedItem.name }}</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1 text-xs">
                    <span class="text-slate-400 font-medium">NIM / NIP</span>
                    <span class="col-span-2 font-bold text-slate-800">{{ selectedItem.nim_nip }}</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1 text-xs">
                    <span class="text-slate-400 font-medium">Email</span>
                    <span class="col-span-2 font-bold text-slate-800">{{ selectedItem.email }}</span>
                  </div>
                  <div v-if="selectedItem.link_surat" class="grid grid-cols-3 gap-1 text-xs pt-2 border-t border-slate-200/50">
                    <span class="text-slate-400 font-medium">Dokumen Surat</span>
                    <span class="col-span-2">
                      <a :href="selectedItem.link_surat" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-bold inline-flex items-center gap-1">
                        Unduh Surat Permohonan
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                      </a>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Booking Info -->
              <div class="space-y-2">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rincian Booking</h4>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/40 space-y-2.5">
                  <div class="grid grid-cols-3 gap-1 text-xs">
                    <span class="text-slate-400 font-medium">Ruang Dipinjam</span>
                    <span class="col-span-2 font-bold text-slate-800">{{ selectedItem.room_name }}</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1 text-xs">
                    <span class="text-slate-400 font-medium">Tanggal Booking</span>
                    <span class="col-span-2 font-bold text-slate-800">{{ formatDate(selectedItem.booking_date) }}</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1 text-xs">
                    <span class="text-slate-400 font-medium">Sesi Waktu</span>
                    <span class="col-span-2 font-bold text-slate-800">{{ selectedItem.session_time }}</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1 text-xs">
                    <span class="text-slate-400 font-medium">Status</span>
                    <span class="col-span-2">
                      <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-1 border" :class="[
                        selectedItem.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200/50' : '',
                        selectedItem.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/50' : '',
                        selectedItem.status === 'rejected' ? 'bg-red-50 text-red-700 border-red-200/50' : '',
                        selectedItem.status === 'key_picked_up' ? 'bg-indigo-50 text-indigo-700 border-indigo-200/50' : '',
                        selectedItem.status === 'returned' ? 'bg-sky-50 text-sky-700 border-sky-200/50' : '',
                        selectedItem.status === 'overdue' ? 'bg-rose-50 text-rose-700 border-rose-200/50' : '',
                        selectedItem.status === 'cancelled' ? 'bg-slate-100 text-slate-600 border-slate-200/50' : '',
                        selectedItem.status === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200/50' : ''
                      ]">
                        {{ getStatusLabel(selectedItem.status) }}
                      </span>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Rejection Reason Callout (If Rejected) -->
              <div v-if="selectedItem.status === 'rejected' && selectedItem.rejection_reason" class="space-y-2">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alasan Penolakan</h4>
                <div class="bg-red-50/50 border border-red-100 text-red-800 p-4 rounded-xl text-xs leading-relaxed font-medium">
                  {{ selectedItem.rejection_reason }}
                </div>
              </div>

              <!-- Audit Logs & Inventory Details (If Picked Up / Returned) -->
              <div v-if="selectedItem.picked_up_at || selectedItem.returned_at || selectedItem.notes_inventory" class="space-y-2">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Log Serah Terima & Inventaris</h4>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/40 space-y-2 text-xs">
                  <div v-if="selectedItem.picked_up_at" class="grid grid-cols-3 gap-1">
                    <span class="text-slate-400 font-medium">Kunci Diserahkan</span>
                    <span class="col-span-2 font-semibold text-slate-700">{{ formatTimestamp(selectedItem.picked_up_at) }}</span>
                  </div>
                  <div v-if="selectedItem.returned_at" class="grid grid-cols-3 gap-1">
                    <span class="text-slate-400 font-medium">Kunci Dikembalikan</span>
                    <span class="col-span-2 font-semibold text-slate-700">{{ formatTimestamp(selectedItem.returned_at) }}</span>
                  </div>
                  <div v-if="selectedItem.notes_inventory" class="grid grid-cols-3 gap-1 pt-2 border-t border-slate-200/50">
                    <span class="text-slate-400 font-medium">Catatan Alat</span>
                    <span class="col-span-2 font-semibold text-slate-700 italic">"{{ selectedItem.notes_inventory }}"</span>
                  </div>
                </div>
              </div>

              <!-- Interactive Actions -->
              
              <!-- Action 1: Pending (Needs Approval/Rejection) -->
              <div v-if="selectedItem.status === 'pending'" class="space-y-3 pt-3 border-t border-slate-100">
                <div v-if="showRejectInput" class="space-y-2">
                  <label class="text-[11px] font-bold text-slate-500 block">Alasan Penolakan (Wajib)</label>
                  <textarea 
                    v-model="rejectionReasonText"
                    placeholder="Tulis alasan penolakan di sini... (contoh: ruangan sedang digunakan untuk rapat internal rektorat)" 
                    class="w-full text-xs p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 h-20 resize-none"
                  ></textarea>
                  <div class="flex gap-2">
                    <button 
                      @click="submitRejection" 
                      :disabled="!rejectionReasonText.trim()"
                      class="flex-1 py-2 text-xs font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                    >
                      Kirim Penolakan
                    </button>
                    <button 
                      @click="showRejectInput = false" 
                      class="px-3 py-2 text-xs font-bold rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                    >
                      Batal
                    </button>
                  </div>
                </div>
                
                <div v-else class="flex gap-3">
                  <button 
                    @click="updateStatusInModal('approved')" 
                    class="flex-1 py-2 text-xs font-bold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors cursor-pointer"
                  >
                    Setujui Permohonan
                  </button>
                  <button 
                    @click="showRejectInput = true" 
                    class="flex-1 py-2 text-xs font-bold rounded-lg text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors cursor-pointer"
                  >
                    Tolak Permohonan
                  </button>
                </div>
              </div>

              <!-- Action 2: Approved (Key release step) -->
              <div v-slot:default v-if="selectedItem.status === 'approved'" class="space-y-3 pt-3 border-t border-slate-100">
                <div class="space-y-2">
                  <label class="text-[11px] font-bold text-slate-500 block">Catatan Inventaris / Barang Tambahan (Opsional)</label>
                  <input 
                    v-model="notesInventoryText"
                    type="text"
                    placeholder="Contoh: Remote AC, remote proyektor, HDMI cable"
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                  />
                </div>
                <button 
                  @click="submitKeyPickup" 
                  class="w-full py-2.5 text-xs font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors cursor-pointer"
                >
                  Serahkan Kunci Ruangan
                </button>
              </div>

              <!-- Action 3: Key Picked Up (Key return step) -->
              <div v-if="selectedItem.status === 'key_picked_up'" class="space-y-3 pt-3 border-t border-slate-100">
                <div class="space-y-2">
                  <label class="text-[11px] font-bold text-slate-500 block">Catatan Inventaris Pengembalian (Opsional)</label>
                  <input 
                    v-model="notesInventoryText"
                    type="text"
                    placeholder="Contoh: Remote AC dikembalikan, kabel HDMI lengkap"
                    class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500"
                  />
                </div>
                <button 
                  @click="submitKeyReturn" 
                  class="w-full py-2.5 text-xs font-bold rounded-lg text-white bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer"
                >
                  Konfirmasi Pengembalian Kunci
                </button>
              </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex justify-end">
              <button @click="closeDetail" class="px-4 py-2 text-xs font-bold rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer">
                Tutup
              </button>
            </div>

          </div>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  reservations: Object
});

const isOpenDetail = ref(false);
const selectedItem = ref(null);
const showRejectInput = ref(false);
const rejectionReasonText = ref('');
const notesInventoryText = ref('');

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const formatTimestamp = (timestampStr) => {
  if (!timestampStr) return '';
  const date = new Date(timestampStr);
  const dateFormatted = date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  return `${dateFormatted}, ${hours}:${minutes} WIT`;
};

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Menunggu Persetujuan',
    approved: 'Disetujui',
    rejected: 'Ditolak',
    key_picked_up: 'Kunci Diambil',
    returned: 'Kunci Dikembalikan',
    overdue: 'Terlambat',
    cancelled: 'Dibatalkan',
    completed: 'Selesai'
  };
  return labels[status] || status;
};

const viewDetail = (item) => {
  selectedItem.value = item;
  isOpenDetail.value = true;
  showRejectInput.value = false;
  rejectionReasonText.value = item.rejection_reason || '';
  notesInventoryText.value = item.notes_inventory || '';
};

const closeDetail = () => {
  isOpenDetail.value = false;
  selectedItem.value = null;
};

const updateStatusInModal = (status) => {
  if (confirm(`Apakah Anda yakin ingin menyetujui reservasi ini?`)) {
    router.post(`/admin/reservations/${selectedItem.value.id}/status`, { status }, {
      preserveScroll: true,
      onSuccess: () => {
        closeDetail();
      }
    });
  }
};

const submitRejection = () => {
  if (!rejectionReasonText.value.trim()) return;
  router.post(`/admin/reservations/${selectedItem.value.id}/status`, {
    status: 'rejected',
    rejection_reason: rejectionReasonText.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      closeDetail();
    }
  });
};

const submitKeyPickup = () => {
  router.post(`/admin/reservations/${selectedItem.value.id}/status`, {
    status: 'key_picked_up',
    notes_inventory: notesInventoryText.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      closeDetail();
    }
  });
};

const submitKeyReturn = () => {
  router.post(`/admin/reservations/${selectedItem.value.id}/status`, {
    status: 'returned',
    notes_inventory: notesInventoryText.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      closeDetail();
    }
  });
};
</script>
