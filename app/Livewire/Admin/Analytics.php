<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Analytics extends Component
{
    public $activeTab = 'visitors';

    protected $queryString = [
        'activeTab' => ['except' => 'visitors']
    ];

    public function changeTab($tab)
    {
        if (in_array($tab, ['visitors', 'scopus', 'sinta'])) {
            $this->activeTab = $tab;
        }
    }

    public function render()
    {
        // 1. Visitor Stats Data
        $visitorStats = [
            'total_visitors_this_month' => 4820,
            'total_borrowings_this_month' => 1240,
            'active_members' => 3120,
            'total_books' => 24500,
            'visitors_chart' => [
                ['label' => 'Jan', 'value' => 3200],
                ['label' => 'Feb', 'value' => 3800],
                ['label' => 'Mar', 'value' => 4500],
                ['label' => 'Apr', 'value' => 2900],
                ['label' => 'Mei', 'value' => 4100],
                ['label' => 'Jun', 'value' => 4820],
            ],
            'demographics' => [
                ['label' => 'Mahasiswa FTIK', 'value' => 1420, 'percentage' => 45.5],
                ['label' => 'Mahasiswa Syariah', 'value' => 840, 'percentage' => 26.9],
                ['label' => 'Mahasiswa Ushuluddin', 'value' => 450, 'percentage' => 14.4],
                ['label' => 'Dosen & Staff', 'value' => 280, 'percentage' => 9.0],
                ['label' => 'Umum', 'value' => 130, 'percentage' => 4.2],
            ]
        ];

        // 2. Scopus Metrics Data
        $scopusStats = [
            'total_publications' => 84,
            'total_citations' => 312,
            'h_index' => 9,
            'subject_areas' => [
                ['subject' => 'Social Sciences', 'count' => 34, 'color' => 'bg-indigo-500'],
                ['subject' => 'Arts and Humanities', 'count' => 24, 'color' => 'bg-emerald-500'],
                ['subject' => 'Computer Science', 'count' => 14, 'color' => 'bg-sky-500'],
                ['subject' => 'Engineering', 'count' => 8, 'color' => 'bg-amber-500'],
                ['subject' => 'Others', 'count' => 4, 'color' => 'bg-rose-500'],
            ],
            'productive_authors' => [
                ['name' => 'Dr. H. Sulaiman, M.Ag', 'scopus_id' => '57218940200', 'docs' => 12, 'citations' => 54],
                ['name' => 'Prof. Dr. Faisal', 'scopus_id' => '57390212400', 'docs' => 9, 'citations' => 42],
                ['name' => 'Dr. Ahmad M. P.', 'scopus_id' => '57489021100', 'docs' => 7, 'citations' => 28],
                ['name' => 'Fatimah Az-Zahra, M.Si', 'scopus_id' => '57890123400', 'docs' => 5, 'citations' => 19],
            ],
            'yearly_trends' => [
                ['year' => '2021', 'docs' => 8],
                ['year' => '2022', 'docs' => 14],
                ['year' => '2023', 'docs' => 19],
                ['year' => '2024', 'docs' => 22],
                ['year' => '2025', 'docs' => 21],
            ]
        ];

        // 3. Sinta Metrics Data
        $sintaStats = [
            'total_sinta_score_3yr' => 4120,
            'total_sinta_score_overall' => 8940,
            'sinta_journals' => [
                ['level' => 'S1 (Sinta 1)', 'count' => 2, 'color' => 'bg-emerald-600'],
                ['level' => 'S2 (Sinta 2)', 'count' => 5, 'color' => 'bg-emerald-500'],
                ['level' => 'S3 (Sinta 3)', 'count' => 12, 'color' => 'bg-teal-500'],
                ['level' => 'S4 (Sinta 4)', 'count' => 18, 'color' => 'bg-teal-400'],
                ['level' => 'S5 (Sinta 5)', 'count' => 24, 'color' => 'bg-sky-400'],
                ['level' => 'S6 (Sinta 6)', 'count' => 8, 'color' => 'bg-rose-400'],
            ],
            'top_sinta_authors' => [
                ['name' => 'Dr. H. Sulaiman, M.Ag', 'score_3yr' => 840, 'score_overall' => 1520],
                ['name' => 'Prof. Dr. Faisal', 'score_3yr' => 690, 'score_overall' => 1240],
                ['name' => 'Dr. Ahmad M. P.', 'score_3yr' => 450, 'score_overall' => 910],
                ['name' => 'Faisal Rahim, M.H', 'score_3yr' => 380, 'score_overall' => 740],
            ]
        ];

        return view('livewire.admin.analytics', [
            'visitorStats' => $visitorStats,
            'scopusStats' => $scopusStats,
            'sintaStats' => $sintaStats,
        ])->layout('layouts.app');
    }
}
