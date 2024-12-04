<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;


class ArticleScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10.0, // Set a timeout for the request
        ]);
    }

    /**
     * Scrape the full article content from a URL.
     *
     * @param string $url
     * @return string
     */
    public function scrapeContent(string $url): string
    {
        try {
            // Fetch the HTML content
            $response = $this->client->get($url);
            $html = $response->getBody()->getContents();

            // Parse the HTML using Symfony DomCrawler
            $crawler = new Crawler($html);

            // Extract meaningful content
            // You can adjust the selectors based on the structure of the website
            $content = $crawler->filter('article')->text();

            return $content;
        } catch (\Exception $e) {
            // Log the error and return a placeholder
            \Log::error('Failed to scrape content', ['url' => $url, 'error' => $e->getMessage()]);
            return 'Failed to retrieve full content.';
        }
    }


  
}
