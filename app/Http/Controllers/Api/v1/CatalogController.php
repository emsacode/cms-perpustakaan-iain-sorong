<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CatalogRecord;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Get a paginated list of catalog records with search and filters.
     */
    public function index(Request $request)
    {
        $query = CatalogRecord::query();

        // Filter by source type
        if ($request->filled('source')) {
            $query->where('source_type', $request->source);
        }

        // Full-text search or fallback to LIKE
        if ($request->filled('q')) {
            $search = $request->q;
            
            if (config('database.default') === 'sqlite') {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('creator', 'like', "%{$search}%")
                      ->orWhere('abstract', 'like', "%{$search}%");
                });
            } else {
                // MySQL Fulltext Search
                $query->whereRaw("MATCH(title) AGAINST(? IN BOOLEAN MODE)", [$search]);
            }
        }

        // Sort by latest created_at by default
        $records = $query->latest()->paginate(12);

        return response()->json($records);
    }
}
