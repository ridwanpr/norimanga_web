<?php

namespace App\Scrapers;

use App\Scrapers\Base\BaseMangaScraper;

/**
 * Scraper for Apkomik website
 */
class ApkomikScraper extends BaseMangaScraper
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
      'status' => $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Status")]/i)') ?: '-',
      'type' => $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Type")]/a)') ?: '-',
      'releaseYear' => $this->xpath->evaluate('string(//div[contains(@class, "fmed")][b[text()="Released"]]/span)') ?: '-',
      'author' => $this->xpath->evaluate('string(//div[contains(@class, "fmed")][b[text()="Author"]]/span)') ?: '-',
      'artist' => $this->xpath->evaluate('string(//div[contains(@class, "fmed")][b[text()="Artist"]]/span)') ?: '-',
      'views' => 0, // Apkomik doesn't show views
      'synopsis' => $this->xpath->evaluate('string(//div[@class="entry-content entry-content-single"])') ?: 'No synopsis available',
      'coverImageUrl' => $this->xpath->evaluate('string(//div[contains(@class, "thumb")]//img/@src)')
    ];
  }

  public function extractGenres(): array
  {
    $genreElements = $this->xpath->query('//div[@class="wd-full"]/span[@class="mgen"]/a');
    $genres = [];

    foreach ($genreElements as $genre) {
      $genres[] = trim($genre->textContent);
    }

    return $genres;
  }
}
