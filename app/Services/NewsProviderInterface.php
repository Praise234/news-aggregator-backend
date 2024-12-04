<?php

namespace App\Services;

interface NewsProviderInterface
{
    /**
     * Fetch news articles based on the provided filters.
     *
     * @param array $filters
     * @return array
     */
    public function fetchNews(array $filters): array;
    
}