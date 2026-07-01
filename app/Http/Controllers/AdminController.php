<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Post;
use App\Models\CatalogRecord;
use App\Models\FailedHarvest;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_books' => CatalogRecord::where('source_type', 'slims')->count(),
            'total_journals' => CatalogRecord::where('source_type', 'ojs')->count(),
            'total_theses' => CatalogRecord::where('source_type', 'eprints')->count(),
            'active_members' => Reservation::distinct('nim_nip')->count('nim_nip') + 142,
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        $failedHarvests = FailedHarvest::latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'failedHarvests' => $failedHarvests,
        ]);
    }

    /**
     * Display the Reservations manager page.
     */
    public function reservations()
    {
        $reservations = Reservation::latest()->paginate(15);
        return Inertia::render('Admin/Reservations', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Update a reservation status.
     */
    public function updateReservationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    /**
     * Display the Pages CMS manager page.
     */
    public function pages()
    {
        $pages = Page::latest()->get();
        return Inertia::render('Admin/Pages', [
            'pages' => $pages,
        ]);
    }

    /**
     * Update or create a dynamic page.
     */
    public function savePage(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:pages,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $request->id,
            'content' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        Page::updateOrCreate(
            ['id' => $request->id],
            [
                'title' => $request->title,
                'slug' => $request->slug,
                'content' => $request->content,
                'is_active' => $request->is_active,
            ]
        );

        return redirect()->back()->with('success', 'Halaman berhasil disimpan.');
    }

    /**
     * Display the Newspapers (Koran Digital) overview page.
     */
    public function newspapers()
    {
        return Inertia::render('Admin/Newspapers');
    }

    /**
     * Fetch digital newspapers from Halsen API (bypassing CORS)
     */
    public function getNewspapersData()
    {
        try {
            // 1. Get access token from cache or request new one
            $token = Cache::remember('ddag_access_token', 3500, function () {
                $response = Http::asJson()->post('https://v1.halsen.id/oauth/token', [
                    'client_id' => 'app-brim',
                    'client_secret' => 'demo-secret-brim-001',
                    'grant_type' => 'client_credentials',
                    'scope' => 'library:read',
                ]);

                if ($response->failed()) {
                    throw new \Exception('Authentication failed');
                }

                return $response->json()['access_token'];
            });

            // 2. Fetch books data
            $dataResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://v1.halsen.id/api/v1/library/books', [
                'limit' => 100,
                'offset' => 0,
            ]);

            if ($dataResponse->failed()) {
                throw new \Exception('Fetch books failed');
            }

            return response()->json($dataResponse->json());

        } catch (\Exception $e) {
            // Clear cache and retry once
            Cache::forget('ddag_access_token');
            
            try {
                $token = Cache::remember('ddag_access_token', 3500, function () {
                    $response = Http::asJson()->post('https://v1.halsen.id/oauth/token', [
                        'client_id' => 'app-brim',
                        'client_secret' => 'demo-secret-brim-001',
                        'grant_type' => 'client_credentials',
                        'scope' => 'library:read',
                    ]);

                    if ($response->failed()) {
                        throw new \Exception('Authentication retry failed');
                    }

                    return $response->json()['access_token'];
                });

                $dataResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get('https://v1.halsen.id/api/v1/library/books', [
                    'limit' => 100,
                    'offset' => 0,
                ]);

                if ($dataResponse->failed()) {
                    throw new \Exception('Fetch books retry failed');
                }

                return response()->json($dataResponse->json());
            } catch (\Exception $ex) {
                return response()->json([
                    'success' => false,
                    'message' => $ex->getMessage()
                ], 500);
            }
        }
    }
}
