<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

use App\Traits\TextHelper;

use App\Services\ArticleScraperService;

use Carbon\Carbon;



class NewsAPIService implements NewsProviderInterface
{


    use TextHelper;


    protected string $baseUrl;
    protected string $apiKey;
    protected $scraper;

    public function __construct(ArticleScraperService $scraper)
    {
        $this->baseUrl = config('services.newsapi.base_url');
        $this->apiKey = config('services.newsapi.api_key');
        $this->scraper = $scraper;
    }

    public function fetchNews(array $filters): array
    {
        $response = Http::get("{$this->baseUrl}/everything", [
            'apiKey' => $this->apiKey,
            'q' => $filters['query'] ?? '',
            'from' => $filters['from_date'] ?? '',
            'to' => $filters['to_date'] ?? '',
            'domains' => 'bbc.co.uk,nytimes.com',
            // 'pageSize' => 2,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch news from NewsAPI' . $response);
        }

        return array_map(function ($item) {
            // return $item;

            $scrappedContent = $this->scraper->scrapeContent($item['url']);
            // if($scrappedContent != "Failed to retrieve full content."){

                return [
                    'title' => $item['title'],
                    'description' => $scrappedContent,
                    'url' => $item['url'],
                    'url_to_image' => $item['urlToImage'],
                    'source' => 'NewsAPI',
                    'summary' => $this->limitToWords($scrappedContent),
                    'author' => $item['author'],
                    'published_at' => Carbon::parse($item['publishedAt'])->toDateTimeString(),
                    'category' => 'General',
                ];
            // }
        }, $response->json()['articles'] ?? []);
    }
}
