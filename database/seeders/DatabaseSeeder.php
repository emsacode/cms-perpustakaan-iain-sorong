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

        $sdgsTags = [
            ['name' => 'SDGs 4: Pendidikan Berkualitas', 'slug' => 'sdgs-4'],
            ['name' => 'SDGs 9: Industri, Inovasi, & Infrastruktur', 'slug' => 'sdgs-9'],
            ['name' => 'SDGs 17: Kemitraan untuk Mencapai Tujuan', 'slug' => 'sdgs-17'],
        ];
        $sdgsIds = [];
        foreach ($sdgsTags as $s) {
            $sObj = \App\Models\SdgsTag::updateOrCreate(['slug' => $s['slug']], ['name' => $s['name']]);
            $sdgsIds[$s['slug']] = $sObj->id;
        }

        // 3. Create Dummy Articles
        $articles = [
            [
                'title' => 'Tips Menghindari Plagiarisme dalam Penulisan Skripsi',
                'content' => 'Plagiarisme adalah hal fatal dalam dunia akademik. Tulisan ini menjelaskan cara menulis kutipan dan memparafrase kalimat secara legal.',
                'status' => 'published',
                'views_count' => 5690,
                'seo_score' => 'good',
                'readability_score' => 'good',
                'published_at' => now()->subDays(5),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['skripsi', 'literasi'],
                'sdgs' => ['sdgs-4']
            ],
            [
                'title' => 'Fasilitas Baru Ruang Home Theater UPT Perpustakaan',
                'content' => 'Perpustakaan kini menghadirkan bioskop mini edukatif untuk mendukung pemutaran film dokumenter ilmiah dan sejarah.',
                'status' => 'published',
                'views_count' => 3402,
                'seo_score' => 'good',
                'readability_score' => 'ok',
                'published_at' => now()->subDays(4),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan'],
                'sdgs' => ['sdgs-9']
            ],
            [
                'title' => 'Jam Layanan Ramadhan 1447 H UPT Perpustakaan IAIN Sorong',
                'content' => 'Selama bulan suci Ramadhan, layanan operasional perpustakaan disesuaikan mulai pukul 08:30 WIT hingga 15:00 WIT.',
                'status' => 'published',
                'views_count' => 1205,
                'seo_score' => 'ok',
                'readability_score' => 'good',
                'published_at' => now()->subDays(3),
                'cats' => ['news-update', 'layanan'],
                'tags_list' => ['perpustakaan'],
                'sdgs' => []
            ],
            [
                'title' => 'Workshop Literasi Informasi Mahasiswa Baru 2026',
                'content' => 'Kegiatan rutin untuk melatih mahasiswa baru memanfaatkan sistem katalog OPAC, e-resources, dan pangkalan jurnal terindeks.',
                'status' => 'published',
                'views_count' => 2310,
                'seo_score' => 'ok',
                'readability_score' => 'ok',
                'published_at' => now()->subDays(2),
                'cats' => ['news-update', 'kegiatan'],
                'tags_list' => ['literasi', 'mahasiswa'],
                'sdgs' => ['sdgs-4', 'sdgs-17']
            ],
            [
                'title' => 'Mengenal Koleksi Referensi Khusus Papua di Lantai 2',
                'content' => 'Koleksi ini menyimpan arsip sejarah, kebudayaan lokal, kajian antropologi, dan dokumentasi pembangunan Papua Barat.',
                'status' => 'draft',
                'views_count' => 450,
                'seo_score' => 'none',
                'readability_score' => 'ok',
                'published_at' => null,
                'cats' => ['jurusan'],
                'tags_list' => ['perpustakaan'],
                'sdgs' => []
            ],
            [
                'title' => 'Arsip Berita Cetak Perpustakaan Lama',
                'content' => 'Berita-berita lama perpustakaan.',
                'status' => 'trash',
                'views_count' => 12,
                'seo_score' => 'none',
                'readability_score' => 'none',
                'published_at' => now()->subYears(1),
                'cats' => ['news-update'],
                'tags_list' => [],
                'sdgs' => []
            ],
            [
                'title' => 'Sosialisasi Penjadwalan Kegiatan Dies Natalis',
                'content' => 'Dies Natalis IAIN Sorong tahun ini akan diselenggarakan dengan meriah.',
                'status' => 'scheduled',
                'views_count' => 0,
                'seo_score' => 'ok',
                'readability_score' => 'none',
                'published_at' => now()->addDays(5),
                'cats' => ['kegiatan'],
                'tags_list' => ['mahasiswa'],
                'sdgs' => ['sdgs-17']
            ]
        ];

        foreach ($articles as $art) {
            $a = Article::updateOrCreate(
                ['title' => $art['title']],
                [
                    'user_id' => $admin->id,
                    'content' => $art['content'],
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

            // Sync SDGs
            $sIds = [];
            foreach ($art['sdgs'] as $sSlug) {
                if (isset($sdgsIds[$sSlug])) {
                    $sIds[] = $sdgsIds[$sSlug];
                }
            }
            $a->sdgsTags()->sync($sIds);
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
                'link_surat' => 'https://example.com/surat-ahmad.pdf'
            ],
            [
                'name' => 'Siti Aminah',
                'nim_nip' => '202410045',
                'email' => 'siti.aminah@iainsorong.ac.id',
                'room_name' => 'Ruang Home Theater',
                'booking_date' => now()->addDays(1)->format('Y-m-d'),
                'session_time' => '13:00 - 15:00 WIT',
                'status' => 'pending',
                'link_surat' => 'https://example.com/surat-siti.pdf'
            ],
            [
                'name' => 'Dr. H. Muhammad',
                'nim_nip' => '197508122005011002',
                'email' => 'muhammad@iainsorong.ac.id',
                'room_name' => 'Ruang Multimedia',
                'booking_date' => now()->format('Y-m-d'),
                'session_time' => '10:00 - 12:00 WIT',
                'status' => 'completed',
                'link_surat' => null
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
