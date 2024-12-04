<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GuardianNewsService;
use App\Services\NewsAPIService;
use App\Services\NewsAggregatorService;
use App\Services\ArticleScraperService;

class FetchNews extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news articles from external APIs';

    public function handle()
    {
        
        $articleScraper = new ArticleScraperService();

        
        $newsAggregator = new NewsAggregatorService([
            new GuardianNewsService(),
            new NewsAPIService($articleScraper),
        ]);

        try {
              // Fetch news from each provider
              $newsAggregator->fetchAndStoreNews([
                'query' => '',
                'from_date' => now()->subDays(7)->toDateString(),
                'to_date' => now()->toDateString(),
                ]);

           
        

            $this->info('News articles fetched and stored successfully.');
        } catch (\Exception $e) {

          

            $this->error('Failed to fetch news: ' . $e->getMessage());
        }
    }
}

