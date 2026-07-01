<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Surveys extends Component
{
    use WithPagination;

    public $ikmScore = 0.0;
    public $ikmGrade = 'Cukup';
    public $averages = [];

    public function deleteSurvey($id)
    {
        DB::table('surveys')->where('id', $id)->delete();
        session()->flash('message', 'Data survei berhasil dihapus.');
    }

    public function render()
    {
        // Calculate IKM Stats
        $surveysList = DB::table('surveys')->get();
        $totalCount = $surveysList->count();

        if ($totalCount > 0) {
            $sumQ = array_fill(1, 9, 0);
            foreach ($surveysList as $sur) {
                for ($i = 1; $i <= 9; $i++) {
                    $prop = "q$i";
                    $sumQ[$i] += $sur->$prop;
                }
            }

            $totalAvg = 0.0;
            $this->averages = [];
            for ($i = 1; $i <= 9; $i++) {
                $avg = round($sumQ[$i] / $totalCount, 2);
                $this->averages[$i] = $avg;
                $totalAvg += $avg;
            }

            // Convert to 100 scale (IKM = average of averages * 25 - wait, PermenPAN scale uses conversion multiplier 25 because value is 1-4.
            // But since our scores are 1-5, multiplier is 20: (totalAvg / 9) * 20)
            $this->ikmScore = round(($totalAvg / 9) * 20, 2);

            // Grade based on score
            if ($this->ikmScore >= 88.31) {
                $this->ikmGrade = 'Sangat Baik (A)';
            } elseif ($this->ikmScore >= 76.61) {
                $this->ikmGrade = 'Baik (B)';
            } elseif ($this->ikmScore >= 65.00) {
                $this->ikmGrade = 'Kurang Baik (C)';
            } else {
                $this->ikmGrade = 'Tidak Baik (D)';
            }
        } else {
            $this->ikmScore = 0.0;
            $this->ikmGrade = 'Belum ada data';
            $this->averages = array_fill(1, 9, 0.0);
        }

        // Get paginated surveys with feedback
        $surveys = DB::table('surveys')->orderBy('created_at', 'desc')->paginate(6);

        // Map PermenPAN question labels
        $labels = [
            1 => 'Persyaratan Pelayanan',
            2 => 'Prosedur Layanan',
            3 => 'Waktu Pelayanan',
            4 => 'Biaya / Tarif',
            5 => 'Produk Spesifikasi Jenis Pelayanan',
            6 => 'Kompetensi Pelaksana',
            7 => 'Perilaku Pelaksana',
            8 => 'Kualitas Sarana & Prasarana',
            9 => 'Penanganan Pengaduan'
        ];

        return view('livewire.admin.surveys', [
            'surveys' => $surveys,
            'labels' => $labels,
            'totalCount' => $totalCount
        ])->layout('layouts.app');
    }
}
