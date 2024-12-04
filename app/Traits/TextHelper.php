<?php
namespace App\Traits;

trait TextHelper
{
    /**
     * Limit a string to a specified number of words.
     *
     * @param string $text
     * @param int $wordLimit
     * @return string
     */
    public function limitToWords(string $text, int $wordLimit = 50): string
    {
        // Split the string into an array of words
        $words = preg_split('/\s+/', trim($text));
    
        // Take only the first $wordLimit words
        $limitedWords = array_slice($words, 0, $wordLimit);
    
        // Join the words back into a string
        return implode(' ', $limitedWords);
    }
}
