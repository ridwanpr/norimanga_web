<?php

namespace App\Scrapers;

use App\Scrapers\Base\BaseMangaScraper;

/**
 * Scraper for WestManga website
 */
class WestMangaScraper extends BaseMangaScraper
{
  public function extractBasicInfo(): array
  {
    $title = trim($this->xpath->evaluate('string(//h1[@class="entry-title"])'));

    return [
      'title' => $title,
      'slug' => $this->extractSlugFromUrl()
    ];
  }

  public function extractDetails(): array
  {
    return [
      'status' => $this->getTableValue('Status') ?: '-',
      'type' => $this->getTableValue('Type') ?: '-',
      'releaseYear' => $this->extractYearFromPostedDate() ?: '-',
      'author' => $this->getTableValue('Author') ?: '-',
      'artist' => $this->getTableValue('Author') ?: '-', // Use same as author if not stated
      'views' => 0, // WestManga doesn't show views
      'synopsis' => $this->xpath->evaluate('string(//div[contains(@class, "entry-content")]//p)') ?: 'No synopsis available',
      'coverImageUrl' => $this->xpath->evaluate('string(//div[contains(@class, "thumb")]//img/@src)')
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

  /**
   * Helper method to extract values from WestManga's table structure
   */
  private function getTableValue(string $label): ?string
  {
    $query = "//div[contains(@class, 'seriestucontr')]//table//tr[td[text()='{$label}']]/td[2]";
    $result = $this->xpath->evaluate("string({$query})");
    return !empty($result) ? trim($result) : null;
  }

  /**
   * Extract year from WestManga's posted date
   */
  private function extractYearFromPostedDate(): ?string
  {
    $postedDate = $this->getTableValue('Posted On');
    if (!empty($postedDate)) {
      // Extract year from date string like "May 19, 2019"
      if (preg_match('/(\d{4})/', $postedDate, $matches)) {
        return $matches[1];
      }
    }
    return null;
  }
}
