<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = [];

    /**
     * Memeriksa dan membatalkan reservasi yang disetujui tetapi tidak diambil kuncinya setelah 15 menit.
     */
    public static function checkAndCancelExpiredReservations(): void
    {
        $approvedReservations = self::where('status', 'approved')->get();

        foreach ($approvedReservations as $res) {
            $parts = explode(' - ', $res->session_time);
            if (count($parts) < 1) {
                continue;
            }

            $startTimeStr = trim($parts[0]); // Mengambil "09:00" dari "09:00 - 11:00 WIT"
            
            try {
                // Konversi tanggal booking dan jam mulai sesi ke Carbon dengan timezone Asia/Jayapura (WIT)
                $startDateTime = \Carbon\Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $res->booking_date . ' ' . $startTimeStr,
                    'Asia/Jayapura'
                );

                // Batas waktu toleransi 15 menit
                $toleranceTime = $startDateTime->copy()->addMinutes(15);

                // Bandingkan dengan waktu saat ini (sekarang)
                if (now()->greaterThan($toleranceTime)) {
                    $res->update([
                        'status' => 'cancelled',
                        'rejection_reason' => 'Dibatalkan otomatis oleh sistem karena kunci tidak diambil lebih dari 15 menit sejak sesi dimulai.',
                        'updated_at' => now()
                    ]);
                }
            } catch (\Exception $e) {
                logger()->error("Gagal memproses auto-cancel untuk reservasi #{$res->id}: " . $e->getMessage());
            }
        }
    }
}
