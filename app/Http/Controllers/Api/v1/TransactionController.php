<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Desiderata;
use App\Models\Survey;
use App\Models\Clearance;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class TransactionController extends Controller
{
    /**
     * Submit a new room reservation.
     */
    public function reserveRoom(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim_nip' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'room_name' => 'required|string|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'session_time' => 'required|string|max:255',
            'link_surat' => 'nullable|url|max:255',
        ]);

        // 1. Cek duplikasi di tingkat aplikasi (untuk respon cepat/user-friendly)
        $exists = Reservation::where('room_name', $validated['room_name'])
            ->where('booking_date', $validated['booking_date'])
            ->where('session_time', $validated['session_time'])
            ->where('status', '!=', 'rejected')
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ruangan sudah dipesan untuk tanggal dan sesi tersebut.',
                'errors' => [
                    'room_name' => ['Jadwal ini sudah tidak tersedia. Silakan pilih sesi atau hari lain.']
                ]
            ], 422);
        }

        // 2. Simpan ke database dengan pengamanan try-catch terhadap DB Unique Constraint (mengatasi Race Condition)
        try {
            $reservation = Reservation::create(array_merge($validated, ['status' => 'pending']));
            
            return response()->json([
                'message' => 'Reservasi berhasil diajukan dan sedang menunggu persetujuan pustakawan.',
                'data' => $reservation
            ], 201);
        } catch (QueryException $e) {
            // Tangani error duplicate key index (SQLSTATE 23000)
            if ($e->getCode() == '23000' || str_contains($e->getMessage(), 'uq_room_booking')) {
                return response()->json([
                    'message' => 'Maaf, bentrokan jadwal baru saja terjadi. Ruangan ini baru saja dipesan oleh pengguna lain untuk sesi yang sama.',
                    'errors' => [
                        'room_name' => ['Jadwal ini sudah tidak tersedia.']
                    ]
                ], 422);
            }
            
            throw $e;
        }
    }

    /**
     * Submit a new book recommendation (Desiderata).
     */
    public function submitDesiderata(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'isbn' => 'nullable|string|max:50',
            'reference_url' => 'nullable|string|max:255',
            'proposer_name' => 'required|string|max:255',
            'proposer_status' => 'required|string|max:100',
            'proposer_email' => 'required|email|max:255',
            'course' => 'nullable|string|max:255',
            'estimated_students' => 'nullable|integer',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $desiderata = Desiderata::create($validated);

        return response()->json([
            'message' => 'Usulan buku berhasil dikirim. Terima kasih atas kontribusi Anda.',
            'data' => $desiderata
        ], 201);
    }

    /**
     * Submit a new IKM Survey.
     */
    public function submitSurvey(Request $request)
    {
        $validated = $request->validate([
            'q1' => 'required|integer|between:1,4',
            'q2' => 'required|integer|between:1,4',
            'q3' => 'required|integer|between:1,4',
            'q4' => 'required|integer|between:1,4',
            'q5' => 'required|integer|between:1,4',
            'q6' => 'required|integer|between:1,4',
            'q7' => 'required|integer|between:1,4',
            'q8' => 'required|integer|between:1,4',
            'q9' => 'required|integer|between:1,4',
            'feedback' => 'nullable|string',
        ]);

        $survey = Survey::create($validated);

        return response()->json([
            'message' => 'Terima kasih! Survei kepuasan Anda berhasil disimpan.',
            'data' => $survey
        ], 201);
    }

    /**
     * Submit a new Clearance (Bebas Pustaka) request with PDF thesis file.
     */
    public function submitClearance(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim_nidn' => 'required|string|max:100',
            'program_studi' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'thesis_file' => 'required|file|mimes:pdf|max:10240', // PDF only, max 10MB
        ]);

        // Secure file upload: randomize filename and store in storage/app/public/uploads/thesis
        if ($request->hasFile('thesis_file')) {
            $path = $request->file('thesis_file')->store('uploads/thesis', 'public');
            $validated['thesis_file'] = $path;
        }

        $clearance = Clearance::create(array_merge($validated, ['status' => 'pending']));

        return response()->json([
            'message' => 'Pengajuan Bebas Pustaka Anda berhasil dikirim dan sedang dalam peninjauan.',
            'data' => $clearance
        ], 201);
    }

    /**
     * Submit a new Membership (Anggota Online) registration with profile photo.
     */
    public function submitMembership(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim_nip' => 'required|string|max:100|unique:memberships,nim_nip',
            'member_type' => 'required|in:mahasiswa,dosen,staff,umum',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Image only, max 2MB
        ]);

        // Secure file upload: randomize photo path and store in storage/app/public/uploads/photos
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('uploads/photos', 'public');
            $validated['photo_path'] = $path;
        }

        // Unset temporary photo request key
        unset($validated['photo']);

        $membership = Membership::create(array_merge($validated, ['status' => 'pending']));

        return response()->json([
            'message' => 'Registrasi anggota k-online berhasil dikirim. Silakan tunggu verifikasi admin.',
            'data' => $membership
        ], 201);
    }
}
