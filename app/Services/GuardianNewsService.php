<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

use App\Traits\TextHelper;

use Carbon\Carbon;

class GuardianNewsService implements NewsProviderInterface
{

    use TextHelper;

    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.guardian.base_url');
        $this->apiKey = config('services.guardian.api_key');
    }


    public function fetchNews(array $filters): array
    {
        $response = Http::get("{$this->baseUrl}/search", [
            'api-key' => $this->apiKey,
            'q' => $filters['query'] ?? '',
            'from-date' => $filters['from_date'] ?? '',
            'to-date' => $filters['to_date'] ?? '',
            'format'=> 'json',
            'show-tags'=> 'contributor',
            'show-fields'=>'headline,thumbnail,short-url',
            'show-blocks'=>'all',
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch news from The Guardian');
        }

        return array_map(function ($item) {
            // return $item;
            return [
                'title' => $item['webTitle'],
                'summary' => $this->limitToWords($item['blocks']['body'][0]['bodyTextSummary']),
                'url_to_image' => $item['fields']['thumbnail'],
                'author' => $item['tags'][0]['firstName'] . " " . $item['tags'][0]['lastName'],
                'description' => $item['blocks']['body'][0]['bodyHtml'],
                'url' => $item['webUrl'],
                'source' => 'The Guardian',
                'published_at' => Carbon::parse($item['webPublicationDate'])->toDateTimeString(),
                'category' => $item['pillarName'] ?? "General",
            ];
        }, $response->json()['response']['results'] ?? []);
    }
}
