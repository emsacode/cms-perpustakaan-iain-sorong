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
                'content' => 'Sistem Online Public Access Catalog (OPAC) adalah pintu gerbang utama untuk menemukan koleksi buku fisik di UPT Perpustakaan IAIN Sorong. Agar pencarian Anda lebih efisien, berikut adalah beberapa langkah praktis yang bisa Anda coba:<br><br><strong>1. Gunakan Kata Kunci yang Spesifik</strong><br>Alih-alih mencari kata kunci umum seperti "Islam", gunakan istilah yang lebih mendalam seperti "Sejarah Peradaban Islam Klasik" atau "Hukum Ekonomi Syariah".<br><br><strong>2. Manfaatkan Fitur Penyaringan (Filter)</strong><br>Saring hasil pencarian berdasarkan tahun terbit atau nama pengarang untuk memperkecil daftar hasil pencarian.<br><br><strong>3. Catat Nomor Panggil (Call Number) Buku</strong><br>Setelah menemukan buku yang dicari, catat nomor panggilnya (misalnya: 2X4.1 BAS s) untuk mempermudah pencarian fisik buku di rak perpustakaan.',
                'excerpt' => 'Panduan praktis mencari dan menemukan buku referensi fisik secara cepat menggunakan katalog online OPAC Perpustakaan IAIN Sorong.',
                'image' => 'https://source.boringavatars.com/sunset/800/opac-search-tips?colors=11853B,065E26,4CAF50,BFA25A,F7F7F2',
                'status' => 'published',
                'views_count' => 1200,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(4),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Panduan Parafrase Efektif untuk Menghindari Plagiarisme Turnitin',
                'content' => 'Turnitin digunakan di UPT Perpustakaan IAIN Sorong untuk mengukur kesamaan naskah tugas akhir dengan karya tulis ilmiah lain yang telah terbit. Agar skripsi Anda lolos uji kemiripan, lakukan teknik parafrase yang benar:<br><br><strong>1. Pahami Konsep Utamanya Dahulu</strong><br>Baca paragraf referensi hingga Anda benar-benar mengerti maksud penulis asli sebelum mulai menulis ulang.<br><br><strong>2. Gunakan Sinonim dan Ubah Struktur Kalimat</strong><br>Ubah kalimat aktif menjadi pasif atau sebaliknya, dan ganti kata-kata tertentu dengan sinonim yang padan tanpa mengubah arti asli kalimat.<br><br><strong>3. Tuliskan Sumber Kutipan Secara Jelas</strong><br>Parafrase tanpa sitasi tetap dianggap sebagai tindakan plagiarisme. Selalu sertakan nama pengarang dan tahun terbit sumber asli.',
                'excerpt' => 'Tips teknik parafrase kalimat yang benar agar naskah tugas akhir Anda lolos uji kemiripan (similarity test) Turnitin.',
                'image' => 'https://source.boringavatars.com/bauhaus/800/turnitin-plagiarism-tips?colors=11853B,065E26,4CAF50,BFA25A,F7F7F2',
                'status' => 'published',
                'views_count' => 2450,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(3),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['skripsi', 'mahasiswa']
            ],
            [
                'title' => 'Cara Mendaftar & Mengakses Jutaan E-Resource Perpustakaan Nasional',
                'content' => 'UPT Perpustakaan IAIN Sorong telah terintegrasi dengan layanan e-resources Perpustakaan Nasional RI (PNRI). Seluruh civitas akademika dapat mendaftar untuk membaca jutaan e-book, jurnal ilmiah internasional, dan manuskrip gratis:<br><br><strong>1. Buat Akun Anggota Online PNRI</strong><br>Kunjungi portal keanggotaan resmi PNRI melalui tautan yang tersedia di panduan e-resources perpustakaan kita.<br><br><strong>2. Masuk dan Cari Jurnal Langganan</strong><br>PNRI melanggan database dunia terkemuka seperti ProQuest, EBSCO, dan SpringerLink yang dapat Anda akses secara cuma-cuma.<br><br><strong>3. Unduh PDF untuk Referensi Riset</strong><br>Gunakan fitur pencarian untuk menemukan artikel relevan dan unduh langsung berkas PDF resminya.',
                'excerpt' => 'Langkah pendaftaran anggota dan cara mengakses jurnal internasional gratis melalui integrasi e-resources Perpustakaan Nasional.',
                'image' => 'https://source.boringavatars.com/marble/800/pnri-eresources-tips?colors=11853B,065E26,4CAF50,BFA25A,F7F7F2',
                'status' => 'published',
                'views_count' => 1890,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(2),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['literasi', 'mahasiswa']
            ],
            [
                'title' => 'Tips Menjaga Fokus Membaca Buku Tebal di Ruang Baca Utama',
                'content' => 'Membaca buku referensi tebal sering kali terasa membosankan dan melelahkan. UPT Perpustakaan IAIN Sorong menyediakan ruang baca yang tenang untuk mendukung kenyamanan Anda:<br><br><strong>1. Gunakan Teknik Pomodoro</strong><br>Membacalah selama 25 menit secara fokus, lalu beristirahatlah selama 5 menit untuk menyegarkan pikiran Anda sebelum kembali membaca.<br><br><strong>2. Jauhkan Gadget dari Meja Baca</strong><br>Matikan notifikasi atau simpan ponsel pintar Anda di dalam tas untuk menghindari gangguan fokus selama membaca.<br><br><strong>3. Buat Catatan Kecil (Mind Map)</strong><br>Tulis poin-poin penting pada buku catatan kecil untuk memetakan ide dan mempermudah ingatan Anda.',
                'excerpt' => 'Strategi meningkatkan fokus dan efisiensi membaca buku referensi akademis tebal di lingkungan perpustakaan.',
                'image' => 'https://source.boringavatars.com/sunset/800/focus-reading-tips?colors=11853B,065E26,4CAF50,BFA25A,F7F7F2',
                'status' => 'published',
                'views_count' => 980,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(1),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['perpustakaan']
            ],
            [
                'title' => 'Panduan Memanfaatkan Mendeley untuk Manajemen Referensi Tugas Akhir',
                'content' => 'Menyusun daftar pustaka secara manual sering kali menimbulkan kesalahan penulisan. Gunakan Mendeley Reference Manager untuk merapikan sitasi skripsi Anda secara otomatis:<br><br><strong>1. Instal Mendeley Desktop dan Plugin Word</strong><br>Pasang aplikasi Mendeley di laptop Anda dan hubungkan dengan aplikasi pengolah kata Microsoft Word.<br><br><strong>2. Impor File PDF Jurnal Anda</strong><br>Seret dan lepas file PDF jurnal ilmiah yang Anda unduh ke dalam perpustakaan Mendeley untuk mengekstrak metadatanya secara otomatis.<br><br><strong>3. Sisipkan Sitasi Instan</strong><br>Gunakan plugin Mendeley di Microsoft Word untuk menyisipkan kutipan dan menghasilkan daftar pustaka sekali klik dengan format APA atau style lainnya.',
                'excerpt' => 'Langkah mudah menggunakan Mendeley Reference Manager untuk membuat sitasi dan daftar pustaka skripsi secara otomatis.',
                'image' => 'https://source.boringavatars.com/bauhaus/800/mendeley-reference-tips?colors=11853B,065E26,4CAF50,BFA25A,F7F7F2',
                'status' => 'published',
                'views_count' => 3120,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now(),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['skripsi', 'literasi']
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
