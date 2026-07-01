<?php

namespace App\Jobs;

use App\Services\HarvesterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HarvestSlimsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [300, 900, 1800]; // 5 min, 15 min, 30 min

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $gatewayUrl = config('services.emsacode.url', 'https://api.emsacode.com/v1');
        $url = rtrim($gatewayUrl, '/') . '/slims';
        $token = config('services.emsacode.token', 'mock-token-123') ?: 'mock-token-123';

        Log::info("Queue Job starting: Harvesting SLiMS via Gateway: {$url}");

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->timeout(15)->get($url);

        if (!$response->successful()) {
            throw new \Exception("Gateway responded with status code: " . $response->status());
        }

        $records = $response->json();

        // If wrapped in paginated 'data' key
        if (isset($records['data']) && is_array($records['data'])) {
            $records = $records['data'];
        }

        if (!is_array($records)) {
            throw new \Exception("Invalid JSON format from gateway");
        }

        $count = 0;
        foreach ($records as $record) {
            $sourceId = $record['source_id'] ?? ('slims-' . ($record['id'] ?? $count));
            
            $title = $record['title'] ?? null;
            $creator = $record['author'] ?? $record['creator'] ?? null;
            $subject = $record['subject'] ?? null;
            $publisher = $record['publisher'] ?? null;
            $year = $record['publish_year'] ?? $record['year'] ?? null;
            $abstract = $record['abstract'] ?? $record['description'] ?? null;
            $urlSource = $record['url'] ?? $record['url_source'] ?? 'https://opac.iainsorong.ac.id';

            if (!$title || !$urlSource) {
                continue;
            }

            HarvesterService::upsertRecord([
                'source_id' => $sourceId,
                'source_type' => 'slims',
                'title' => $title,
                'creator' => $creator,
                'subject' => $subject,
                'publisher' => $publisher,
                'year' => $year,
                'abstract' => $abstract,
                'url_source' => $urlSource,
            ]);

            $count++;
        }

        Log::info("Queue Job success: Harvested {$count} SLiMS records.");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $gatewayUrl = config('services.emsacode.url', 'https://api.emsacode.com/v1');
        $url = rtrim($gatewayUrl, '/') . '/slims';

        HarvesterService::logFailure('slims', $url, "Queue Job Failed after all retries. Reason: " . $exception->getMessage());
    }
}
