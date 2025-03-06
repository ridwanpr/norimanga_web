<?php
namespace App\Scrapers;
use App\Scrapers\Base\BaseMangaScraper;
/**
 * Scraper for Kiryuu website
 */
class KiryuuScraper extends BaseMangaScraper
{
    public function extractBasicInfo(): array
    {
        $title = trim($this->xpath->evaluate('string(//h1[@class="entry-title"])'));
        $alternativeTitle = trim($this->xpath->evaluate('string(//div[@class="seriestualt"])'));

        return [
            'title' => $title,
            'slug' => $this->extractSlugFromUrl()
        ];
    }

    public function extractDetails(): array
    {
        // Get values from the infotable
        $status = $this->xpath->evaluate('string(//table[@class="infotable"]//tr[td[text()="Status"]]/td[2])');
        $type = $this->xpath->evaluate('string(//table[@class="infotable"]//tr[td[text()="Type"]]/td[2])');
        $releaseYear = $this->xpath->evaluate('string(//table[@class="infotable"]//tr[td[text()="Released"]]/td[2])');
        $author = $this->xpath->evaluate('string(//table[@class="infotable"]//tr[td[text()="Author"]]/td[2])');

        // Extract views - the format is different from what was expected
        $viewsText = $this->xpath->evaluate('string(//table[@class="infotable"]//tr[td[text()="Views"]]/td[2]/span)');
        $views = str_replace('K', '000', $viewsText);
        $views = preg_replace('/[^0-9]/', '', $views) ?: 0;

        // Get synopsis from the correct location
        $synopsis = $this->xpath->evaluate('string(//div[@class="entry-content entry-content-single"])');

        // Get cover image URL
        $coverImageUrl = $this->xpath->evaluate('string(//div[@class="thumb"]//img/@src)');

        return [
            'status' => $status ?: '-',
            'type' => $type ?: '-',
            'releaseYear' => $releaseYear ?: '-',
            'author' => $author ?: '-',
            'artist' => '-', // Not found in the provided HTML
            'views' => $views,
            'synopsis' => $synopsis ?: 'No synopsis available',
            'coverImageUrl' => $coverImageUrl
        ];
    }

    public function extractGenres(): array
    {
        $genreElements = $this->xpath->query('//div[@class="seriestugenre"]/a');
        $genres = [];

        foreach ($genreElements as $genre) {
            $genres[] = trim($genre->textContent);
        }

        return $genres;
    }
}
