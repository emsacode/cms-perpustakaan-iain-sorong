<?php

namespace App\Console\Commands;

use App\Services\HarvesterService;
use Illuminate\Console\Command;

class HarvestSlims extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:harvest-slims {--url= : URL API SLiMS di api.emsacode.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Harvest data from SLiMS Library Catalog via api.emsacode.com JSON API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gatewayUrl = config('services.emsacode.url', 'https://api.emsacode.com/v1');
        $url = $this->option('url') ?: rtrim($gatewayUrl, '/') . '/slims';
        $token = config('services.emsacode.token', 'mock-token-123') ?: 'mock-token-123';
        $this->info("Harvesting SLiMS via Gateway: {$url}");

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];

        $jsonData = HarvesterService::fetch('slims', $url, $headers);

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

            $this->info("Successfully harvested {$count} records from SLiMS (Gateway).");
        } catch (\Exception $e) {
            $this->error("Error parsing SLiMS JSON: " . $e->getMessage());
            HarvesterService::logFailure('slims', $url, "JSON Parse Error: " . $e->getMessage());
        }
    }

    private function getMockJson(): string
    {
        return json_encode([
            [
                'id' => 201,
                'source_id' => 'slims-201',
                'title' => 'Pengantar Hukum Islam di Indonesia',
                'author' => 'Prof. Dr. H. Zainuddin Ali, M.A.',
                'isbn' => '9789790070448',
                'publisher' => 'Sinar Grafika',
                'publish_year' => '2024',
                'subject' => 'Hukum Islam',
                'abstract' => 'Buku ini menguraikan hukum Islam secara komparatif dengan tata hukum nasional di Indonesia.',
                'url' => 'https://opac.iainsorong.ac.id/index.php?p=show_detail&id=201'
            ],
            [
                'id' => 202,
                'source_id' => 'slims-202',
                'title' => 'Kamus Lengkap Bahasa Arab - Indonesia',
                'author' => 'Mahmud Yunus',
                'isbn' => '9798442031',
                'publisher' => 'Hidakarya Agung',
                'publish_year' => '2020',
                'subject' => 'Bahasa Arab',
                'abstract' => 'Kamus referensi utama pembelajaran bahasa Arab bagi santri dan mahasiswa di Indonesia.',
                'url' => 'https://opac.iainsorong.ac.id/index.php?p=show_detail&id=202'
            ]
        ], JSON_PRETTY_PRINT);
    }
}
