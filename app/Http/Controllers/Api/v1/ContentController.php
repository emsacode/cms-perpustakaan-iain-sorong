<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Epaper;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\ServiceHour;
use App\Models\Page;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ContentController extends Controller
{
    /**
     * Get published posts.
     */
    public function posts(Request $request)
    {
        $query = Article::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->latest('published_at');

        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($request->boolean('all')) {
            $articles = $query->get()->map(function ($article) {
                return $this->formatArticle($article);
            });
            return response()->json($articles);
        }

        $paginator = $query->paginate(12);

        $paginator->getCollection()->transform(function ($article) {
            return $this->formatArticle($article);
        });

        return response()->json($paginator);
    }

    /**
     * Get a specific post by slug.
     */
    public function postDetail($slug)
    {
        $article = Article::with(['user', 'categories', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($this->formatArticle($article));
    }

    /**
     * Format Article to Astro compatible schema.
     */
    private function formatArticle($article)
    {
        // Calculate read time: avg 200 words per minute
        $wordCount = str_word_count(strip_tags($article->content));
        $readMinutes = max(1, ceil($wordCount / 200));
        $readTime = $readMinutes . ' menit baca';

        // Author
        $author = $article->user->name ?? 'Admin Perpustakaan';

        // Category (first related category, or 'Berita' if none)
        $category = 'Berita';
        if ($article->categories->isNotEmpty()) {
            $category = $article->categories->first()->name;
        }

        // Image
        $image = $article->image;
        if ($image) {
            if (!str_starts_with($image, '/images/') && !str_starts_with($image, 'http')) {
                $image = asset('storage/' . $image);
            }
        } else {
            $image = '/images/berita/post-1.jpg'; // fallback
        }

        return [
            'slug' => $article->slug,
            'content' => $article->content,
            'data' => [
                'title' => $article->title,
                'description' => $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content), 150),
                'image' => $image,
                'category' => $category,
                'author' => $author,
                'date' => $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('Y-m-d') : $article->created_at->format('Y-m-d'),
                'readTime' => $readTime
            ]
        ];
    }

    /**
     * Get epapers.
     */
    public function epapers()
    {
        $epapers = Epaper::latest('published_date')->paginate(12);

        // Map absolute cover/pdf URLs
        $epapers->getCollection()->transform(function ($epaper) {
            $epaper->pdf_url = asset('storage/' . $epaper->pdf_file);
            $epaper->cover_url = $epaper->cover_image ? asset('storage/' . $epaper->cover_image) : null;
            return $epaper;
        });

        return response()->json($epapers);
    }

    /**
     * Get FAQs.
     */
    public function faqs()
    {
        $faqs = \Illuminate\Support\Facades\Cache::rememberForever('faqs_list', function () {
            return Faq::orderBy('sort_order')->get();
        });
        return response()->json($faqs);
    }

    /**
     * Get Gallery items.
     */
    public function galleries()
    {
        $galleries = Gallery::latest()->get()->map(function ($item) {
            $item->image_url = asset('storage/' . $item->image);
            return $item;
        });
        
        return response()->json($galleries);
    }

    /**
     * Get Service Hours.
     */
    public function serviceHours()
    {
        $hours = ServiceHour::all();
        return response()->json($hours);
    }

    /**
     * Get active dynamic pages.
     */
    public function pages()
    {
        $pages = \Illuminate\Support\Facades\Cache::rememberForever('pages_active', function () {
            return Page::where('is_active', true)->select('id', 'title', 'slug')->get()->toArray();
        });
        return response()->json($pages);
    }

    /**
     * Get details of a specific dynamic page.
     */
    public function pageDetail($slug)
    {
        $page = \Illuminate\Support\Facades\Cache::rememberForever("page_detail_{$slug}", function () use ($slug) {
            $record = Page::where('slug', $slug)->where('is_active', true)->first();
            return $record ? $record->toArray() : null;
        });

        if (!$page) {
            abort(404);
        }

        return response()->json($page);
    }

    /**
     * Get digital newspapers list from Halsen API (CORS bypass for Astro site)
     */
    public function newspapers()
    {
        try {
            // Get cached access token or fetch new one
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

            // Get cached books list for 1 hour to optimize performance and prevent rate limits
            $data = Cache::remember('ddag_newspapers_data', 3600, function () use ($token) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get('https://v1.halsen.id/api/v1/library/books', [
                    'limit' => 100,
                    'offset' => 0,
                ]);

                if ($response->failed()) {
                    throw new \Exception('Fetch books failed');
                }

                return $response->json();
            });

            return response()->json($data);

        } catch (\Exception $e) {
            // Clear caches on failure
            Cache::forget('ddag_access_token');
            Cache::forget('ddag_newspapers_data');
            
            try {
                // Retry once
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

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get('https://v1.halsen.id/api/v1/library/books', [
                    'limit' => 100,
                    'offset' => 0,
                ]);

                if ($response->failed()) {
                    throw new \Exception('Fetch books retry failed');
                }

                return response()->json($response->json());
            } catch (\Exception $ex) {
                return response()->json([
                    'success' => false,
                    'message' => $ex->getMessage()
                ], 500);
            }
        }
    }

    /**
     * Get published podcasts.
     */
    public function podcasts()
    {
        $podcasts = Podcast::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return response()->json($podcasts);
    }
}
