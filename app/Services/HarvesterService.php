<?php

namespace App\Services;

use App\Models\CatalogRecord;
use App\Models\FailedHarvest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HarvesterService
{
    /**
     * Fetch data from an endpoint with a 15-second timeout and logging on failure.
     */
    public static function fetch(string $sourceType, string $url, array $headers = []): ?string
    {
        try {
            $response = Http::withHeaders($headers)->timeout(15)->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            self::logFailure($sourceType, $url, "HTTP Status: " . $response->status() . " - " . $response->reason());
        } catch (\Exception $e) {
            self::logFailure($sourceType, $url, $e->getMessage());
        }

        return null;
    }

    /**
     * Log a failed harvest into the database and application log.
     */
    public static function logFailure(string $sourceType, string $url, string $errorMessage): void
    {
        Log::error("Failed to harvest {$sourceType} from {$url}. Error: {$errorMessage}");

        FailedHarvest::create([
            'source_type' => $sourceType,
            'endpoint' => $url,
            'error_message' => $errorMessage,
            'failed_at' => now(),
        ]);
    }

    /**
     * Normalize and upsert a record into catalog_records.
     */
    public static function upsertRecord(array $data): void
    {
        // Clean abstract/description formatting glitches (sisa tag HTML, entitas rusak)
        if (isset($data['abstract'])) {
            $data['abstract'] = strip_tags(html_entity_decode($data['abstract']));
            $data['abstract'] = preg_replace('/\\s+/', ' ', $data['abstract']); // Bersihkan enter/tab ganda
            $data['abstract'] = trim($data['abstract']);
        }

        // Clean title
        if (isset($data['title'])) {
            $data['title'] = strip_tags(html_entity_decode($data['title']));
            $data['title'] = trim($data['title']);
        }

        CatalogRecord::updateOrCreate(
            ['source_id' => $data['source_id']],
            [
                'source_type' => $data['source_type'],
                'title' => $data['title'],
                'creator' => $data['creator'] ?? null,
                'subject' => $data['subject'] ?? null,
                'publisher' => $data['publisher'] ?? null,
                'year' => $data['year'] ?? null,
                'abstract' => $data['abstract'] ?? null,
                'url_source' => $data['url_source'],
            ]
        );
    }
}
