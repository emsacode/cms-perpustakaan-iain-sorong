<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use App\Models\AiAnalyticsSnapshot;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@iainsorong.ac.id'],
            [
                'name' => 'Admin Perpustakaan',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active'
            ]
        );

        // Create a dummy editor
        User::updateOrCreate(
            ['email' => 'editor1@iainsorong.ac.id'],
            [
                'name' => 'Fajar Editor',
                'password' => bcrypt('password'),
                'role' => 'editor',
                'status' => 'active'
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor2@iainsorong.ac.id'],
            [
                'name' => 'Rina Staff',
                'password' => bcrypt('password'),
                'role' => 'editor',
                'status' => 'inactive'
            ]
        );

        // 2. Create Taxonomies
        $categories = [
            ['name' => 'News & Update', 'slug' => 'news-update'],
            ['name' => 'Jurusan', 'slug' => 'jurusan'],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan'],
            ['name' => 'Layanan', 'slug' => 'layanan'],
        ];
        $catIds = [];
        foreach ($categories as $cat) {
            $c = \App\Models\Category::updateOrCreate(['slug' => $cat['slug']], ['name' => $cat['name']]);
            $catIds[$cat['slug']] = $c->id;
        }

        $tags = [
            ['name' => 'Literasi', 'slug' => 'literasi'],
            ['name' => 'Perpustakaan', 'slug' => 'perpustakaan'],
            ['name' => 'Skripsi', 'slug' => 'skripsi'],
            ['name' => 'Mahasiswa', 'slug' => 'mahasiswa'],
        ];
        $tagIds = [];
        foreach ($tags as $t) {
            $tagObj = \App\Models\Tag::updateOrCreate(['slug' => $t['slug']], ['name' => $t['name']]);
            $tagIds[$t['slug']] = $tagObj->id;
        }

        // 3. Create Dummy Articles
        $articles = [
            [
                'title' => 'Tips Mencari Referensi Cepat Menggunakan OPAC Perpustakaan',
                'content' => 'Sistem Online Public Access Catalog (OPAC) adalah pintu gerbang utama untuk menemukan koleksi buku fisik di UPT Perpustakaan IAIN Sorong. Agar pencarian Anda lebih efisien, berikut adalah beberapa langkah praktis yang bisa Anda coba:<br><br><strong>1. Gunakan Kata Kunci yang Spesifik</strong><br>Alih-alih mencari kata kunci umum seperti "Islam", gunakan istilah yang lebih mendalam seperti "Sejarah Peradaban Islam Klasik" or "Hukum Ekonomi Syariah".<br><br><strong>2. Manfaatkan Fitur Penyaringan (Filter)</strong><br>Saring hasil pencarian berdasarkan tahun terbit atau nama pengarang untuk memperkecil daftar hasil pencarian.<br><br><strong>3. Catat Nomor Panggil (Call Number) Buku</strong><br>Setelah menemukan buku yang dicari, catat nomor panggilnya (misalnya: 2X4.1 BAS s) untuk mempermudah pencarian fisik buku di rak perpustakaan.',
                'excerpt' => 'Panduan praktis mencari dan menemukan buku referensi fisik secara cepat menggunakan katalog online OPAC Perpustakaan IAIN Sorong.',
                'image' => 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1000&auto=format&fit=crop',
                'status' => 'published',
                'views_count' => 1200,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(5),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Panduan Parafrase Efektif untuk Menghindari Plagiarisme Turnitin',
                'content' => 'Turnitin digunakan di UPT Perpustakaan IAIN Sorong untuk mengukur kesamaan naskah tugas akhir dengan karya tulis ilmiah lain yang telah terbit. Agar skripsi Anda lolos uji kemiripan, lakukan teknik parafrase yang benar:<br><br><strong>1. Pahami Konsep Utamanya Dahulu</strong><br>Baca paragraf referensi hingga Anda benar-benar mengerti maksud penulis asli sebelum mulai menulis ulang.<br><br><strong>2. Gunakan Sinonim dan Ubah Struktur Kalimat</strong><br>Ubah kalimat aktif menjadi pasif atau sebaliknya, dan ganti kata-kata tertentu dengan sinonim yang padan tanpa mengubah arti asli kalimat.<br><br><strong>3. Tuliskan Sumber Kutipan Secara Jelas</strong><br>Parafrase tanpa sitasi tetap dianggap sebagai tindakan plagiarisme. Selalu sertakan nama pengarang dan tahun terbit sumber asli.',
                'excerpt' => 'Tips teknik parafrase kalimat yang benar agar naskah tugas akhir Anda lolos uji kemiripan (similarity test) Turnitin.',
                'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=1000&auto=format&fit=crop',
                'status' => 'published',
                'views_count' => 2450,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(4),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['skripsi', 'mahasiswa']
            ],
            [
                'title' => 'Cara Mendaftar & Mengakses Jutaan E-Resource Perpustakaan Nasional',
                'content' => 'UPT Perpustakaan IAIN Sorong telah terintegrasi dengan layanan e-resources Perpustakaan Nasional RI (PNRI). Seluruh civitas akademika dapat mendaftar untuk membaca jutaan e-book, jurnal ilmiah internasional, dan manuskrip gratis:<br><br><strong>1. Buat Akun Anggota Online PNRI</strong><br>Kunjungi portal keanggotaan resmi PNRI melalui tautan yang tersedia di panduan e-resources perpustakaan kita.<br><br><strong>2. Masuk dan Cari Jurnal Langganan</strong><br>PNRI melanggan database dunia terkemuka seperti ProQuest, EBSCO, dan SpringerLink yang dapat Anda akses secara cuma-cuma.<br><br><strong>3. Unduh PDF untuk Referensi Riset</strong><br>Gunakan fitur pencarian untuk menemukan artikel relevan dan unduh langsung berkas PDF resminya.',
                'excerpt' => 'Langkah pendaftaran anggota dan cara mengakses jurnal internasional gratis melalui integrasi e-resources Perpustakaan Nasional.',
                'image' => 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?q=80&w=1000&auto=format&fit=crop',
                'status' => 'published',
                'views_count' => 1890,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(3),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['literasi', 'mahasiswa']
            ],
            [
                'title' => 'Tips Menjaga Fokus Membaca Buku Tebal di Ruang Baca Utama',
                'content' => 'Membaca buku referensi tebal sering kali terasa membosankan dan melelahkan. UPT Perpustakaan IAIN Sorong menyediakan ruang baca yang tenang untuk mendukung kenyamanan Anda:<br><br><strong>1. Gunakan Teknik Pomodoro</strong><br>Membacalah selama 25 menit secara fokus, lalu beristirahatlah selama 5 menit untuk menyegarkan pikiran Anda sebelum kembali membaca.<br><br><strong>2. Jauhkan Gadget dari Meja Baca</strong><br>Matikan notifikasi atau simpan ponsel pintar Anda di dalam tas untuk menghindari gangguan fokus selama membaca.<br><br><strong>3. Buat Catatan Kecil (Mind Map)</strong><br>Tulis poin-poin penting pada buku catatan kecil untuk memetakan ide dan mempermudah ingatan Anda.',
                'excerpt' => 'Strategi meningkatkan fokus and efisiensi membaca buku referensi akademis tebal di lingkungan perpustakaan.',
                'image' => 'https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?q=80&w=1000&auto=format&fit=crop',
                'status' => 'published',
                'views_count' => 980,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(2),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Panduan Memanfaatkan Mendeley untuk Manajemen Referensi Tugas Akhir',
                'content' => 'Menyusun daftar pustaka secara manual sering kali menimbulkan kesalahan penulisan. Gunakan Mendeley Reference Manager untuk merapikan sitasi skripsi Anda secara otomatis:<br><br><strong>1. Instal Mendeley Desktop dan Plugin Word</strong><br>Pasang aplikasi Mendeley di laptop Anda dan hubungkan dengan aplikasi pengolah kata Microsoft Word.<br><br><strong>2. Impor File PDF Jurnal Anda</strong><br>Seret dan lepas file PDF jurnal ilmiah yang Anda unduh ke dalam perpustakaan Mendeley untuk mengekstrak metadatanya secara otomatis.<br><br><strong>3. Sisipkan Sitasi Instan</strong><br>Gunakan plugin Mendeley di Microsoft Word untuk menyisipkan kutipan dan menghasilkan daftar pustaka sekali klik dengan format APA atau style lainnya.',
                'excerpt' => 'Langkah mudah menggunakan Mendeley Reference Manager untuk membuat sitasi dan daftar pustaka skripsi secara otomatis.',
                'image' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=1000&auto=format&fit=crop',
                'status' => 'published',
                'views_count' => 3120,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(1),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['skripsi', 'literasi']
            ],
            [
                'title' => 'Cara Cerdas Mencari Referensi Akademik di Era AI',
                'content' => '<p>Artikel ini membahas tiga hal utama: pentingnya <strong>referensi akademik</strong> yang berkualitas, peran <strong>AI untuk penelitian</strong>, dan langkah praktis memverifikasi sumber agar pencarian jurnal tetap akurat. Bukti terkini menunjukkan AI paling berguna sebagai pendamping pencarian, bukan pengganti database ilmiah dan penilaian kritis peneliti (Bolanos et al., 2024; Tomczyk et al., 2024).</p><h2>Pentingnya Referensi Akademik yang Berkualitas</h2><p>Cara mencari referensi akademik yang baik menentukan mutu argumen, ketepatan sitasi, dan kredibilitas karya ilmiah sejak tahap proposal hingga skripsi selesai (Alahi & Yesmin, 2024). Meta-analisis pada 23 studi dengan 22.054 peserta menunjukkan hubungan positif sedang antara <strong>literasi informasi</strong> dan performa akademik, dengan effect size gabungan r = 0.33 (Ashiq et al., 2025). Studi lain pada mahasiswa tugas akhir juga menemukan korelasi positif antara kemampuan literasi informasi dan kompetensi riset (Alahi & Yesmin, 2024).</p><p>Masalahnya, banyak mahasiswa tetap memilih blog, Wikipedia, atau koran online karena terasa relevan, meski kualitas otoritasnya tidak selalu memadai untuk tulisan akademik (Haliq et al., 2023). Penelitian lain menunjukkan penggunaan teknologi saja tidak otomatis meningkatkan kemampuan mencari dan menilai informasi (Haliq et al., 2023). Karena itu, mencari sumber bukan sekadar menemukan artikel, tetapi menilai relevansi, akurasi, otoritas, dan penggunaan etisnya (Haliq et al., 2023).</p><ul><li><strong>Referensi kuat</strong> membantu membangun argumen yang dapat dipertanggungjawabkan (Tergembay et al., 2026).</li><li><strong>Literasi informasi</strong> menurunkan risiko kewalahan oleh banjir sumber digital (Olivia & Desriyeni, 2026).</li><li>Pelatihan terstruktur meningkatkan kemampuan menilai dan menggunakan sumber ilmiah (Cagitla & Balid-Bordado, 2026).</li></ul><h2>Peran AI dalam Penelitian Ilmiah</h2><p>AI mempercepat pencarian, penyaringan, peringkasan, dan pengelompokan tema dalam literatur ilmiah, terutama ketika jumlah artikel sudah sangat besar (Mandić, 2025; De La Torre-López et al., 2023). Tinjauan besar atas alat AI untuk systematic literature review juga menegaskan bahwa AI kini paling banyak dipakai untuk screening dan extraction, dengan tantangan utama pada usability, transparency, dan standardisasi evaluasi (Bolanos et al., 2024). Sistem berbasis retrieval-augmented generation (RAG) dirancang agar model mengambil informasi dari dokumen yang dapat diverifikasi, sehingga mengurangi halusinasi (Bolanos et al., 2024).</p><p>Namun, bukti komparatif menunjukkan hasil AI tidak selalu lebih baik dari alat tradisional. Pada pengujian topik e-commerce, Scopus dan Web of Science unggul dalam akurasi dan kualitas, sedangkan alat AI lebih menonjol dalam menemukan hasil unik yang mungkin terlewat metode biasa (Tomczyk et al., 2024). Kesimpulan yang paling konsisten adalah <strong>kombinasi AI dan database konvensional</strong> memberi hasil paling kuat (Tomczyk et al., 2024; Pulletikurty, 2026).</p><ul><li>AI paling efektif untuk <strong>membantu</strong>, bukan menggantikan peneliti (Whitfield & Hofmann, 2023; Oyelude, 2024).</li><li>AI dapat menghemat waktu pencarian dan manajemen sitasi (Alaa, 2024; Barame et al., 2025).</li><li>Penggunaan AI tetap perlu pengawasan karena bias, interpretabilitas, dan privasi masih menjadi isu (Mandić, 2025; Barame et al., 2025; Pulletikurty, 2026).</li></ul><h2>Langkah Praktis Memverifikasi Referensi dengan AI</h2><p>Untuk mahasiswa IAIN Sorong, langkah paling aman adalah memulai dari topik riset yang sempit, lalu memakai AI untuk memperluas istilah pencarian, bukan langsung menerima jawaban jadi (Bolanos et al., 2024). Misalnya, mahasiswa yang meneliti "literasi digital mahasiswa baru" dapat meminta AI membuat variasi kata kunci Indonesia dan Inggris, lalu menjalankannya di Google Scholar, DOAJ, atau database kampus (Godwin et al., 2025). Setelah itu, simpan artikel yang relevan ke Zotero agar sitasi lebih rapi dan jejak bacaan tidak hilang (Haliq et al., 2023; Barame et al., 2025).</p><p>Berikut alur praktis mencari dan memverifikasi referensi akademik:</p><table class="min-w-full divide-y divide-gray-200 border border-gray-200 my-5"><thead><tr class="bg-gray-50"><th class="px-4 py-2 text-left font-semibold">Langkah</th><th class="px-4 py-2 text-left font-semibold">Tujuan</th><th class="px-4 py-2 text-left font-semibold">Contoh Praktis</th><th class="px-4 py-2 text-left font-semibold">Dasar Ilmiah</th></tr></thead><tbody><tr><td class="border-t px-4 py-2">Tentukan kata kunci</td><td class="border-t px-4 py-2">Memperjelas fokus pencarian</td><td class="border-t px-4 py-2">"AI untuk penelitian" + "information literacy"</td><td class="border-t px-4 py-2">AI mendukung pencarian bahasa alami (Bolanos et al., 2024)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Cari di database ilmiah</td><td class="border-t px-4 py-2">Menjaga kualitas hasil awal</td><td class="border-t px-4 py-2">Google Scholar, Scopus, DOAJ, perpustakaan digital</td><td class="border-t px-4 py-2">Database tradisional cenderung unggul dalam akurasi (Tomczyk et al., 2024)</td></tr><tr><td class="border-t px-4 py-2">Pakai AI untuk eksplorasi</td><td class="border-t px-4 py-2">Menemukan artikel unik</td><td class="border-t px-4 py-2">Ringkasan awal, tema, paper terkait</td><td class="border-t px-4 py-2">AI sering menemukan hasil unik (Tomczyk et al., 2024)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Kelola dengan reference manager</td><td class="border-t px-4 py-2">Merapikan sitasi</td><td class="border-t px-4 py-2">Simpan PDF dan metadata di Zotero</td><td class="border-t px-4 py-2">Mendukung tugas akademik mandiri (Haliq et al., 2023)</td></tr><tr><td class="border-t px-4 py-2">Verifikasi sumber</td><td class="border-t px-4 py-2">Mencegah referensi palsu</td><td class="border-t px-4 py-2">Cek DOI, jurnal, penulis, tahun</td><td class="border-t px-4 py-2">Pelatihan menurunkan sitasi palsu (Pratama, 2025)</td></tr></tbody></table><h2>Kesalahan Umum dalam Menggunakan AI</h2><p>Kesalahan paling sering adalah terlalu percaya pada hasil AI tanpa mengecek apakah artikel itu benar-benar ada, relevan, dan terbit di jurnal yang layak. Studi pelatihan mahasiswa menunjukkan intervensi khusus dapat menurunkan referensi palsu dan meningkatkan penggunaan jurnal bereputasi (Pratama, 2025). Ini berarti masalah <strong>halusinasi sitasi</strong> nyata, tetapi bisa dikurangi dengan pelatihan yang tepat (Pratama, 2025).</p><p>Kesalahan lain adalah mengira semua hasil teratas pasti paling baik. Evaluasi kualitas jurnal justru perlu pendekatan seperti <strong>lateral reading</strong>, yaitu membuka sumber lain untuk memeriksa reputasi jurnal, kredibilitas penerbit, dan tanda-tanda predatory journal (Dale & Craft, 2021). Lingkungan akademik juga masih menunjukkan kelemahan dalam perilaku verifikasi informasi, sehingga pelatihan fact-checking tetap penting (Tergembay et al., 2026).</p><ul><li>Jangan menyalin daftar pustaka dari AI tanpa cek DOI atau metadata (Pratama, 2025).</li><li>Jangan memakai blog sebagai rujukan utama untuk karya ilmiah (Haliq et al., 2023).</li><li>Jangan abaikan isu <strong>privasi data</strong> saat mengunggah dokumen ke alat AI berbasis cloud (Barame et al., 2025).</li></ul><h2>Platform Rekomendasi untuk Penelitian</h2><p>Platform yang berguna terbagi dalam tiga fungsi: database pencarian, asisten AI, dan manajer referensi. Survei alat AI terbaru mengidentifikasi mesin pencari seperti Scite, Elicit, Consensus, Perplexity, dan SciSpace sebagai kelompok search engine, sementara alat lain berperan sebagai writing assistant (Bolanos et al., 2024). Di sisi lain, OpenAlex dan Zotero relevan untuk alur kerja yang lebih terbuka, terstruktur, dan dapat direproduksi (Barame et al., 2025).</p><table class="min-w-full divide-y divide-gray-200 border border-gray-200 my-5"><thead><tr class="bg-gray-50"><th class="px-4 py-2 text-left font-semibold">Platform</th><th class="px-4 py-2 text-left font-semibold">Fungsi Utama</th><th class="px-4 py-2 text-left font-semibold">Kekuatan</th><th class="px-4 py-2 text-left font-semibold">Catatan</th></tr></thead><tbody><tr><td class="border-t px-4 py-2">Google Scholar</td><td class="border-t px-4 py-2">Pencarian jurnal</td><td class="border-t px-4 py-2">Mudah diakses, luas</td><td class="border-t px-4 py-2">Perlu seleksi kualitas manual (Haliq et al., 2023)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">DOAJ</td><td class="border-t px-4 py-2">Jurnal open access</td><td class="border-t px-4 py-2">Baik untuk jurnal terbuka</td><td class="border-t px-4 py-2">Tidak mencakup semua bidang (Tergembay et al., 2026)</td></tr><tr><td class="border-t px-4 py-2">Crossref</td><td class="border-t px-4 py-2">Verifikasi DOI</td><td class="border-t px-4 py-2">Cek metadata artikel</td><td class="border-t px-4 py-2">Sangat berguna untuk validasi (Pratama, 2025)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Elicit / Consensus / Scite</td><td class="border-t px-4 py-2">AI untuk penelitian</td><td class="border-t px-4 py-2">Ringkasan & pencarian bahasa alami</td><td class="border-t px-4 py-2">Jangan dipakai sebagai sumber final tanpa cek silang (Bolanos et al., 2024; Whitfield & Hofmann, 2023)</td></tr><tr><td class="border-t px-4 py-2">Zotero</td><td class="border-t px-4 py-2">Manajemen referensi</td><td class="border-t px-4 py-2">Simpan, kutip, organisasi</td><td class="border-t px-4 py-2">Mendukung kerja mandiri mahasiswa (Haliq et al., 2023)</td></tr></tbody></table><h2>Verifikasi Kredibilitas Sumber</h2><p>Tips memverifikasi kredibilitas sumber dimulai dari empat pertanyaan sederhana: siapa penulisnya, di jurnal apa artikel terbit, apakah metadata cocok, dan apakah isi artikel benar-benar relevan dengan topik. Literasi informasi didefinisikan sebagai kemampuan menemukan, menilai, menggunakan, dan menyebarkan informasi secara etis, sehingga verifikasi sumber adalah bagian inti dari kompetensi riset (Alahi & Yesmin, 2024; Tergembay et al., 2026). Bukti juga menunjukkan pelatihan verifikasi digital dan media meningkatkan hasil belajar, minat, dan keterampilan berpikir kritis (Gergul et al., 2025).</p><p>Untuk praktik harian, mahasiswa dapat membuka halaman jurnal, mengecek DOI di Crossref, melihat apakah jurnal terindeks secara wajar, lalu membandingkan abstrak dengan kebutuhan penelitian. Jika AI memberi 10 artikel, jangan ambil semuanya. Pilih 3–5 yang paling relevan, baca abstraknya, dan simpan hanya yang mendukung rumusan masalah (Olivia & Desriyeni, 2026).</p><ul><li>Gunakan <strong>lateral reading</strong> untuk mengecek reputasi jurnal dan penerbit (Dale & Craft, 2021).</li><li>Cek kecocokan judul, penulis, tahun, dan DOI sebelum mengutip (Pratama, 2025).</li><li>Prioritaskan sumber yang valid, ilmiah, dan relevan dengan pertanyaan riset (Haliq et al., 2023; Tergembay et al., 2026).</li></ul><h2>Kesimpulan</h2><p>Cara mencari referensi akademik yang paling cerdas di era AI adalah menggabungkan <strong>database jurnal</strong>, alat AI, dan keterampilan verifikasi sumber. Bukti paling konsisten menunjukkan AI mempercepat pencarian dan membantu menemukan artikel unik, tetapi akurasi dan kualitas tetap lebih terjaga ketika hasil AI dicek silang dengan sumber ilmiah konvensional dan dinilai secara kritis (Tomczyk et al., 2024; Oyelude, 2024).</p><p>Bagi mahasiswa, dosen, dan peneliti pemula, kuncinya bukan memakai AI sebanyak mungkin, tetapi memakai AI dengan <strong>etis, terukur, dan terverifikasi</strong>. Dengan cara itu, referensi akademik yang dikumpulkan akan lebih kuat, relevan, dan aman digunakan dalam karya ilmiah.</p><h2>FAQ</h2><h3>Apakah AI bisa menggantikan Google Scholar?</h3><p>Tidak. Bukti terbaru menunjukkan AI lebih tepat sebagai pelengkap, sedangkan alat konvensional masih unggul dalam akurasi dan kualitas hasil (Tomczyk et al., 2024).</p><h3>Apakah semua referensi dari AI bisa dipercaya?</h3><p>Tidak. Referensi dari AI perlu diverifikasi karena sitasi palsu atau halusinasi masih sering muncul (Pratama, 2025).</p><h3>Platform apa yang cocok untuk mahasiswa pemula?</h3><p>Kombinasi Google Scholar, DOAJ, Zotero, dan satu alat AI pencari literatur adalah pilihan paling praktis untuk mulai belajar (Haliq et al., 2023; Bolanos et al., 2024).</p><h3>Mengapa literasi informasi penting dalam pencarian jurnal?</h3><p>Karena literasi informasi berkaitan positif dengan kompetensi riset dan performa akademik, sekaligus membantu mengurangi information overload (Ashiq et al., 2025; Olivia & Desriyeni, 2026).</p><h3>Bagaimana cara cepat mengecek kredibilitas jurnal?</h3><p>Gunakan lateral reading, cek DOI dan metadata, lalu lihat apakah jurnal dan penerbitnya tampak sah dan relevan (Dale & Craft, 2021; Pratama, 2025).</p><h2>Referensi</h2><p class="text-sm text-gray-500">Alaa, A. (2024). Harnessing Artificial Intelligence for Enhanced Efficiency in Academic Writing and Research. <em>Fusion: Practice and Applications</em>. https://doi.org/10.54216/fpa.160209<br>Alahi, F., & Yesmin, S. (2024). Impact of information literacy on research work performance: measuring thesis students’ competency at a public university in Bangladesh. <em>Global Knowledge, Memory and Communication</em>. https://doi.org/10.1108/gkmc-03-2024-0174<br>Ashiq, M., Hira, A., & Saeed, A. (2025). The relationship between information literacy and academic performance: a meta-analysis study. <em>Performance Measurement and Metrics</em>. https://doi.org/10.1108/pmm-04-2025-0020<br>Barame, G. K., Ngugi, J., & Sumbiri, D. (2025). Enhancing Academic Research Efficiency: A Comparative Analysis of Manual and AI-Driven Workflows with Optimized LLM-Zotero Integration. <em>Journal of Information and Technology</em>. https://doi.org/10.70619/vol5iss9pp46-55<br>Bolanos, F., Salatino, A., Osborne, F., & Motta, E. (2024). Artificial intelligence for literature reviews: opportunities and challenges. <em>Artificial Intelligence Review, 57</em>. https://doi.org/10.1007/s10462-024-10902-3<br>Cagitla, M., & Balid-Bordado, S. (2026). Strengthening Academic Information Literacy (SAIL) and Information Literacy of Research Students. <em>International Journal For Multidisciplinary Research</em>. https://doi.org/10.36948/ijfmr.2026.v08i01.68925<br>Dale, J., & Craft, A. (2021). Professional Applications of Information Literacy: Helping Researchers Learn to Evaluate Journal Quality. <em>Serials Review, 47</em>, 129 - 135. https://doi.org/10.1080/00987913.2021.1964337<br>De La Torre-López, J., Ramírez, A., & Romero, J. (2023). Artificial intelligence to automate the systematic review of scientific literature. <em>Computing, 105</em>, 2171 - 2194. https://doi.org/10.1007/s00607-023-01181-x<br>Gergul, S., Gorodnycha, L., Olkhovyk, M., & Panchenko, V. (2025). The Effectiveness of using Information Verification Services in the Training of Future Teachers. <em>WSEAS TRANSACTIONS ON COMPUTER RESEARCH</em>. https://doi.org/10.37394/232018.2025.13.28<br>Godwin, R., Soundararajan, K., Duggan, E. W., Goss, H., Atcheson, C., Wasson, E., Gosling, A. F., Archer, A., Berkowitz, D., Sherrer, M., & Melvin, R. (2025). AI-driven literature tools for enhanced medical education and research. <em>Physiology</em>. https://doi.org/10.1152/physiol.2025.40.s1.1646<br>Haliq, A., Zamzani, Z., Wiedarti, P., & Akhiruddin, A. (2023). Self-Acces in Digital Literacy: Evaluating the Quality of Information and Reliability of Sources in Writing Academic Essay. <em>Interference: Journal of Language, Literature, and Linguistics</em>. https://doi.org/10.26858/interference.v4i1.44561<br>Mandić, A. (2025). Optimizing the Research Process: The Role of AI Tools in Scientific Literature Searches. <em>2025 MIPRO 48th ICT and Electronics Convention</em>, 1159-1163. https://doi.org/10.1109/mipro65660.2025.11131993<br>Olivia, D., & Desriyeni, D. (2026). The Effect of Information Literacy on Students’ Information Overload in the Use of Digital Academic References. <em>Journal of Multidisciplinary Science: MIKAILALSYS</em>. https://doi.org/10.58578/mikailalsys.v4i2.9877<br>Oyelude, A. (2024). Artificial intelligence (AI) tools for academic research. <em>Library Hi Tech News</em>. https://doi.org/10.1108/lhtn-08-2024-0131<br>Pratama, H. (2025). Training Students to Identify and Correct Fabricated References in ChatGPT-Generated Literature Reviews. <em>Conference on English Language Teaching</em>. https://doi.org/10.24090/celti.2025.1335<br>Pulletikurty, R. R. (2026). AI TOOLS FOR LITERATURE REVIEW AND KNOWLEDGE MAPPING: EMPOWERING RESEARCH EXCELLENCE IN ACADEMIC WRITING AND PUBLISHING. <em>Journal of Emerging Technologies and Innovative Research</em>. https://doi.org/10.56975/jetir.v13i2.575657<br>Tergembay, К., Moldabayev, K., Abdrakhmanova, A., Tulebayeva, S., & Abulkassimova, G. (2026). Recognition of fake news and deepfake technologies: on the example of the academic environment in higher education. <em>Herald of Journalism</em>. https://doi.org/10.26577/hj79120268<br>Tomczyk, P., Brüggemann, P., Mergner, N., & Petrescu, M. (2024). Are AI tools better than traditional tools in literature searching? Evidence from E-commerce research. <em>Journal of Librarianship and Information Science, 58</em>, 135 - 145. https://doi.org/10.1177/09610006241295802<br>Whitfield, S., & Hofmann, M. A. (2023). Elicit: AI literature review research assistant. <em>Public Services Quarterly, 19</em>, 201 - 207. https://doi.org/10.1080/15228959.2023.2224125</p>',
                'excerpt' => 'Panduan cara mencari referensi akademik dengan AI secara etis, efektif, dan terverifikasi untuk mahasiswa, dosen, dan peneliti.',
                'image' => 'https://images.unsplash.com/photo-1677442136019-21780efad99a?q=80&w=1000&auto=format&fit=crop',
                'status' => 'published',
                'views_count' => 1540,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now(),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['literasi', 'perpustakaan']
            ],
            [
                'title' => 'Cara Cerdas Mencari Referensi Akademik di Era AI',
                'content' => '<p>Artikel ini membahas tiga hal utama: pentingnya <strong>referensi akademik</strong> yang berkualitas, peran <strong>AI untuk penelitian</strong>, dan langkah praktis memverifikasi sumber agar pencarian jurnal tetap akurat. Bukti terkini menunjukkan AI paling berguna sebagai pendamping pencarian, bukan pengganti database ilmiah dan penilaian kritis peneliti (Bolanos et al., 2024; Tomczyk et al., 2024).</p><h2>Pentingnya Referensi Akademik yang Berkualitas</h2><p>Cara mencari referensi akademik yang baik menentukan mutu argumen, ketepatan sitasi, dan kredibilitas karya ilmiah sejak tahap proposal hingga skripsi selesai (Alahi & Yesmin, 2024). Meta-analisis pada 23 studi dengan 22.054 peserta menunjukkan hubungan positif sedang antara <strong>literasi informasi</strong> dan performa akademik, dengan effect size gabungan r = 0.33 (Ashiq et al., 2025). Studi lain pada mahasiswa tugas akhir juga menemukan korelasi positif antara kemampuan literasi informasi dan kompetensi riset (Alahi & Yesmin, 2024).</p><p>Masalahnya, banyak mahasiswa tetap memilih blog, Wikipedia, atau koran online karena terasa relevan, meski kualitas otoritasnya tidak selalu memadai untuk tulisan akademik (Haliq et al., 2023). Penelitian lain menunjukkan penggunaan teknologi saja tidak otomatis meningkatkan kemampuan mencari dan menilai informasi (Haliq et al., 2023). Karena itu, mencari sumber bukan sekadar menemukan artikel, tetapi menilai relevansi, akurasi, otoritas, dan penggunaan etisnya (Haliq et al., 2023).</p><ul><li><strong>Referensi kuat</strong> membantu membangun argumen yang dapat dipertanggungjawabkan (Tergembay et al., 2026).</li><li><strong>Literasi informasi</strong> menurunkan risiko kewalahan oleh banjir sumber digital (Olivia & Desriyeni, 2026).</li><li>Pelatihan terstruktur meningkatkan kemampuan menilai dan menggunakan sumber ilmiah (Cagitla & Balid-Bordado, 2026).</li></ul><h2>Peran AI dalam Penelitian Ilmiah</h2><p>AI mempercepat pencarian, penyaringan, peringkasan, dan pengelompokan tema dalam literatur ilmiah, terutama ketika jumlah artikel sudah sangat besar (Mandić, 2025; De La Torre-López et al., 2023). Tinjauan besar atas alat AI untuk systematic literature review juga menegaskan bahwa AI kini paling banyak dipakai untuk screening dan extraction, dengan tantangan utama pada usability, transparency, dan standardisasi evaluasi (Bolanos et al., 2024). Sistem berbasis retrieval-augmented generation (RAG) dirancang agar model mengambil informasi dari dokumen yang dapat diverifikasi, sehingga mengurangi halusinasi (Bolanos et al., 2024).</p><p>Namun, bukti komparatif menunjukkan hasil AI tidak selalu lebih baik dari alat tradisional. Pada pengujian topik e-commerce, Scopus dan Web of Science unggul dalam akurasi dan kualitas, sedangkan alat AI lebih menonjol dalam menemukan hasil unik yang mungkin terlewat metode biasa (Tomczyk et al., 2024). Kesimpulan yang paling konsisten adalah <strong>kombinasi AI dan database konvensional</strong> memberi hasil paling kuat (Tomczyk et al., 2024; Pulletikurty, 2026).</p><ul><li>AI paling efektif untuk <strong>membantu</strong>, bukan menggantikan peneliti (Whitfield & Hofmann, 2023; Oyelude, 2024).</li><li>AI dapat menghemat waktu pencarian dan manajemen sitasi (Alaa, 2024; Barame et al., 2025).</li><li>Penggunaan AI tetap perlu pengawasan karena bias, interpretabilitas, dan privasi masih menjadi isu (Mandić, 2025; Barame et al., 2025; Pulletikurty, 2026).</li></ul><h2>Langkah Praktis Memverifikasi Referensi dengan AI</h2><p>Untuk mahasiswa IAIN Sorong, langkah paling aman adalah memulai dari topik riset yang sempit, lalu memakai AI untuk memperluas istilah pencarian, bukan langsung menerima jawaban jadi (Bolanos et al., 2024). Misalnya, mahasiswa yang meneliti "literasi digital mahasiswa baru" dapat meminta AI membuat variasi kata kunci Indonesia dan Inggris, lalu menjalankannya di Google Scholar, DOAJ, atau database kampus (Godwin et al., 2025). Setelah itu, simpan artikel yang relevan ke Zotero agar sitasi lebih rapi dan jejak bacaan tidak hilang (Haliq et al., 2023; Barame et al., 2025).</p><p>Berikut alur praktis mencari dan memverifikasi referensi akademik:</p><table class="min-w-full divide-y divide-gray-200 border border-gray-200 my-5"><thead><tr class="bg-gray-50"><th class="px-4 py-2 text-left font-semibold">Langkah</th><th class="px-4 py-2 text-left font-semibold">Tujuan</th><th class="px-4 py-2 text-left font-semibold">Contoh Praktis</th><th class="px-4 py-2 text-left font-semibold">Dasar Ilmiah</th></tr></thead><tbody><tr><td class="border-t px-4 py-2">Tentukan kata kunci</td><td class="border-t px-4 py-2">Memperjelas fokus pencarian</td><td class="border-t px-4 py-2">"AI untuk penelitian" + "information literacy"</td><td class="border-t px-4 py-2">AI mendukung pencarian bahasa alami (Bolanos et al., 2024)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Cari di database ilmiah</td><td class="border-t px-4 py-2">Menjaga kualitas hasil awal</td><td class="border-t px-4 py-2">Google Scholar, Scopus, DOAJ, perpustakaan digital</td><td class="border-t px-4 py-2">Database tradisional cenderung unggul dalam akurasi (Tomczyk et al., 2024)</td></tr><tr><td class="border-t px-4 py-2">Pakai AI untuk eksplorasi</td><td class="border-t px-4 py-2">Menemukan artikel unik</td><td class="border-t px-4 py-2">Ringkasan awal, tema, paper terkait</td><td class="border-t px-4 py-2">AI sering menemukan hasil unik (Tomczyk et al., 2024)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Kelola dengan reference manager</td><td class="border-t px-4 py-2">Merapikan sitasi</td><td class="border-t px-4 py-2">Simpan PDF dan metadata di Zotero</td><td class="border-t px-4 py-2">Mendukung tugas akademik mandiri (Haliq et al., 2023)</td></tr><tr><td class="border-t px-4 py-2">Verifikasi sumber</td><td class="border-t px-4 py-2">Mencegah referensi palsu</td><td class="border-t px-4 py-2">Cek DOI, jurnal, penulis, tahun</td><td class="border-t px-4 py-2">Pelatihan menurunkan sitasi palsu (Pratama, 2025)</td></tr></tbody></table><h2>Kesalahan Umum dalam Menggunakan AI</h2><p>Kesalahan paling sering adalah terlalu percaya pada hasil AI tanpa mengecek apakah artikel itu benar-benar ada, relevan, dan terbit di jurnal yang layak. Studi pelatihan mahasiswa menunjukkan intervensi khusus dapat menurunkan referensi palsu dan meningkatkan penggunaan jurnal bereputasi (Pratama, 2025). Ini berarti masalah <strong>halusinasi sitasi</strong> nyata, tetapi bisa dikurangi dengan pelatihan yang tepat (Pratama, 2025).</p><p>Kesalahan lain adalah mengira semua hasil teratas pasti paling baik. Evaluasi kualitas jurnal justru perlu pendekatan seperti <strong>lateral reading</strong>, yaitu membuka sumber lain untuk memeriksa reputasi jurnal, kredibilitas penerbit, dan tanda-tanda predatory journal (Dale & Craft, 2021). Lingkungan akademik juga masih menunjukkan kelemahan dalam perilaku verifikasi informasi, sehingga pelatihan fact-checking tetap penting (Tergembay et al., 2026).</p><ul><li>Jangan menyalin daftar pustaka dari AI tanpa cek DOI atau metadata (Pratama, 2025).</li><li>Jangan memakai blog sebagai rujukan utama untuk karya ilmiah (Haliq et al., 2023).</li><li>Jangan abaikan isu <strong>privasi data</strong> saat mengunggah dokumen ke alat AI berbasis cloud (Barame et al., 2025).</li></ul><h2>Platform Rekomendasi untuk Penelitian</h2><p>Platform yang berguna terbagi dalam tiga fungsi: database pencarian, asisten AI, dan manajer referensi. Survei alat AI terbaru mengidentifikasi mesin pencari seperti Scite, Elicit, Consensus, Perplexity, dan SciSpace sebagai kelompok search engine, sementara alat lain berperan as writing assistant (Bolanos et al., 2024). Di sisi lain, OpenAlex dan Zotero relevan untuk alur kerja yang lebih terbuka, terstruktur, dan dapat direproduksi (Barame et al., 2025).</p><table class="min-w-full divide-y divide-gray-200 border border-gray-200 my-5"><thead><tr class="bg-gray-50"><th class="px-4 py-2 text-left font-semibold">Platform</th><th class="px-4 py-2 text-left font-semibold">Fungsi Utama</th><th class="px-4 py-2 text-left font-semibold">Kekuatan</th><th class="px-4 py-2 text-left font-semibold">Catatan</th></tr></thead><tbody><tr><td class="border-t px-4 py-2">Google Scholar</td><td class="border-t px-4 py-2">Pencarian jurnal</td><td class="border-t px-4 py-2">Mudah diakses, luas</td><td class="border-t px-4 py-2">Perlu seleksi kualitas manual (Haliq et al., 2023)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">DOAJ</td><td class="border-t px-4 py-2">Jurnal open access</td><td class="border-t px-4 py-2">Baik untuk jurnal terbuka</td><td class="border-t px-4 py-2">Tidak mencakup semua bidang (Tergembay et al., 2026)</td></tr><tr><td class="border-t px-4 py-2">Crossref</td><td class="border-t px-4 py-2">Verifikasi DOI</td><td class="border-t px-4 py-2">Cek metadata artikel</td><td class="border-t px-4 py-2">Sangat berguna untuk validasi (Pratama, 2025)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Elicit / Consensus / Scite</td><td class="border-t px-4 py-2">AI untuk penelitian</td><td class="border-t px-4 py-2">Ringkasan & pencarian bahasa alami</td><td class="border-t px-4 py-2">Jangan dipakai sebagai sumber final tanpa cek silang (Bolanos et al., 2024; Whitfield & Hofmann, 2023)</td></tr><tr><td class="border-t px-4 py-2">Zotero</td><td class="border-t px-4 py-2">Manajemen referensi</td><td class="border-t px-4 py-2">Simpan, kutip, organisasi</td><td class="border-t px-4 py-2">Mendukung kerja mandiri mahasiswa (Haliq et al., 2023)</td></tr></tbody></table><h2>Verifikasi Kredibilitas Sumber</h2><p>Tips memverifikasi kredibilitas sumber dimulai dari empat pertanyaan sederhana: siapa penulisnya, di jurnal apa artikel terbit, apakah metadata cocok, dan apakah isi artikel benar-benar relevan dengan topik. Literasi informasi didefinisikan sebagai kemampuan menemukan, menilai, menggunakan, dan menyebarkan informasi secara etis, sehingga verifikasi sumber adalah bagian inti dari kompetensi riset (Alahi & Yesmin, 2024; Tergembay et al., 2026). Bukti juga menunjukkan pelatihan verifikasi digital dan media meningkatkan hasil belajar, minat, dan keterampilan berpikir kritis (Gergul et al., 2025).</p><p>Untuk praktik harian, mahasiswa dapat membuka halaman jurnal, mengecek DOI di Crossref, melihat apakah jurnal terindeks secara wajar, lalu membandingkan abstrak dengan kebutuhan penelitian. Jika AI memberi 10 artikel, jangan ambil semuanya. Pilih 3–5 yang paling relevan, baca abstraknya, dan simpan hanya yang mendukung rumusan masalah (Olivia & Desriyeni, 2026).</p><ul><li>Gunakan <strong>lateral reading</strong> untuk mengecek reputasi jurnal and penerbit (Dale & Craft, 2021).</li><li>Cek kecocokan judul, penulis, tahun, dan DOI sebelum mengutip (Pratama, 2025).</li><li>Prioritaskan sumber yang valid, ilmiah, dan relevan dengan pertanyaan riset (Haliq et al., 2023; Tergembay et al., 2026).</li></ul><h2>Kesimpulan</h2><p>Cara mencari referensi akademik yang paling cerdas di era AI adalah menggabungkan <strong>database jurnal</strong>, alat AI, dan keterampilan verifikasi sumber. Bukti paling konsisten menunjukkan AI mempercepat pencarian dan membantu menemukan artikel unik, tetapi akurasi dan kualitas tetap lebih terjaga ketika hasil AI dicek silang dengan sumber ilmiah konvensional dan dinilai secara kritis (Tomczyk et al., 2024; Oyelude, 2024).</p><p>Bagi mahasiswa, dosen, dan peneliti pemula, kuncinya bukan memakai AI sebanyak mungkin, tetapi memakai AI dengan <strong>etis, terukur, dan terverifikasi</strong>. Dengan cara itu, referensi akademik yang dikumpulkan akan lebih kuat, relevan, dan aman digunakan dalam karya ilmiah.</p><h2>FAQ</h2><h3>Apakah AI bisa menggantikan Google Scholar?</h3><p>Tidak. Bukti terbaru menunjukkan AI lebih tepat sebagai pelengkap, sedangkan alat konvensional masih unggul dalam akurasi dan kualitas hasil (Tomczyk et al., 2024).</p><h3>Apakah semua referensi dari AI bisa dipercaya?</h3><p>Tidak. Referensi dari AI perlu diverifikasi karena sitasi palsu atau halusinasi masih sering muncul (Pratama, 2025).</p><h3>Platform apa yang cocok untuk mahasiswa pemula?</h3><p>Kombinasi Google Scholar, DOAJ, Zotero, dan satu alat AI pencari literatur adalah pilihan paling praktis untuk mulai belajar (Haliq et al., 2023; Bolanos et al., 2024).</p><h3>Mengapa literasi informasi penting dalam pencarian jurnal?</h3><p>Karena literasi informasi berkaitan positif dengan kompetensi riset dan performa akademik, sekaligus membantu mengurangi information overload (Ashiq et al., 2025; Olivia & Desriyeni, 2026).</p><h3>Bagaimana cara cepat mengecek kredibilitas jurnal?</h3><p>Gunakan lateral reading, cek DOI dan metadata, lalu lihat apakah jurnal dan penerbitnya tampak sah dan relevan (Dale & Craft, 2021; Pratama, 2025).</p><h2>Referensi</h2><p class="text-sm text-gray-500">Alaa, A. (2024). Harnessing Artificial Intelligence for Enhanced Efficiency in Academic Writing and Research. <em>Fusion: Practice and Applications</em>. https://doi.org/10.54216/fpa.160209<br>Alahi, F., & Yesmin, S. (2024). Impact of information literacy on research work performance: measuring thesis students’ competency at a public university in Bangladesh. <em>Global Knowledge, Memory and Communication</em>. https://doi.org/10.1108/gkmc-03-2024-0174<br>Ashiq, M., Hira, A., & Saeed, A. (2025). The relationship between information literacy and academic performance: a meta-analysis study. <em>Performance Measurement and Metrics</em>. https://doi.org/10.1108/pmm-04-2025-0020<br>Barame, G. K., Ngugi, J., & Sumbiri, D. (2025). Enhancing Academic Research Efficiency: A Comparative Analysis of Manual and AI-Driven Workflows with Optimized LLM-Zotero Integration. <em>Journal of Information and Technology</em>. https://doi.org/10.70619/vol5iss9pp46-55<br>Bolanos, F., Salatino, A., Osborne, F., & Motta, E. (2024). Artificial intelligence for literature reviews: opportunities and challenges. <em>Artificial Intelligence Review, 57</em>. https://doi.org/10.1007/s10462-024-10902-3<br>Cagitla, M., & Balid-Bordado, S. (2026). Strengthening Academic Information Literacy (SAIL) and Information Literacy of Research Students. <em>International Journal For Multidisciplinary Research</em>. https://doi.org/10.36948/ijfmr.2026.v08i01.68925<br>Dale, J., & Craft, A. (2021). Professional Applications of Information Literacy: Helping Researchers Learn to Evaluate Journal Quality. <em>Serials Review, 47</em>, 129 - 135. https://doi.org/10.1080/00987913.2021.1964337<br>De La Torre-López, J., Ramírez, A., & Romero, J. (2023). Artificial intelligence to automate the systematic review of scientific literature. <em>Computing, 105</em>, 2171 - 2194. https://doi.org/10.1007/s00607-023-01181-x<br>Gergul, S., Gorodnycha, L., Olkhovyk, M., & Panchenko, V. (2025). The Effectiveness of using Information Verification Services in the Training of Future Teachers. <em>WSEAS TRANSACTIONS ON COMPUTER RESEARCH</em>. https://doi.org/10.37394/232018.2025.13.28<br>Godwin, R., Soundararajan, K., Duggan, E. W., Goss, H., Atcheson, C., Wasson, E., Gosling, A. F., Archer, A., Berkowitz, D., Sherrer, M., & Melvin, R. (2025). AI-driven literature tools for enhanced medical education and research. <em>Physiology</em>. https://doi.org/10.1152/physiol.2025.40.s1.1646<br>Haliq, A., Zamzani, Z., Wiedarti, P., & Akhiruddin, A. (2023). Self-Acces in Digital Literacy: Evaluating the Quality of Information and Reliability of Sources in Writing Academic Essay. <em>Interference: Journal of Language, Literature, and Linguistics</em>. https://doi.org/10.26858/interference.v4i1.44561<br>Mandić, A. (2025). Optimizing the Research Process: The Role of AI Tools in Scientific Literature Searches. <em>2025 MIPRO 48th ICT and Electronics Convention</em>, 1159-1163. https://doi.org/10.1109/mipro65660.2025.11131993<br>Olivia, D., & Desriyeni, D. (2026). The Effect of Information Literacy on Students’ Information Overload in the Use of Digital Academic References. <em>Journal of Multidisciplinary Science: MIKAILALSYS</em>. https://doi.org/10.58578/mikailalsys.v4i2.9877<br>Oyelude, A. (2024). Artificial intelligence (AI) tools for academic research. <em>Library Hi Tech News</em>. https://doi.org/10.1108/lhtn-08-2024-0131<br>Pratama, H. (2025). Training Students to Identify and Correct Fabricated References in ChatGPT-Generated Literature Reviews. <em>Conference on English Language Teaching</em>. https://doi.org/10.24090/celti.2025.1335<br>Pulletikurty, R. R. (2026). AI TOOLS FOR LITERATURE REVIEW AND KNOWLEDGE MAPPING: EMPOWERING RESEARCH EXCELLENCE IN ACADEMIC WRITING AND PUBLISHING. <em>Journal of Emerging Technologies and Innovative Research</em>. https://doi.org/10.56975/jetir.v13i2.575657<br>Tergembay, К., Moldabayev, K., Abdrakhmanova, A., Tulebayeva, S., & Abulkassimova, G. (2026). Recognition of fake news and deepfake technologies: on the example of the academic environment in higher education. <em>Herald of Journalism</em>. https://doi.org/10.26577/hj79120268<br>Tomczyk, P., Brüggemann, P., Mergner, N., & Petrescu, M. (2024). Are AI tools better than traditional tools in literature searching? Evidence from E-commerce research. <em>Journal of Librarianship and Information Science, 58</em>, 135 - 145. https://doi.org/10.1177/09610006241295802<br>Whitfield, S., & Hofmann, M. A. (2023). Elicit: AI literature review research assistant. <em>Public Services Quarterly, 19</em>, 201 - 207. https://doi.org/10.1080/15228959.2023.2224125</p>',
                'excerpt' => 'Panduan cara mencari referensi akademik dengan AI secara etis, efektif, dan terverifikasi untuk mahasiswa, dosen, dan peneliti.',
                'image' => '/images/berita/tips-ai-referensi.svg',
                'status' => 'published',
                'views_count' => 1540,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now(),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['literasi', 'perpustakaan']
            ],
            [
                'title' => '5 Website Jurnal Gratis untuk Mencari Referensi Akademik',
                'content' => '<p>Artikel ini membahas lima website jurnal gratis, peran AI untuk penelitian, dan cara memverifikasi sumber agar pencarian referensi akademik tetap akurat dan etis. Bukti yang tersedia menunjukkan akses terbuka membantu mahasiswa mendapatkan literatur ilmiah lebih cepat dan lebih luas, tetapi kualitas sumber tetap harus diperiksa secara kritis (Anugradia et al., 2025; Björk et al., 2010; Overstreet, 2025).</p><h2>Pendahuluan</h2><p>Cara mencari referensi akademik kini tidak lagi harus bergantung pada sumber berbayar, karena internet telah memperluas ketersediaan artikel ilmiah bebas akses di banyak bidang (Björk et al., 2010). Bagi mahasiswa IAIN Sorong, ini penting karena biaya akses jurnal internasional sering menjadi hambatan nyata saat menyusun makalah, proposal, atau skripsi (Anugradia et al., 2025).</p><p>Di sisi lain, kemudahan akses belum otomatis membuat pencarian jurnal menjadi tepat. Mahasiswa sering masih memakai blog, Wikipedia, atau sumber populer lain karena terasa relevan, walau jurnal ilmiah tetap dinilai sebagai sumber paling valid untuk penulisan akademik (Haliq et al., 2023). Karena itu, strategi terbaik bukan hanya menemukan artikel gratis, tetapi juga memilih <strong>referensi akademik</strong> yang kredibel dan relevan (Vamanu & Zak, 2022).</p><h2>Mengapa Referensi Akademik Berkualitas Sangat Penting</h2><p>Referensi akademik yang baik membantu mahasiswa membangun argumen yang dapat dipertanggungjawabkan dan mengurangi risiko memakai informasi yang lemah atau menyesatkan (Overstreet, 2025; Vamanu & Zak, 2022). Dalam evaluasi tulisan mahasiswa, 86,67% menggunakan jurnal ilmiah online, dan seluruh responden setuju bahwa jurnal ilmiah adalah sumber paling valid untuk tulisan akademik (Haliq et al., 2023).</p><p>Masalahnya, kemampuan menilai sumber masih sering terbatas. Siswa dan mahasiswa kerap lemah dalam memberi justifikasi kredibilitas sumber dan juga kesulitan mengenali argumen yang tidak seimbang dalam konten online (Marttunen et al., 2021). Penelitian lain juga menunjukkan penulis pemula cenderung beralih ke sumber non-akademik seperti situs berita online dan perusahaan komersial ketika diminta menulis berbasis riset (Overstreet, 2025).</p><p>Kualitas referensi tidak hanya soal nama jurnal, tetapi juga soal <strong>kredibilitas sumber</strong> dan <strong>kekuatan isi</strong>. Dua kriteria itu dapat diterjemahkan menjadi empat pertanyaan sederhana: siapa otornya, apakah sumbernya tepercaya, apakah klaimnya masuk akal, dan apakah ada dukungan bukti yang jelas (Vamanu & Zak, 2022). Jadi, mahasiswa tidak cukup hanya menemukan PDF gratis; mereka juga perlu membaca dengan sikap evaluatif (Kiili et al., 2023).</p><h2>Peran AI dalam Mencari Literatur Ilmiah</h2><p>AI untuk penelitian paling berguna ketika dipakai sebagai alat bantu, bukan sebagai pengganti penilaian manusia (Whitfield & Hofmann, 2023). Tinjauan terbaru menunjukkan AI dapat membantu tugas literature review, penyaringan artikel, ekstraksi informasi, peringkasan, serta pencarian berbasis bahasa alami (Bolanos et al., 2024).</p><p>Namun, hasil AI tidak selalu konsisten. Evaluasi terhadap alat AI gratis menemukan variasi kualitas jawaban yang cukup besar, dengan beberapa alat memasukkan sumber non-akademik, menggunakan informasi usang, dan kurang transparan dalam pemilihan sumber (Danler et al., 2024). Tinjauan lain juga menegaskan bahwa kualitas ekstraksi informasi dari AI dapat sangat bervariasi antarplatform (Bolanos et al., 2024).</p><p>Di kampus, adopsi AI sudah sangat luas. Studi survei pada mahasiswa dan peneliti muda menemukan lebih dari 90% responden memakai AI untuk aktivitas akademik, tetapi sekitar 9,7% masih menghindarinya karena kurang paham, alasan etis, dan isu kepercayaan (Archana et al., 2025). Karena itu, yang dibutuhkan bukan sekadar akses ke AI, melainkan <strong>literasi AI</strong> agar pemakaiannya bermanfaat dan tetap bertanggung jawab (Archana et al., 2025; Alqahtani et al., 2023).</p><h2>Langkah-Langkah Cerdas Mencari Referensi Akademik Menggunakan AI</h2><p>Cara mencari referensi akademik yang lebih cerdas dimulai dari topik yang spesifik. Mahasiswa yang menulis skripsi tentang pendidikan Islam, misalnya, sebaiknya tidak hanya mengetik “pendidikan Islam” di Google, tetapi mempersempitnya menjadi “literasi digital mahasiswa PAI” atau “strategi pembelajaran tafsir berbasis media digital” agar hasil pencarian jurnal lebih relevan (Bolanos et al., 2024; Allen & Weber, 2014).</p><p>Setelah itu, gunakan AI untuk membantu membuat variasi kata kunci dan memahami arah awal literatur. Beberapa alat seperti Elicit, Consensus, Scite, Perplexity, dan SciSpace memungkinkan kueri bahasa alami serta ringkasan singkat dari artikel yang ditemukan (Bolanos et al., 2024). Meski begitu, hasil awal tetap perlu dibawa kembali ke database jurnal atau mesin telusur ilmiah yang lebih mapan (Danler et al., 2024).</p><table class="min-w-full divide-y divide-gray-200 border border-gray-200 my-5"><thead><tr class="bg-gray-50"><th class="px-4 py-2 text-left font-semibold">Langkah</th><th class="px-4 py-2 text-left font-semibold">Tujuan</th><th class="px-4 py-2 text-left font-semibold">Contoh Praktis</th><th class="px-4 py-2 text-left font-semibold">Dasar Ilmiah</th></tr></thead><tbody><tr><td class="border-t px-4 py-2">Tentukan topik sempit</td><td class="border-t px-4 py-2">Mengurangi hasil yang terlalu luas</td><td class="border-t px-4 py-2">“AI untuk penelitian pendidikan Islam”</td><td class="border-t px-4 py-2">Tugas literatur menuntut pencarian artikel empiris yang fokus (Allen & Weber, 2014)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Pakai AI untuk kata kunci</td><td class="border-t px-4 py-2">Membuat kueri lebih kaya</td><td class="border-t px-4 py-2">Cari sinonim Indonesia-Inggris</td><td class="border-t px-4 py-2">Alat AI mendukung kueri bahasa alami (Bolanos et al., 2024)</td></tr><tr><td class="border-t px-4 py-2">Telusuri database gratis</td><td class="border-t px-4 py-2">Mendapat artikel nyata</td><td class="border-t px-4 py-2">Google Scholar, DOAJ, PubMed Central</td><td class="border-t px-4 py-2">Platform gratis direkomendasikan untuk sumber kredibel (Anugradia et al., 2025)</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">Simpan dengan reference manager</td><td class="border-t px-4 py-2">Merapikan sitasi</td><td class="border-t px-4 py-2">Simpan di Zotero</td><td class="border-t px-4 py-2">Zotero gratis untuk mengumpulkan, mengatur, dan mengutip referensi (Jhajj et al., 2024)</td></tr><tr><td class="border-t px-4 py-2">Verifikasi metadata</td><td class="border-t px-4 py-2">Mencegah referensi palsu</td><td class="border-t px-4 py-2">Cek DOI, penulis, tahun</td><td class="border-t px-4 py-2">AI bisa menghasilkan referensi yang tidak ada (Jhajj et al., 2024)</td></tr></tbody></table><h2>Kesalahan yang Harus Dihindari Saat Menggunakan AI</h2><p>Kesalahan paling umum adalah menerima hasil AI seolah pasti benar. Padahal beberapa alat AI masih memakai sumber non-akademik, kurang transparan, atau menghasilkan rujukan yang terdengar meyakinkan tetapi keliru (Danler et al., 2024; Jhajj et al., 2024).</p><p>Kesalahan kedua adalah melewatkan evaluasi sumber hanya karena artikelnya gratis dan mudah diakses. Akses terbuka tidak berarti semua artikel memiliki kualitas yang sama, dan mahasiswa masih perlu membedakan jurnal bereputasi dari sumber yang lemah (Anugradia et al., 2025; Overstreet, 2025). Pendekatan checklist sederhana saja juga sering tidak cukup jika tidak disertai pembacaan kritis yang lebih kontekstual (Angell & Tewell, 2017).</p><p>Kesalahan ketiga adalah mengandalkan jalur ilegal ketika artikel tidak langsung tersedia. Sebagian mahasiswa memang melaporkan memakai Sci-Hub saat kesulitan mengakses artikel berbayar, tetapi studi yang sama menunjukkan mereka juga mencari alternatif legal seperti DOAJ dan situs universitas luar negeri (Anugradia et al., 2025). Untuk website perpustakaan, rujukan yang lebih aman adalah mengarahkan pengguna ke akses terbuka yang sah, seperti OA journal, repositori institusi, atau layanan pengecekan akses terbuka (Björk et al., 2010; Himmelstein et al., 2018).</p><h2>Rekomendasi Platform untuk Menemukan Referensi Ilmiah</h2><p>Lima website jurnal gratis berikut paling relevan untuk mahasiswa IAIN karena mudah diakses, berguna untuk pencarian jurnal, dan mendukung kebutuhan tugas akademik dari tahap awal hingga penyusunan daftar pustaka. Daftar ini tidak berarti hanya ada lima sumber yang baik, tetapi kelimanya paling masuk akal sebagai titik awal pencarian literatur ilmiah gratis (Anugradia et al., 2025; Allen & Weber, 2014).</p><table class="min-w-full divide-y divide-gray-200 border border-gray-200 my-5"><thead><tr class="bg-gray-50"><th class="px-4 py-2 text-left font-semibold">Platform</th><th class="px-4 py-2 text-left font-semibold">Fungsi Utama</th><th class="px-4 py-2 text-left font-semibold">Kelebihan</th><th class="px-4 py-2 text-left font-semibold">Catatan Penggunaan</th></tr></thead><tbody><tr><td class="border-t px-4 py-2">Google Scholar</td><td class="border-t px-4 py-2">Mesin telusur ilmiah</td><td class="border-t px-4 py-2">Mudah, luas, familiar bagi mahasiswa (Anugradia et al., 2025)</td><td class="border-t px-4 py-2">Hasil perlu disaring karena tidak semua sumber setara</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">DOAJ</td><td class="border-t px-4 py-2">Direktori jurnal open access</td><td class="border-t px-4 py-2">Fokus pada jurnal akses terbuka kredibel (Anugradia et al., 2025)</td><td class="border-t px-4 py-2">Cocok saat butuh jurnal ilmiah gratis penuh</td></tr><tr><td class="border-t px-4 py-2">PubMed Central</td><td class="border-t px-4 py-2">Arsip full-text gratis</td><td class="border-t px-4 py-2">Menyediakan akses gratis dan terstruktur (Anugradia et al., 2025; Björk et al., 2010)</td><td class="border-t px-4 py-2">Sangat kuat untuk bidang kesehatan</td></tr><tr class="bg-gray-50"><td class="border-t px-4 py-2">CORE</td><td class="border-t px-4 py-2">Agregator karya ilmiah</td><td class="border-t px-4 py-2">Menghimpun banyak sumber terbuka (Bolanos et al., 2024)</td><td class="border-t px-4 py-2">Cocok untuk memperluas jangkauan pencarian</td></tr><tr><td class="border-t px-4 py-2">ScienceOpen</td><td class="border-t px-4 py-2">Agregator literatur ilmiah</td><td class="border-t px-4 py-2">Gratis, memuat OA dan preprint, plus linked citations (deLaubell, 2024)</td><td class="border-t px-4 py-2">Berguna untuk menelusuri sitasi dan dampak artikel</td></tr></tbody></table><h3>Platform Pendukung</h3><p>Google Scholar sering menjadi pilihan pertama mahasiswa karena mudah dipakai dan sumbernya beragam (Anugradia et al., 2025). Namun, untuk verifikasi akses terbuka, layanan seperti Unpaywall juga relevan karena dapat memberi tahu apakah sebuah artikel tersedia gratis secara legal di internet (Himmelstein et al., 2018).</p><p>Untuk manajemen referensi, Zotero dan Mendeley membantu mengumpulkan, menata, menandai, dan menghasilkan sitasi secara otomatis (Jhajj et al., 2024). Untuk eksplorasi AI, Elicit, Consensus, Scite, dan SciSpace bisa dipakai sebagai pendamping pencarian, bukan sumber final (Bolanos et al., 2024; Danler et al., 2024).</p><h2>Tips Memverifikasi Kredibilitas Sumber</h2><p>Verifikasi kredibilitas sumber sebaiknya dilakukan dengan dua fokus: <strong>sumbernya</strong> dan <strong>isinya</strong>. Dari sisi sumber, yang dilihat adalah otoritas dan kepercayaan; dari sisi isi, yang diuji adalah kewajaran klaim dan dukungan buktinya (Vamanu & Zak, 2022).</p><p>Pendekatan yang makin sering direkomendasikan adalah <strong>lateral reading</strong>, yaitu membuka tab baru untuk memeriksa siapa penulisnya, bagaimana reputasi situs atau jurnalnya, dan apa kata sumber lain tentang klaim tersebut (Overstreet, 2025). Bukti pada penulis pemula menunjukkan strategi evaluasi dapat bergeser dari yang lemah ke yang lebih kuat setelah pelatihan riset online (Overstreet, 2025).</p><p>Mahasiswa juga perlu belajar membedakan teks yang lebih kredibel dari yang kurang kredibel. Evaluasi kredibilitas mencakup dua keterampilan yang berbeda, yaitu mengonfirmasi sumber yang lebih dapat dipercaya dan mempertanyakan sumber yang kurang dapat dipercaya (Kiili et al., 2023). Karena itu, saat menemukan artikel dari blog pribadi, media populer, atau situs komersial, pembaca perlu ekstra hati-hati sebelum menjadikannya referensi akademik utama (Kiili et al., 2023).</p><h2>Kesimpulan</h2><p>Cara mencari referensi akademik yang efektif bagi mahasiswa IAIN adalah memadukan website jurnal gratis, keterampilan evaluasi sumber, dan AI untuk penelitian yang dipakai secara etis. Bukti penelitian menunjukkan akses terbuka memperluas jangkauan literatur, AI dapat mempercepat pencarian dan organisasi referensi, tetapi kualitas hasil tetap bergantung pada verifikasi manusia (Anugradia et al., 2025; Whitfield & Hofmann, 2023; Jhajj et al., 2024).</p><p>Dengan kata lain, lima platform seperti Google Scholar, DOAJ, PubMed Central, CORE, dan ScienceOpen dapat menjadi pintu masuk yang kuat untuk pencarian jurnal. Namun, referensi akademik yang benar-benar berkualitas hanya diperoleh ketika mahasiswa memeriksa kredibilitas sumber, relevansi isi, dan keakuratan sitasinya sebelum dipakai dalam karya ilmiah.</p><h2>FAQ</h2><h3>Apakah Google Scholar termasuk website jurnal gratis?</h3><p>Google Scholar bukan jurnal, melainkan mesin telusur ilmiah yang sangat sering dipakai mahasiswa karena mudah diakses dan menawarkan banyak sumber (Anugradia et al., 2025).</p><h3>Apakah semua artikel open access pasti berkualitas?</h3><p>Tidak. Open access memperluas akses, tetapi kualitas jurnal tetap perlu dinilai secara kritis karena ada variasi mutu antarjurnal (Anugradia et al., 2025).</p><h3>Apakah AI boleh dipakai untuk mencari referensi?</h3><p>Boleh, dan bukti menunjukkan AI dapat membantu efisiensi riset, selama dipakai sebagai alat bantu dan tetap diawasi manusia (Whitfield & Hofmann, 2023; Jhajj et al., 2024).</p><h3>Apa website gratis terbaik untuk mahasiswa pemula?</h3><p>Untuk pemula, kombinasi Google Scholar, DOAJ, dan Zotero adalah titik awal yang praktis karena mudah dipakai dan mendukung pencarian serta pengelolaan referensi (Anugradia et al., 2025; Jhajj et al., 2024).</p><h3>Bagaimana mengecek apakah referensi itu kredibel?</h3><p>Periksa otoritas sumber, kepercayaan pada penulis atau penerbit, lalu nilai apakah isi artikelnya masuk akal dan didukung bukti (Vamanu & Zak, 2022).</p><h2>Referensi</h2><p class="text-sm text-gray-500">Allen, E. J., & Weber, R. (2014). The Library and the Web: Graduate Students’ Selection of Open Access Journals for Empirical Literature Searches. <em>Journal of Web Librarianship, 8</em>, 243 - 262. https://doi.org/10.1080/19322909.2014.927745<br>Alqahtani, T., Badreldin, H., Alrashed, M. A., Alshaya, A. I., Alghamdi, S., Saleh, K. B., Alowais, S. A., Alshaya, O. A., Rahman, I., Yami, M. A. S., & Albekairy, A. M. (2023). The emergent role of artificial intelligence, natural learning processing, and large language models in higher education and research.. <em>Research in social & administrative pharmacy : RSAP</em>. https://doi.org/10.1016/j.sapharm.2023.05.016<br>Angell, K., & Tewell, E. (2017). Teaching and Un-Teaching Source Evaluation: Questioning Authority in Information Literacy Instruction. <em>Communications in Information Literacy, 11</em>, 95-121. https://doi.org/10.15760/comminfolit.2017.11.1.37<br>Anugradia, N., Kruehong, T., & Alvarez, J. (2025). Students\' Evaluation of the Effectiveness of Open Access Journals in Accelerating Paper Completion. <em>Journal of Educational Technology and Learning Creativity</em>. https://doi.org/10.37251/jetlc.v3i1.1462<br>Archana, S., R, R. V., Padmakumar, P., C., S., & Aboobaker, N. (2025). AI assisted learning and research: an exploratory study among university students and scholars. <em>Discover Education, 4</em>. https://doi.org/10.1007/s44217-025-00814-x<br>Björk, B., Welling, P., Laakso, M., Majlender, P., Hedlund, T., & Guðnason, G. (2010). Open Access to the Scientific Journal Literature: Situation 2009. <em>PLoS ONE, 5</em>. https://doi.org/10.1371/journal.pone.0011273<br>Bolanos, F., Salatino, A., Osborne, F., & Motta, E. (2024). Artificial intelligence for literature reviews: opportunities and challenges. <em>Artificial Intelligence Review, 57</em>. https://doi.org/10.1007/s10462-024-10902-3<br>Danler, M., Hackl, W., Neururer, S., & Pfeifer, B. (2024). Quality and Effectiveness of AI Tools for Students and Researchers for Scientific Literature Review and Analysis. <em>Studies in health technology and informatics, 313</em>, 203-208. https://doi.org/10.3233/shti240038<br>deLaubell, L. (2024). ScienceOpen. <em>The Charleston Advisor</em>. https://doi.org/10.5260/chara.25.4.02<br>Haliq, A., Zamzani, Z., Wiedarti, P., & Akhiruddin, A. (2023). Self-Acces in Digital Literacy: Evaluating the Quality of Information and Reliability of Sources in Writing Academic Essay. <em>Interference: Journal of Language, Literature, and Linguistics</em>. https://doi.org/10.26858/interference.v4i1.44561<br>Himmelstein, D. S., Romero, A., Levernier, J. G., Munro, T. A., McLaughlin, S., Tzovaras, G. B., & Greene, C. (2018). Sci-Hub provides access to nearly all scholarly literature. <em>eLife, 7</em>. https://doi.org/10.7554/elife.32822<br>Jhajj, K. S., Jindal, P., & Kaur, K. (2024). Use of Artificial Intelligence Tools for Research by Medical Students: A Narrative Review. <em>Cureus, 16</em>. https://doi.org/10.7759/cureus.55367<br>Kiili, C., Räikkönen, E., Bråten, I., Strømsø, H. I., & Hagerman, M. (2023). Examining the structure of credibility evaluation when sixth graders read online texts. <em>J. Comput. Assist. Learn., 39</em>, 954-969. https://doi.org/10.1111/jcal.12779<br>Marttunen, M., Salminen, T., & Utriainen, J. (2021). Student evaluations of the credibility and argumentation of online sources. <em>The Journal of Educational Research, 114</em>, 294 - 305. https://doi.org/10.1080/00220671.2021.1929052<br>Overstreet, M. (2025). Cultivating networked literacy: Second language writers and the development of online source evaluation strategies. <em>Computers and Composition</em>. https://doi.org/10.1016/j.compcom.2025.102914<br>Vamanu, I., & Zak, E. (2022). Information source and content: articulating two key concepts for information evaluation. <em>Information and Learning Sciences</em>. https://doi.org/10.1108/ils-09-2021-0084<br>Whitfield, S., & Hofmann, M. A. (2023). Elicit: AI literature review research assistant. <em>Public Services Quarterly, 19</em>, 201 - 207. https://doi.org/10.1080/15228959.2023.2224125</p>',
                'excerpt' => 'Panduan cara mencari referensi akademik dengan 5 website jurnal gratis, AI untuk penelitian, dan tips verifikasi sumber ilmiah.',
                'image' => '/images/berita/tips-jurnal-gratis.svg',
                'status' => 'published',
                'views_count' => 1890,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now(),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['literasi', 'perpustakaan']
            ]
        ];

        foreach ($articles as $art) {
            $a = Article::updateOrCreate(
                ['title' => $art['title']],
                [
                    'user_id' => $admin->id,
                    'slug' => \Illuminate\Support\Str::slug($art['title']),
                    'content' => $art['content'],
                    'excerpt' => $art['excerpt'],
                    'image' => $art['image'],
                    'status' => $art['status'],
                    'views_count' => $art['views_count'],
                    'seo_score' => $art['seo_score'],
                    'readability_score' => $art['readability_score'],
                    'published_at' => $art['published_at']
                ]
            );

            // Sync categories
            $cIds = [];
            foreach ($art['cats'] as $cSlug) {
                if (isset($catIds[$cSlug])) {
                    $cIds[] = $catIds[$cSlug];
                }
            }
            $a->categories()->sync($cIds);

            // Sync tags
            $tIds = [];
            foreach ($art['tags_list'] as $tSlug) {
                if (isset($tagIds[$tSlug])) {
                    $tIds[] = $tagIds[$tSlug];
                }
            }
            $a->tags()->sync($tIds);

        }

        // 3. Create AI Analytics Snapshots
        AiAnalyticsSnapshot::updateOrCreate(
            ['metric_name' => 'predicted_growth'],
            [
                'metric_value' => [
                    'growth_percentage' => 12.4,
                    'calculated_at' => now()->toIso8601String(),
                    'recommendation' => 'Publish trending articles'
                ]
            ]
        );

        // 4. Create Dynamic Pages for Astro Frontend API
        $pages = [
            [
                'title' => 'Profil UPT Perpustakaan',
                'slug' => 'profil',
                'content' => '<h2>Profil Perpustakaan IAIN Sorong</h2><p>Perpustakaan IAIN Sorong didirikan untuk mendukung Tri Dharma Perguruan Tinggi dengan menyediakan akses informasi ilmiah bagi dosen dan mahasiswa.</p>',
                'is_active' => true,
                'author_id' => $admin->id,
                'status' => 'published',
                'seo_score' => 'good',
                'readability_score' => 'good',
                'page_builder_type' => 'bricks',
                'views_count' => 1250,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Visi & Misi',
                'slug' => 'visi-misi',
                'content' => '<h2>Visi & Misi</h2><p><strong>Visi:</strong> Menjadi pusat dokumentasi ilmiah keislaman dan kebudayaan Papua terkemuka di Indonesia Timur tahun 2030.</p><p><strong>Misi:</strong> Menyediakan layanan rujukan terbaik, melestarikan khazanah lokal Papua, dan mengembangkan sistem otomasi perpustakaan.</p>',
                'is_active' => true,
                'author_id' => $admin->id,
                'status' => 'published',
                'seo_score' => 'ok',
                'readability_score' => 'good',
                'page_builder_type' => 'bricks',
                'views_count' => 840,
                'published_at' => now()->subDays(9),
            ],
            [
                'title' => 'Tata Tertib Perpustakaan',
                'slug' => 'tata-tertib',
                'content' => '<h2>Tata Tertib Pemustaka</h2><ol><li>Setiap pengunjung wajib mengisi daftar hadir elektronik di pintu masuk.</li><li>Dilarang membawa tas, jaket, dan makanan/minuman ke dalam ruang koleksi.</li><li>Peminjaman buku maksimal 3 eksemplar selama 7 hari kerja.</li></ol>',
                'is_active' => true,
                'author_id' => $admin->id,
                'status' => 'published',
                'seo_score' => 'ok',
                'readability_score' => 'ok',
                'page_builder_type' => 'custom',
                'views_count' => 620,
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Panduan Akses E-Journal & E-Resources',
                'slug' => 'panduan-e-resources',
                'content' => '<h2>Panduan Akses E-Resources</h2><p>Gunakan NIM/NIP Anda untuk masuk ke repositori institusi dan database jurnal terlanggan PNRI.</p>',
                'is_active' => false,
                'author_id' => $admin->id,
                'status' => 'draft',
                'seo_score' => 'bad',
                'readability_score' => 'ok',
                'page_builder_type' => 'gutenberg',
                'views_count' => 0,
                'published_at' => null,
            ],
            [
                'title' => 'F.A.Q Layanan UPT',
                'slug' => 'faq',
                'content' => '<h2>Pertanyaan yang Sering Diajukan (FAQ)</h2><p>Pertanyaan umum seputar layanan perpustakaan, kartu anggota, dan denda.</p>',
                'is_active' => true,
                'author_id' => $admin->id,
                'status' => 'scheduled',
                'seo_score' => 'good',
                'readability_score' => 'none',
                'page_builder_type' => 'custom',
                'views_count' => 0,
                'published_at' => now()->addDays(2),
            ],
            [
                'title' => 'Arsip Buletin E-Paper Lama (2020-2023)',
                'slug' => 'arsip-buletin-lama',
                'content' => '<h2>Arsip Buletin</h2><p>Konten lama buletin digital perpustakaan.</p>',
                'is_active' => false,
                'author_id' => $admin->id,
                'status' => 'trash',
                'seo_score' => 'none',
                'readability_score' => 'none',
                'page_builder_type' => 'custom',
                'views_count' => 45,
                'published_at' => now()->subYears(2),
            ],
        ];

        foreach ($pages as $p) {
            \App\Models\Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'content' => $p['content'],
                    'is_active' => $p['is_active'],
                    'author_id' => $p['author_id'],
                    'status' => $p['status'],
                    'seo_score' => $p['seo_score'],
                    'readability_score' => $p['readability_score'],
                    'page_builder_type' => $p['page_builder_type'],
                    'views_count' => $p['views_count'],
                    'published_at' => $p['published_at'],
                ]
            );
        }

        // 5. Create Dummy Reservations for Dashboard testing
        $reservations = [
            [
                'name' => 'Ahmad Fauzi',
                'nim_nip' => '202410012',
                'email' => 'ahmad.fauzi@iainsorong.ac.id',
                'room_name' => 'Ruang Diskusi Kelompok 1',
                'booking_date' => now()->addDays(2)->format('Y-m-d'),
                'session_time' => '09:00 - 11:00 WIT',
                'status' => 'approved',
                'link_surat' => 'https://example.com/surat-ahmad.pdf',
                'picked_up_at' => null,
                'returned_at' => null,
                'notes_inventory' => null,
                'rejection_reason' => null,
            ],
            [
                'name' => 'Siti Aminah',
                'nim_nip' => '202410045',
                'email' => 'siti.aminah@iainsorong.ac.id',
                'room_name' => 'Ruang Home Theater',
                'booking_date' => now()->addDays(1)->format('Y-m-d'),
                'session_time' => '13:00 - 15:00 WIT',
                'status' => 'pending',
                'link_surat' => 'https://example.com/surat-siti.pdf',
                'picked_up_at' => null,
                'returned_at' => null,
                'notes_inventory' => null,
                'rejection_reason' => null,
            ],
            [
                'name' => 'Dr. H. Muhammad',
                'nim_nip' => '197508122005011002',
                'email' => 'muhammad@iainsorong.ac.id',
                'room_name' => 'Ruang Multimedia',
                'booking_date' => now()->format('Y-m-d'),
                'session_time' => '10:00 - 12:00 WIT',
                'status' => 'returned',
                'link_surat' => null,
                'picked_up_at' => now()->subHours(2),
                'returned_at' => now()->subMinutes(15),
                'notes_inventory' => 'Remote AC & kabel HDMI dikembalikan dalam kondisi baik',
                'rejection_reason' => null,
            ],
            [
                'name' => 'Budi Santoso',
                'nim_nip' => '202410099',
                'email' => 'budi.santoso@iainsorong.ac.id',
                'room_name' => 'Ruang Multimedia',
                'booking_date' => now()->subDays(1)->format('Y-m-d'),
                'session_time' => '09:00 - 11:00 WIT',
                'status' => 'rejected',
                'link_surat' => null,
                'picked_up_at' => null,
                'returned_at' => null,
                'notes_inventory' => null,
                'rejection_reason' => 'Ruangan telah dipesan sebelumnya untuk kegiatan internal rektorat.',
            ],
            [
                'name' => 'Rina Wijaya',
                'nim_nip' => '202410112',
                'email' => 'rina.wijaya@iainsorong.ac.id',
                'room_name' => 'Ruang Diskusi Kelompok 1',
                'booking_date' => now()->format('Y-m-d'),
                'session_time' => '14:00 - 16:00 WIT',
                'status' => 'key_picked_up',
                'link_surat' => null,
                'picked_up_at' => now()->subMinutes(30),
                'returned_at' => null,
                'notes_inventory' => 'Remote AC dan Proyektor dipinjam',
                'rejection_reason' => null,
            ]
        ];

        foreach ($reservations as $res) {
            \Illuminate\Support\Facades\DB::table('reservations')->updateOrInsert(
                ['nim_nip' => $res['nim_nip'], 'booking_date' => $res['booking_date'], 'session_time' => $res['session_time']],
                [
                    'name' => $res['name'],
                    'email' => $res['email'],
                    'room_name' => $res['room_name'],
                    'status' => $res['status'],
                    'link_surat' => $res['link_surat'],
                    'picked_up_at' => $res['picked_up_at'],
                    'returned_at' => $res['returned_at'],
                    'notes_inventory' => $res['notes_inventory'],
                    'rejection_reason' => $res['rejection_reason'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 6. Create Dummy Surveys (IKM)
        $surveys = [
            ['q1' => 4, 'q2' => 4, 'q3' => 4, 'q4' => 4, 'q5' => 4, 'q6' => 4, 'q7' => 4, 'q8' => 4, 'q9' => 4, 'feedback' => 'Pelayanan ramah dan ruangan bersih.'],
            ['q1' => 4, 'q2' => 4, 'q3' => 4, 'q4' => 4, 'q5' => 4, 'q6' => 4, 'q7' => 4, 'q8' => 4, 'q9' => 4, 'feedback' => 'Koleksi buku digital semakin lengkap.'],
            ['q1' => 3, 'q2' => 3, 'q3' => 4, 'q4' => 3, 'q5' => 3, 'q6' => 4, 'q7' => 3, 'q8' => 3, 'q9' => 4, 'feedback' => 'AC di ruang baca utama kurang dingin.'],
        ];

        foreach ($surveys as $sur) {
            \Illuminate\Support\Facades\DB::table('surveys')->insert(array_merge($sur, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 7. Create Dummy Desiderata (Book Requests)
        $desiderata = [
            [
                'title' => 'Metodologi Penelitian Islam Modern',
                'author' => 'Prof. Dr. Faisal',
                'publisher' => 'Pustaka Akademika',
                'year' => 2023,
                'isbn' => '978-602-1234-56-7',
                'reference_url' => 'https://books.google.com/isbn/9786021234567',
                'proposer_name' => 'Diana Lestari',
                'proposer_status' => 'Dosen',
                'proposer_email' => 'diana.lestari@iainsorong.ac.id',
                'course' => 'Metodologi Riset',
                'estimated_students' => 45,
                'reason' => 'Buku ini rujukan utama mata kuliah metodologi penelitian.',
                'status' => 'pending'
            ],
            [
                'title' => 'Sejarah Kebudayaan Islam Papua',
                'author' => 'Dr. Ahmad M.',
                'publisher' => 'Papua Press',
                'year' => 2021,
                'isbn' => '978-602-9876-54-3',
                'reference_url' => null,
                'proposer_name' => 'Faisal Rahim',
                'proposer_status' => 'Mahasiswa',
                'proposer_email' => 'faisal.rahim@iainsorong.ac.id',
                'course' => 'Sejarah Islam Nusantara',
                'estimated_students' => 30,
                'reason' => 'Sangat berguna untuk menyusun skripsi tentang dakwah di Papua.',
                'status' => 'approved'
            ]
        ];

        foreach ($desiderata as $des) {
            \Illuminate\Support\Facades\DB::table('desiderata')->updateOrInsert(
                ['isbn' => $des['isbn']],
                array_merge($des, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // 8. Create Dummy Clearances (Bebas Pustaka)
        $clearances = [
            [
                'name' => 'Lukman Hakim',
                'nim_nidn' => '1204059901',
                'program_studi' => 'Pendidikan Agama Islam (PAI)',
                'phone' => '081234567890',
                'status' => 'pending',
                'thesis_file' => 'uploads/thesis/thesis_lukman.pdf',
                'receipt_file' => null,
            ],
            [
                'name' => 'Fatimah Az-Zahra',
                'nim_nidn' => '1204059902',
                'program_studi' => 'Hukum Keluarga Islam (HKI)',
                'phone' => '082345678901',
                'status' => 'approved',
                'thesis_file' => 'uploads/thesis/thesis_fatimah.pdf',
                'receipt_file' => 'uploads/receipts/clearance_receipt_fatimah.pdf',
            ],
        ];

        foreach ($clearances as $clear) {
            \App\Models\Clearance::updateOrCreate(
                ['nim_nidn' => $clear['nim_nidn']],
                $clear
            );
        }

        // 9. Create Dummy Memberships (Kartu Anggota Online)
        $memberships = [
            [
                'name' => 'Andi Wijaya',
                'nim_nip' => '230401001',
                'member_type' => 'mahasiswa',
                'email' => 'andi.wijaya@iainsorong.ac.id',
                'phone' => '083456789012',
                'photo_path' => 'uploads/photos/photo_andi.jpg',
                'status' => 'pending',
            ],
            [
                'name' => 'Dr. H. Sulaiman, M.Ag',
                'nim_nip' => '197508122005011002',
                'member_type' => 'dosen',
                'email' => 'sulaiman@iainsorong.ac.id',
                'phone' => '084567890123',
                'photo_path' => 'uploads/photos/photo_sulaiman.jpg',
                'status' => 'active',
            ],
        ];

        foreach ($memberships as $member) {
            \App\Models\Membership::updateOrCreate(
                ['nim_nip' => $member['nim_nip']],
                $member
            );
        }

        // 10. Create Dummy Podcasts
        $podcasts = [
            [
                'title' => 'Bincang Literasi #1: Pentingnya Mendeley dalam Tugas Akhir',
                'slug' => 'bincang-literasi-1-mendeley',
                'audio_url' => 'https://spotify.com/episode/abcde123',
                'video_url' => 'https://youtube.com/watch?v=youtube_podcast_1',
                'description' => 'Dalam episode perdana ini, UPT Perpustakaan mengupas tuntas cara instalasi, pengelolaan metadata, dan sitasi otomatis menggunakan Mendeley untuk mempercepat skripsi.',
                'duration' => '24:15',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Bincang Literasi #2: Mengenal Koleksi Khusus Papua Barat',
                'slug' => 'bincang-literasi-2-koleksi-papua',
                'audio_url' => 'https://spotify.com/episode/fghij456',
                'video_url' => 'https://youtube.com/watch?v=youtube_podcast_2',
                'description' => 'Mengeksplorasi warisan sejarah dan kajian antropologi melalui koleksi khusus Papua di lantai 2 UPT Perpustakaan IAIN Sorong.',
                'duration' => '32:40',
                'published_at' => now()->subDays(5),
            ]
        ];

        foreach ($podcasts as $pod) {
            \App\Models\Podcast::updateOrCreate(
                ['slug' => $pod['slug']],
                $pod
            );
        }
    }
}
