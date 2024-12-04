<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\GuardianNewsService;
use App\Services\NewsAPIService;
use App\Services\NewsAggregatorService;
use App\Services\ArticleScraperService;


class TempFetcher extends Controller
{
    public function fetcher(Request $request) {

        $articleScraper = new ArticleScraperService();

        
        $newsAggregator = new NewsAggregatorService([
            new GuardianNewsService(),
            new NewsAPIService($articleScraper),
        ]);

        try {
              // Fetch news from each provider
              $filters = [
                'query' => $request->input('query', ''),
                'from_date' => $request->input('from_date', now()->subDays(7)->toDateString()),
                'to_date' => $request->input('to_date', now()->toDateString()),
            ];

            $news = [];
            foreach ($newsAggregator->getProviders() as $provider) {
                $news = array_merge($news, $provider->fetchNews($filters));
            }

            return response()->json([
                'message' => 'News fetched successfully.',
                'data' => $news,
            ], 200);
        

            // $this->info('News articles fetched and stored successfully.');
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed.',
                'error' => $e->getMessage(),
            ], 400);


            $this->error('Failed to fetch news: ' . $e->getMessage());
        }
    }
}
