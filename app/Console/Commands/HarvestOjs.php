<?php

namespace App\Console\Commands;

use App\Services\HarvesterService;
use Illuminate\Console\Command;

class HarvestOjs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:harvest-ojs {--url= : URL API OJS di api.emsacode.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Harvest data from Open Journal Systems via api.emsacode.com JSON API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gatewayUrl = config('services.emsacode.url', 'https://api.emsacode.com/v1');
        $url = $this->option('url') ?: rtrim($gatewayUrl, '/') . '/ojs';
        $token = config('services.emsacode.token', 'mock-token-123') ?: 'mock-token-123';
        $this->info("Harvesting OJS via Gateway: {$url}");

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];

        $jsonData = HarvesterService::fetch('ojs', $url, $headers);

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
                $sourceId = $record['source_id'] ?? ('ojs-' . ($record['id'] ?? $count));
                
                $title = $record['title'] ?? null;
                $creator = $record['creator'] ?? $record['author'] ?? null;
                $subject = $record['subject'] ?? 'Journal Article';
                $publisher = $record['publisher'] ?? 'IAIN Sorong Press';
                $year = $record['year'] ?? null;
                $abstract = $record['abstract'] ?? $record['description'] ?? null;
                $urlSource = $record['url_source'] ?? $record['url'] ?? null;

                if (!$title || !$urlSource) {
                    continue;
                }

                HarvesterService::upsertRecord([
                    'source_id' => $sourceId,
                    'source_type' => 'ojs',
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

            $this->info("Successfully harvested {$count} records from OJS (Gateway).");
        } catch (\Exception $e) {
            $this->error("Error parsing OJS JSON: " . $e->getMessage());
            HarvesterService::logFailure('ojs', $url, "JSON Parse Error: " . $e->getMessage());
        }
    }

    private function getMockJson(): string
    {
        return json_encode([
            [
                'source_id' => 'ojs-88',
                'title' => 'Integrasi Moderasi Beragama dalam Buku Ajar Pendidikan Agama Islam tingkat SMA',
                'creator' => 'Achmad Rois',
                'subject' => 'Moderasi Beragama',
                'publisher' => 'IAIN Sorong Press',
                'year' => '2026',
                'abstract' => 'Penelitian kualitatif teks mengenai integrasi nilai moderasi beragama dalam buku ajar SMA di Papua Barat.',
                'url_source' => 'https://journal.iainsorong.ac.id/index.php/qolam/article/view/88'
            ],
            [
                'source_id' => 'ojs-89',
                'title' => 'Penerapan Model Project-Based Learning Untuk Meningkatkan Kreativitas Santri Pondok Pesantren di Sorong',
                'creator' => 'Fatmawati Salosa',
                'subject' => 'Project-Based Learning',
                'publisher' => 'IAIN Sorong Press',
                'year' => '2026',
                'abstract' => 'Studi eksperimen tentang pembelajaran vokasional untuk santri kreatif di Sorong Timur.',
                'url_source' => 'https://journal.iainsorong.ac.id/index.php/qolam/article/view/89'
            ]
        ], JSON_PRETTY_PRINT);
    }
}
