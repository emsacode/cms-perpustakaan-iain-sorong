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
                'title' => 'Tips Menghindari Plagiarisme dalam Penulisan Skripsi',
                'content' => 'Plagiarisme adalah hal fatal dalam dunia akademik. Tulisan ini menjelaskan cara menulis kutipan dan memparafrase kalimat secara legal.',
                'excerpt' => 'Pelajari cara menulis kutipan dan memparafrase kalimat secara legal untuk menghindari plagiarisme fatal pada skripsi.',
                'image' => 'uploads/berita/post-1.jpg',
                'status' => 'published',
                'views_count' => 5690,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(5),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['skripsi', 'literasi']
            ],
            [
                'title' => 'Fasilitas Baru Ruang Home Theater UPT Perpustakaan',
                'content' => 'Perpustakaan kini menghadirkan bioskop mini edukatif untuk mendukung pemutaran film dokumenter ilmiah dan sejarah.',
                'excerpt' => 'Nikmati bioskop mini edukatif di Perpustakaan IAIN Sorong untuk pemutaran film dokumenter ilmiah dan sejarah.',
                'image' => 'uploads/berita/post-2.jpg',
                'status' => 'published',
                'views_count' => 3402,
                'seo_score' => 'good',
                'readability_score' => 'ok',
                'published_at' => now()->subDays(4),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Jam Layanan Ramadhan 1447 H UPT Perpustakaan IAIN Sorong',
                'content' => 'Selama bulan suci Ramadhan, layanan operasional perpustakaan disesuaikan mulai pukul 08:30 WIT hingga 15:00 WIT.',
                'excerpt' => 'Informasi penyesuaian jam layanan operasional UPT Perpustakaan IAIN Sorong selama bulan suci Ramadhan 1447 H.',
                'image' => 'uploads/berita/post-3.jpg',
                'status' => 'published',
                'views_count' => 1205,
                'seo_score' => 'ok',
                'readability_score' => 'good',
                'published_at' => now()->subDays(3),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Workshop Literasi Informasi Mahasiswa Baru 2026',
                'content' => 'Kegiatan rutin untuk melatih mahasiswa baru memanfaatkan sistem katalog OPAC, e-resources, dan pangkalan jurnal terindeks.',
                'excerpt' => 'Pelatihan rutin penggunaan OPAC, e-resources, dan jurnal terindeks bagi mahasiswa baru IAIN Sorong tahun 2026.',
                'image' => 'uploads/berita/post-5.jpg',
                'status' => 'published',
                'views_count' => 2310,
                'seo_score' => 'ok',
                'readability_score' => 'ok',
                'published_at' => now()->subDays(2),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['literasi', 'mahasiswa']
            ],
            [
                'title' => 'Mengenal Koleksi Referensi Khusus Papua di Lantai 2',
                'content' => 'Koleksi ini menyimpan arsip sejarah, kebudayaan lokal, kajian antropologi, dan dokumentasi pembangunan Papua Barat.',
                'excerpt' => 'Kunjungi Lantai 2 untuk mengakses arsip sejarah, kebudayaan lokal, dan kajian antropologi pembangunan Papua.',
                'image' => 'uploads/berita/post-6.jpg',
                'status' => 'draft',
                'views_count' => 450,
                'seo_score' => 'none',
                'readability_score' => 'ok',
                'published_at' => null,
                'cats' => ['jurusan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Arsip Berita Cetak Perpustakaan Lama',
                'content' => 'Berita-berita lama perpustakaan.',
                'excerpt' => 'Kumpulan arsip berita cetak dokumentasi sejarah perpustakaan lama.',
                'image' => 'uploads/berita/post-7.png',
                'status' => 'trash',
                'views_count' => 12,
                'seo_score' => 'none',
                'readability_score' => 'none',
                'published_at' => now()->subYears(1),
                'cats' => ['news-update'],
                'tags_list' => []
            ],
            [
                'title' => 'Sosialisasi Penjadwalan Kegiatan Dies Natalis',
                'content' => 'Dies Natalis IAIN Sorong tahun ini akan diselenggarakan dengan meriah.',
                'excerpt' => 'Persiapan Dies Natalis IAIN Sorong dengan rangkaian sosialisasi kegiatan dan perlombaan bagi mahasiswa.',
                'image' => 'uploads/berita/post-8.jpeg',
                'status' => 'scheduled',
                'views_count' => 0,
                'seo_score' => 'ok',
                'readability_score' => 'none',
                'published_at' => now()->addDays(5),
                'cats' => ['kegiatan'],
                'tags_list' => ['mahasiswa']
            ],
            [
                'title' => 'Kunjungan Studi Banding UPT Perpustakaan IAIN Fattahul Yaqin',
                'content' => 'Hari ini UPT Perpustakaan IAIN Sorong menerima kunjungan kehormatan dari tim UPT Perpustakaan IAIN Fattahul Yaqin untuk berdiskusi mengenai transformasi digital SLiMS dan penerapan sistem manajemen HONAI.',
                'excerpt' => 'UPT Perpustakaan IAIN Sorong menerima kunjungan kehormatan dari IAIN Fattahul Yaqin guna membahas transformasi digital.',
                'image' => 'uploads/berita/post-9.jpeg',
                'status' => 'published',
                'views_count' => 1890,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(1),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Layanan Pengembalian Buku Mandiri Menggunakan Drop Box',
                'content' => 'Pemustaka kini dapat mengembalikan buku di luar jam layanan operasional melalui Drop Box mandiri yang disediakan di depan pintu masuk gedung perpustakaan.',
                'excerpt' => 'Kini pemustaka bisa mengembalikan buku kapan saja lewat kotak Drop Box mandiri di area lobi perpustakaan.',
                'image' => 'uploads/berita/post-10.jpg',
                'status' => 'published',
                'views_count' => 2450,
                'seo_score' => 'good',
                'readability_score' => 'ok',
                'published_at' => now()->subHours(12),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['mahasiswa', 'perpustakaan']
            ],
            [
                'title' => 'Daftar Buku Terpopuler yang Paling Banyak Dipinjam Semester Ganjil',
                'content' => 'Berikut adalah rilis berkala daftar 10 judul buku teks bidang metodologi penelitian dan kajian Islam yang paling sering dipinjam oleh pemustaka sepanjang semester ini.',
                'excerpt' => 'Rilis resmi daftar 10 buku terlaris yang paling banyak dipinjam oleh pemustaka selama semester ganjil.',
                'image' => 'uploads/berita/post-11.jpeg',
                'status' => 'published',
                'views_count' => 3120,
                'seo_score' => 'ok',
                'readability_score' => 'good',
                'published_at' => now()->subHours(6),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['mahasiswa']
            ],
            [
                'title' => 'Penerimaan Hibah Buku Referensi Sejarah Islam Klasik',
                'content' => 'UPT Perpustakaan IAIN Sorong menerima hibah 150 eksemplar buku referensi keagamaan dan sejarah Islam klasik dari Kementerian Agama RI.',
                'excerpt' => 'UPT Perpustakaan menerima hibah 150 buku sejarah Islam klasik dari Kementerian Agama untuk melengkapi koleksi referensi.',
                'image' => 'uploads/berita/post-12.jpeg',
                'status' => 'published',
                'views_count' => 980,
                'seo_score' => 'ok',
                'readability_score' => 'ok',
                'published_at' => now()->subHours(3),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Tips Sukses Mengakses E-Journal Terakreditasi SINTA',
                'content' => 'Panduan taktis bagi dosen dan mahasiswa tingkat akhir untuk melakukan penelusuran artikel ilmiah secara efektif pada pangkalan data jurnal terindeks SINTA.',
                'excerpt' => 'Panduan praktis melakukan pencarian artikel ilmiah pada database e-journal nasional terakreditasi SINTA.',
                'image' => 'uploads/berita/post-13.jpeg',
                'status' => 'published',
                'views_count' => 4210,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subHours(1),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['skripsi', 'literasi']
            ],
            [
                'title' => 'Pameran Buku Karya Dosen dan Penulis Lokal Papua',
                'content' => 'UPT Perpustakaan menggelar pameran karya ilmiah, buku biografi, dan antologi sastra yang ditulis langsung oleh civitas akademika IAIN Sorong serta penulis lokal Papua.',
                'excerpt' => 'Perpustakaan memamerkan karya tulis ilmiah dan buku sastra hasil karya dosen IAIN Sorong dan penulis lokal Papua.',
                'image' => 'uploads/berita/post-14.jpeg',
                'status' => 'published',
                'views_count' => 1450,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subHours(2),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Layanan E-Resource Perpustakaan Nasional RI (PNRI) Kini Dapat Diakses Bebas',
                'content' => 'Seluruh mahasiswa dan dosen IAIN Sorong kini dapat mengakses jutaan koleksi e-resources perpustakaan nasional secara gratis setelah integrasi pendaftaran anggota massal selesai.',
                'excerpt' => 'Akses gratis ke jutaan jurnal, e-book, dan dokumen ilmiah PNRI bagi civitas akademika IAIN Sorong.',
                'image' => 'uploads/berita/post-15.jpeg',
                'status' => 'published',
                'views_count' => 2890,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subHours(4),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['literasi', 'mahasiswa']
            ],
            [
                'title' => 'Sosialisasi Turnitin bagi Mahasiswa Tingkat Akhir untuk Uji Kesamaan Skripsi',
                'content' => 'Perpustakaan menyelenggarakan panduan teknis penggunaan akun Turnitin institusi untuk melakukan pengujian tingkat kemiripan naskah tugas akhir mahasiswa.',
                'excerpt' => 'Petunjuk teknis pengujian kemiripan naskah skripsi menggunakan akun Turnitin resmi Perpustakaan.',
                'image' => 'uploads/berita/post-16.png',
                'status' => 'published',
                'views_count' => 3560,
                'seo_score' => 'good',
                'readability_score' => 'ok',
                'published_at' => now()->subHours(8),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['skripsi', 'mahasiswa']
            ],
            [
                'title' => 'Perpustakaan Mengadakan Pemilihan Duta Baca Mahasiswa 2026',
                'content' => 'Ajang pencarian duta baca perpustakaan untuk mempromosikan gerakan minat baca, literasi informasi, dan kegiatan edukatif di lingkungan kampus.',
                'excerpt' => 'Ikuti pemilihan Duta Baca Perpustakaan IAIN Sorong tahun 2026 untuk mengkampanyekan gerakan literasi.',
                'image' => 'uploads/berita/post-17.jpg',
                'status' => 'published',
                'views_count' => 1230,
                'seo_score' => 'ok',
                'readability_score' => 'good',
                'published_at' => now()->subHours(18),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['mahasiswa']
            ],
            [
                'title' => 'Optimalisasi Sistem Pencarian Buku Menggunakan SLiMS 9 Bulian',
                'content' => 'Sistem katalog online (OPAC) perpustakaan kini diperbarui ke versi SLiMS 9 Bulian untuk mempermudah pencarian koleksi fisik dengan akurasi dan kecepatan tinggi.',
                'excerpt' => 'Pembaruan sistem OPAC ke versi SLiMS 9 Bulian guna mempermudah pencarian koleksi buku perpustakaan.',
                'image' => 'uploads/berita/post-18.jpg',
                'status' => 'published',
                'views_count' => 1980,
                'seo_score' => 'ok',
                'readability_score' => 'ok',
                'published_at' => now()->subDays(2),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Cara Menggunakan Consensus MCP untuk Riset Akademik dan Penulisan Skripsi',
                'content' => 'Di era digital saat ini, melakukan literature review atau tinjauan pustaka sering kali menjadi tahapan paling menyita waktu dalam penulisan skripsi maupun jurnal ilmiah. Mahasiswa harus memilah ribuan dokumen untuk menemukan referensi yang valid dan tepercaya.<br><br>Untuk membantu mengatasi tantangan ini, UPT Perpustakaan IAIN Sorong memperkenalkan inovasi pencarian ilmiah berbasis AI: <strong>Consensus MCP</strong>. Melalui protokol ini, Anda dapat menghubungkan mesin pencari ilmiah Consensus langsung ke asisten AI Anda untuk mencari lebih dari 200 juta artikel jurnal peer-reviewed secara instan.<br><br><h2>Apa itu Consensus MCP?</h2><strong>Consensus</strong> (consensus.app) adalah mesin pencari akademis bertenaga kecerdasan buatan yang mengindeks jutaan makalah ilmiah terverifikasi. Sedangkan <strong>MCP (Model Context Protocol)</strong> adalah standar terbuka yang dikembangkan oleh Anthropic untuk menghubungkan asisten AI seperti Claude dengan data atau alat eksternal.<br><br>Dengan mengintegrasikan <strong>Consensus MCP</strong>, asisten AI Anda tidak lagi sekadar "menebak" atau memunculkan halusinasi informasi. AI akan mencari bukti ilmiah langsung dari database jurnal, memberikan ringkasan konsensus dari para ilmuwan, dan menyertakan kutipan (citations) yang sah secara akademis.<br><br><h2>Manfaat Utama untuk Civitas Akademika IAIN Sorong</h2>Menggunakan alat ini di lingkungan kampus memberikan beberapa keuntungan strategis bagi mahasiswa dan dosen:<br>1. <strong>Bebas Halusinasi Data:</strong> Kutipan referensi bersumber langsung dari penerbit jurnal akademis terindeks.<br>2. <strong>Menemukan Konsensus Ilmiah:</strong> Mengetahui secara cepat apakah sebuah teori disepakati oleh mayoritas peneliti atau masih menjadi perdebatan.<br>3. <strong>Efisiensi Waktu:</strong> Memangkas waktu pencarian jurnal dari hitungan jam menjadi hitungan detik.<br>4. <strong>Format Sitasi Instan:</strong> Referensi dapat langsung diformat ke gaya penulisan ilmiah (APA, MLA, atau Chicago).<br><br><h2>Cara Pemasangan & Integrasi Consensus MCP</h2>Anda dapat menghubungkan Consensus ke asisten AI Anda (seperti Claude Desktop atau editor kode seperti Cursor) dengan mengikuti langkah mudah di bawah ini.<br><br><h3>1. Integrasi pada Claude Desktop</h3>Bagi dosen atau mahasiswa yang menggunakan aplikasi Claude Desktop, Anda cukup menambahkan konfigurasi server MCP.<br><ul><li>Buka file konfigurasi Claude (claude_desktop_config.json).</li><li>Tambahkan baris berikut di dalam objek mcpServers:</li></ul><pre><code>{\n  \"mcpServers\": {\n    \"Consensus\": {\n      \"command\": \"npx\",\n      \"args\": [\n        \"-y\",\n        \"mcp-remote@latest\",\n        \"https://mcp.consensus.app/mcp\"\n      ]\n    }\n  }\n}</code></pre><ul><li>Restart Claude Desktop. Asisten AI Anda kini memiliki tombol pencarian riset Consensus.</li></ul><br><h3>2. Integrasi pada Editor Cursor (Untuk Peneliti & Developer)</h3>Jika Anda menggunakan editor teks modern seperti Cursor untuk menyunting naskah LaTeX atau kode riset:<ul><li>Masuk ke Settings → Tools & MCP → klik New MCP Server.</li><li>Isi nama: Consensus.</li><li>Pilih type: command.</li><li>Masukkan command: npx -y mcp-remote@latest https://mcp.consensus.app/mcp.</li><li>Simpan. Alat pencarian ilmiah kini aktif secara langsung di panel chat Cursor Anda.</li></ul><br><h2>Tips Menulis Skripsi dengan Bantuan Consensus</h2>Agar hasil pencarian optimal dan sesuai dengan kaidah penulisan karya tulis ilmiah yang baik, terapkan tips berikut:<br><ul><li><strong>Ajukan Pertanyaan Ya/Tidak yang Spesifik:</strong> Consensus bekerja paling baik saat menjawab pertanyaan langsung, misalnya: "Apakah pembelajaran online efektif meningkatkan kemandirian belajar mahasiswa?"</li><li><strong>Verifikasi Selalu Sumber Asli:</strong> Meskipun asisten AI menyajikan ringkasan, pastikan Anda mengklik tautan jurnal yang disediakan untuk membaca abstrak lengkap guna menghindari salah tafsir.</li><li><strong>Gunakan Fitur Filter:</strong> Saring makalah berdasarkan tahun terbit terbaru (misal: 5 tahun terakhir) untuk memastikan kebaruan riset (state of the art).</li></ul><br><h2>Kesimpulan</h2>Teknologi <strong>Consensus MCP</strong> membawa perpustakaan digital ke level berikutnya. Integrasi ini membuat pengerjaan skripsi, tesis, dan publikasi ilmiah dosen IAIN Sorong menjadi jauh lebih cepat, akurat, dan kredibel.<br><br>Kunjungi UPT Perpustakaan IAIN Sorong untuk mendapatkan pendampingan lebih lanjut mengenai pemanfaatan alat-alat riset berbasis AI dalam menunjang studi Anda!',
                'excerpt' => 'Pelajari cara mengintegrasikan Consensus MCP ke asisten AI (Claude/Cursor) untuk mencari referensi jurnal ilmiah terverifikasi secara cepat dan otomatis untuk skripsi Anda.',
                'image' => 'uploads/berita/post-15.jpeg',
                'status' => 'published',
                'views_count' => 1540,
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
