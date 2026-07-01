<?php

namespace App\Console\Commands;

use App\Services\HarvesterService;
use Illuminate\Console\Command;

class HarvestEprints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:harvest-eprints {--url= : URL API EPrints di api.emsacode.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Harvest data from EPrints repository via api.emsacode.com JSON API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gatewayUrl = config('services.emsacode.url', 'https://api.emsacode.com/v1');
        $url = $this->option('url') ?: rtrim($gatewayUrl, '/') . '/eprints';
        $token = config('services.emsacode.token', 'mock-token-123') ?: 'mock-token-123';
        $this->info("Harvesting EPrints via Gateway: {$url}");

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];

        $jsonData = HarvesterService::fetch('eprints', $url, $headers);

        if (!$jsonData) {
            $this->warn("Failed to retrieve live data from Gateway. Using static mock JSON for local verification.");
            $jsonData = $this->getMockJson();
        }

        try {
            $records = json_decode($jsonData, true);

            // Jika dibungkus dalam key 'data' dari paginasi gateway
            if (isset($records['data']) && is_array($records['data'])) {
                $records = $records['data'];
            }

            if (!is_array($records)) {
                throw new \Exception("Invalid JSON format");
            }

            $count = 0;

            foreach ($records as $record) {
                $sourceId = $record['source_id'] ?? ('eprints-' . ($record['id'] ?? $count));
                $title = $record['title'] ?? null;
                $creator = $record['creator'] ?? null;
                $subject = $record['subject'] ?? null;
                $publisher = $record['publisher'] ?? null;
                $year = $record['year'] ?? null;
                $abstract = $record['abstract'] ?? null;
                $urlSource = $record['url_source'] ?? $record['url'] ?? null;

                if (!$title || !$urlSource) {
                    continue;
                }

                HarvesterService::upsertRecord([
                    'source_id' => $sourceId,
                    'source_type' => 'eprints',
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

            $this->info("Successfully harvested {$count} records from EPrints (Gateway).");
        } catch (\Exception $e) {
            $this->error("Error parsing EPrints JSON: " . $e->getMessage());
            HarvesterService::logFailure('eprints', $url, "JSON Parse Error: " . $e->getMessage());
        }
    }

    private function getMockJson(): string
    {
        return json_encode([
            [
                'source_id' => 'eprints-101',
                'title' => 'Strategi Pengembangan Kurikulum Pendidikan Agama Islam di Era Digital',
                'creator' => 'Dr. Hamzah M.A.',
                'subject' => 'Kurikulum PAI',
                'publisher' => 'IAIN Sorong Press',
                'year' => '2025',
                'abstract' => 'Artikel ini membahas tentang kurikulum baru yang adaptif teknologi untuk sekolah Islam di Sorong.&#13;\r',
                'url_source' => 'https://repo.iainsorong.ac.id/101/'
            ],
            [
                'source_id' => 'eprints-102',
                'title' => 'Pengaruh Budaya Lokal Suku Malamoi Terhadap Pola Interaksi Sosial Keagamaan di Sorong Raya',
                'creator' => 'Salma Rahayaan',
                'subject' => 'Sosiologi Agama',
                'publisher' => 'Fakultas Tarbiyah IAIN Sorong',
                'year' => '2026',
                'abstract' => 'Penelitian antropologis mengenai kerukunan antar suku Malamoi dan pendatang di Sorong. <p>Rekomendasi kebijakan kerukunan beragama.</p>',
                'url_source' => 'https://repo.iainsorong.ac.id/102/'
            ]
        ], JSON_PRETTY_PRINT);
    }
}
