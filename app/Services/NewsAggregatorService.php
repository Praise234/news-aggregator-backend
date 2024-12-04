<?php

namespace App\Services;

use App\Models\Articles;

use Illuminate\Http\JsonResponse;

class NewsAggregatorService
{
    protected array $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }


    public function fetchAndStoreNews(array $filters): void
    {
        try {
            foreach ($this->providers as $provider) {
                $newsItems = $provider->fetchNews($filters);
                
                // return $newsItems;
                
                // return response()->json(['message' => $newsItems], 200);
                
                foreach ($newsItems as $news) {
                    Articles::updateOrCreate(
                        ['url' => $news['url']], // Ensure no duplicates
                        $news
                    );
                }
                // Log the success
                \Log::info('Fetch Successful1');
            }
        } catch (\Exception $e) {
            \Log::error('Fetching Error', ['error' => $e->getMessage()]);
        }
    }
}
