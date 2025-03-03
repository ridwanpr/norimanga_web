<?php

namespace App\Scrapers\ChapterScraper;

use DOMXPath;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Support\Facades\Log;
use App\Scrapers\Base\BaseChapterScraper;

class ComicasoChapterScraper extends BaseChapterScraper
{
    /**
     * Get the manga detail URL
     *
     * @param Manga $manga
     * @return string
     */
    protected function getMangaUrl(Manga $manga): string
    {
        return "https://{$manga->source}/manga/{$manga->slug}/";
    }

    /**
     * Get the chapter detail URL
     *
     * @param MangaChapter $chapter
     * @param Manga $manga
     * @return string
     */
    protected function getChapterUrl(MangaChapter $chapter, Manga $manga): string
    {
        return "https://{$manga->source}/{$chapter->slug}";
    }

    /**
     * Extract chapter data from DOM elements
     *
     * @param DOMXPath $xpath
     * @param \DOMNode $element
     * @param Manga $manga
     * @return array|null
     */
    protected function extractChapterData(DOMXPath $xpath, \DOMNode $element, Manga $manga): ?array
    {
        $linkElement = $xpath->evaluate('.//div[@class="eph-num"]/a', $element)->item(0);
        if (!$linkElement) {
            return null;
        }

        $chapterTitle = $xpath->evaluate('string(.//span[@class="chapternum"])', $element);
        $chapterUrl = $linkElement->getAttribute('href');
        $chapterSlug = basename(rtrim($chapterUrl, '/'));
        $chapterNumber = $xpath->evaluate('string(.//span[@class="chapternum"])', $element);

        if (empty($chapterNumber)) {
            return null;
        }

        return [
            'title' => $chapterTitle,
            'slug' => $chapterSlug,
            'chapter_number' => $chapterNumber
        ];
    }

    /**
     * Extract image URLs from chapter page
     *
     * @param string $html
     * @return array
     */
    function extractImageUrls(string $html): array
    {
        // Find all script tags with base64-encoded content
        preg_match_all('/src="data:text\/javascript;base64,([^"]+)"/', $html, $matches);

        if (empty($matches[1])) {
            return [];
        }

        // Loop through all base64-encoded scripts
        foreach ($matches[1] as $base64Script) {
            // Decode the base64 script
            $decodedScript = base64_decode($base64Script);

            // Look for ts_reader.run
            if (strpos($decodedScript, 'ts_reader.run') !== false) {
                // Extract the JSON-like data from ts_reader.run()
                preg_match('/ts_reader\.run\((.*?)\)/', $decodedScript, $scriptMatches);

                if (!empty($scriptMatches[1])) {
                    // Try to decode as-is first
                    $jsonData = json_decode($scriptMatches[1], true);

                    // If json_decode fails, we need to fix any JavaScript-specific syntax
                    if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
                        // Extract image URLs directly using regex as a fallback
                        preg_match_all('/"images":\s*\[(.*?)\]/s', $scriptMatches[1], $imgMatches);

                        if (!empty($imgMatches[1][0])) {
                            // Extract all URLs from the images array
                            preg_match_all('/"(https:\\\\?\/\\\\?\/[^"]+)"/', $imgMatches[1][0], $urlMatches);

                            if (!empty($urlMatches[1])) {
                                $decodedUrls = [];
                                foreach ($urlMatches[1] as $url) {
                                    // Clean up the escaped slashes
                                    $decodedUrls[] = str_replace(['\\/', '\\\\/', '\\\\\\\/'], '/', $url);
                                }
                                return $decodedUrls;
                            }
                        }
                    } else {
                        // JSON decode succeeded, extract URLs normally
                        if (isset($jsonData['sources'][0]['images'])) {
                            $urls = $jsonData['sources'][0]['images'];
                            $decodedUrls = [];

                            foreach ($urls as $url) {
                                $decodedUrls[] = str_replace('\\/', '/', $url);
                            }
                            return $decodedUrls;
                        }
                    }
                }
            }
        }

        return [];
    }
}
