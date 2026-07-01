<template>
  <AdminLayout>
    <div class="space-y-6 text-slate-700 font-sans">
      
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Pengelolaan Halaman Dinamis (CMS)
          </h1>
          <p class="text-slate-500 text-xs mt-1 font-medium">
            Edit dan kelola konten halaman statis seperti Profil, Visi-Misi, Sejarah, dan Tata Tertib.
          </p>
        </div>
        <div>
          <button 
            @click="createNewPage" 
            class="px-4 py-2.5 rounded-lg text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-sm flex items-center gap-2 cursor-pointer"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Halaman Baru
          </button>
        </div>
      </div>

      <!-- Main Layout: Sidebar List + Editor Panel -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Left: Pages List -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 space-y-3 shadow-sm lg:col-span-1">
          <h3 class="text-xs font-bold text-slate-400 px-2 uppercase tracking-wider">
            Daftar Halaman
          </h3>

          <div v-if="pages.length === 0" class="text-center py-8 text-slate-400 text-sm italic">
            Belum ada halaman. Klik tombol di atas untuk membuat.
          </div>

          <div v-else class="space-y-1">
            <button
              v-for="page in pages"
              :key="page.id"
              @click="selectPage(page)"
              :class="[
                'w-full text-left px-4 py-2.5 rounded-lg transition-all duration-200 border flex items-center justify-between cursor-pointer',
                selectedPageId === page.id
                  ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50 font-semibold shadow-sm'
                  : 'text-slate-700 hover:bg-slate-50 border-transparent'
              ]"
            >
              <div class="flex flex-col min-w-0">
                <span class="truncate text-sm">{{ page.title }}</span>
                <span class="text-[10px] text-slate-400 font-mono mt-0.5">/{{ page.slug }}</span>
              </div>
              <span class="w-1.5 h-1.5 rounded-full" :class="page.is_active ? 'bg-emerald-500' : 'bg-slate-300'"></span>
            </button>
          </div>
        </div>

        <!-- Right: Editor Panel -->
        <div class="lg:col-span-2">
          
          <div v-if="!editorOpen" class="bg-white border border-dashed border-slate-300 rounded-2xl h-96 flex flex-col items-center justify-center text-slate-400 gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span class="text-sm font-medium">Pilih halaman dari daftar kiri untuk mengedit, atau buat halaman baru.</span>
          </div>

          <form v-else @submit.prevent="savePage" class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
              <span>{{ form.id ? 'Edit Halaman' : 'Buat Halaman Baru' }}</span>
              <button 
                type="button" 
                @click="editorOpen = false" 
                class="text-xs font-semibold text-slate-400 hover:text-slate-600 cursor-pointer"
              >
                Batal
              </button>
            </h3>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              
              <!-- Title -->
              <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Judul Halaman</label>
                <input 
                  type="text" 
                  v-model="form.title" 
                  @input="autoGenerateSlug" 
                  required
                  placeholder="Contoh: Visi & Misi"
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:border-emerald-500/50 focus:bg-white focus:outline-none text-slate-800 text-sm transition-colors"
                />
              </div>

              <!-- Slug -->
              <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Slug (URL Path)</label>
                <input 
                  type="text" 
                  v-model="form.slug" 
                  required
                  placeholder="Contoh: visi-misi"
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:border-emerald-500/50 focus:bg-white focus:outline-none text-slate-800 text-sm font-mono transition-colors"
                />
              </div>

            </div>

            <!-- Content Editor (HTML Textarea) -->
            <div class="space-y-2">
              <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Isi Konten (Format HTML)</label>
              <textarea 
                v-model="form.content" 
                rows="12"
                required
                placeholder="Tulis konten HTML Anda di sini..."
                class="w-full p-4 bg-slate-50 border border-slate-200 rounded-lg focus:border-emerald-500/50 focus:bg-white focus:outline-none text-slate-800 text-sm font-mono leading-relaxed transition-colors"
              ></textarea>
              <span class="text-xs text-slate-400">Anda dapat menulis tag HTML standar seperti &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, dan &lt;li&gt; untuk mengatur tata letak tulisan.</span>
            </div>

            <!-- Active Toggle & Button -->
            <div class="flex items-center justify-between border-t border-slate-100 pt-5">
              
              <div class="flex items-center gap-3">
                <input 
                  type="checkbox" 
                  id="is_active" 
                  v-model="form.is_active" 
                  class="w-4 h-4 text-emerald-600 bg-white border-slate-300 rounded focus:ring-emerald-500 cursor-pointer"
                />
                <label for="is_active" class="text-sm font-medium text-slate-600 select-none cursor-pointer">
                  Aktifkan Halaman (Tampilkan di Publik)
                </label>
              </div>

              <button 
                type="submit" 
                :disabled="submitting"
                class="px-5 py-2.5 rounded-lg text-sm font-bold bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white transition-colors shadow-sm flex items-center gap-2 cursor-pointer"
              >
                <span v-if="submitting">Menyimpan...</span>
                <span v-else>Simpan Halaman</span>
              </button>

            </div>

          </form>

        </div>

      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  pages: Array
});

const editorOpen = ref(false);
const selectedPageId = ref(null);
const submitting = ref(false);

const form = reactive({
  id: null,
  title: '',
  slug: '',
  content: '',
  is_active: true
});

const createNewPage = () => {
  form.id = null;
  form.title = '';
  form.slug = '';
  form.content = '';
  form.is_active = true;
  selectedPageId.value = null;
  editorOpen.value = true;
};

const selectPage = (page) => {
  form.id = page.id;
  form.title = page.title;
  form.slug = page.slug;
  form.content = page.content;
  form.is_active = page.is_active === 1 || page.is_active === true;
  selectedPageId.value = page.id;
  editorOpen.value = true;
};

const autoGenerateSlug = () => {
  if (!form.id) { // Only auto-generate for new pages
    form.slug = form.title
      .toLowerCase()
      .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
      .replace(/\s+/g, '-')        // replace spaces with -
      .replace(/-+/g, '-');        // collapse dashes
  }
};

const savePage = () => {
  submitting.value = true;
  router.post('/admin/pages', form, {
    preserveScroll: true,
    onSuccess: () => {
      submitting.value = false;
      editorOpen.value = false;
      selectedPageId.value = null;
    },
    onError: () => {
      submitting.value = false;
    }
  });
};
</script>
