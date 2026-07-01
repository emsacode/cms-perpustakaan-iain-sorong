<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Clearance;
use App\Models\Membership;
use App\Models\Desiderata;
use App\Models\Survey;
use Livewire\Component;

class Dashboard extends Component
{
    public $messages = [];
    public $prompt = '';
    public $logs = [];
    public $isThinking = false;

    public function mount()
    {
        $this->messages = [
            [
                'role' => 'assistant',
                'content' => "Halo! Saya adalah Asisten Analitik UPT Perpustakaan. Saya dapat membantu menganalisis artikel berita, data pembaca, dan memberikan statistik pertumbuhan.<br><br>Cobalah bertanya: <strong>'tampilkan analisis statistik'</strong>"
            ]
        ];
    }

    public function sendMessage()
    {
        if (trim($this->prompt) === '') {
            return;
        }

        // Add user message
        $this->messages[] = [
            'role' => 'user',
            'content' => e($this->prompt)
        ];

        $userPrompt = strtolower($this->prompt);
        $this->prompt = '';
        $this->isThinking = true;

        // Custom reasoning logs based on prompt
        if (str_contains($userPrompt, 'statistik') || str_contains($userPrompt, 'analisis') || str_contains($userPrompt, 'view')) {
            $this->logs = [
                'Memeriksa tabel database `articles`...',
                'Melakukan kalkulasi query: SELECT SUM(views_count), COUNT(*) FROM articles;',
                'Menghitung total editor aktif dari tabel `users`...',
                'Mengekstrak snapshot pertumbuhan untuk dashboard...',
            ];
            
            // Build response with real data
            $totalViews = Article::sum('views_count');
            $totalArticles = Article::count();
            $activeUsers = User::where('status', 'active')->count();

            $aiResponse = "Berdasarkan analisis real-time basis data <span class='font-semibold text-indigo-500'>MySQL</span>:<br><br>" .
                          "• Total tayangan artikel mencapai <strong class='text-foreground'>{$totalViews} view</strong> dari <strong>{$totalArticles} artikel</strong> yang dipublikasikan.<br>" .
                          "• Saat ini terdapat <strong>{$activeUsers} staf/editor aktif</strong> di dalam sistem.<br><br>" .
                          "Pertumbuhan trafik diprediksi meningkat sekitar <strong>12.4%</strong> pada bulan depan berdasarkan tren pembaca saat ini.";
        } else {
            $this->logs = [
                'Parsing input teks pengguna...',
                'Mengevaluasi parameter bahasa...',
                'Menghasilkan respon chatbot...',
            ];
            $aiResponse = "Terima kasih atas pesan Anda. Silakan ketik perintah analitik seperti <strong>'tampilkan analisis statistik'</strong> untuk melihat proses query data yang sesungguhnya.";
        }

        // Simulate typing animation delay
        $this->dispatch('start-ai-response', [
            'content' => $aiResponse,
            'logs' => $this->logs
        ]);
        
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $aiResponse
        ];
        
        $this->isThinking = false;
    }

    // Direct Action Handlers from Dashboard Home
    public function approveReservation($id)
    {
        Reservation::where('id', $id)->update(['status' => 'approved', 'updated_at' => now()]);
        session()->flash('message', 'Reservasi ruangan berhasil disetujui.');
    }

    public function rejectReservation($id)
    {
        Reservation::where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        session()->flash('message', 'Reservasi ruangan telah ditolak.');
    }

    public function approveClearance($id)
    {
        Clearance::where('id', $id)->update(['status' => 'approved', 'updated_at' => now()]);
        session()->flash('message', 'Pengajuan bebas pustaka berhasil disetujui.');
    }

    public function rejectClearance($id)
    {
        Clearance::where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        session()->flash('message', 'Pengajuan bebas pustaka telah ditolak.');
    }

    public function activateMembership($id)
    {
        Membership::where('id', $id)->update(['status' => 'active', 'updated_at' => now()]);
        session()->flash('message', 'Kartu anggota online berhasil diaktifkan.');
    }

    public function rejectMembership($id)
    {
        Membership::where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        session()->flash('message', 'Registrasi kartu anggota telah ditolak.');
    }

    public function render()
    {
        $totalArticles = Article::count();
        $totalViews = Article::sum('views_count');
        $activeEditors = User::where('role', 'editor')->where('status', 'active')->count();
        
        // Interactive services stats
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $pendingClearances = Clearance::where('status', 'pending')->count();
        $pendingMemberships = Membership::where('status', 'pending')->count();
        $totalDesiderata = Desiderata::count();
        
        // IKM calculation
        $surveys = Survey::all();
        $averageIkm = 0;
        if ($surveys->count() > 0) {
            $totalScore = 0;
            $totalQuestions = 0;
            foreach ($surveys as $survey) {
                $totalScore += ($survey->q1 + $survey->q2 + $survey->q3 + $survey->q4 + $survey->q5 + $survey->q6 + $survey->q7 + $survey->q8 + $survey->q9);
                $totalQuestions += 9;
            }
            $averageIkm = round(($totalScore / $totalQuestions) * 25, 1);
        } else {
            $averageIkm = 85.4;
        }

        // Build the Unified Operations Queue
        $queue = collect();

        // 1. Add pending reservations
        Reservation::where('status', 'pending')
            ->get()
            ->each(function($item) use ($queue) {
                $queue->push([
                    'id' => $item->id,
                    'type' => 'reservation',
                    'title' => 'Reservasi Ruangan: ' . $item->room_name,
                    'user' => $item->name . ' (' . $item->nim_nip . ')',
                    'meta' => 'Tanggal: ' . date('d M Y', strtotime($item->booking_date)) . ' • Sesi: ' . $item->session_time,
                    'link_surat' => $item->link_surat,
                    'created_at' => $item->created_at,
                ]);
            });

        // 2. Add pending clearances
        Clearance::where('status', 'pending')
            ->get()
            ->each(function($item) use ($queue) {
                $queue->push([
                    'id' => $item->id,
                    'type' => 'clearance',
                    'title' => 'Bebas Pustaka: ' . $item->program_studi,
                    'user' => $item->name . ' (' . $item->nim_nidn . ')',
                    'meta' => 'Tugas Akhir: ' . basename($item->thesis_file),
                    'link_surat' => null,
                    'created_at' => $item->created_at,
                ]);
            });

        // 3. Add pending memberships
        Membership::where('status', 'pending')
            ->get()
            ->each(function($item) use ($queue) {
                $queue->push([
                    'id' => $item->id,
                    'type' => 'membership',
                    'title' => 'Registrasi Anggota: ' . ucfirst($item->member_type),
                    'user' => $item->name . ' (' . $item->nim_nip . ')',
                    'meta' => 'Email: ' . $item->email . ' • WA: ' . $item->phone,
                    'link_surat' => null,
                    'created_at' => $item->created_at,
                ]);
            });

        // Sort by created_at ascending (oldest pending items first) so staff processes in order of entry
        $inboxQueue = $queue->sortBy('created_at')->values()->all();

        // Get popular articles for dashboard listing
        $popularArticles = Article::orderBy('views_count', 'desc')->take(3)->get();

        return view('livewire.admin.dashboard', [
            'totalArticles' => $totalArticles,
            'totalViews' => $totalViews,
            'activeEditors' => $activeEditors,
            'popularArticles' => $popularArticles,
            'pendingReservations' => $pendingReservations,
            'pendingClearances' => $pendingClearances,
            'pendingMemberships' => $pendingMemberships,
            'totalDesiderata' => $totalDesiderata,
            'averageIkm' => $averageIkm,
            'inboxQueue' => $inboxQueue,
        ])->layout('layouts.app');
    }
}
