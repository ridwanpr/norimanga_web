<?php

namespace App\Scrapers;

use App\Scrapers\Base\BaseMangaScraper;

/**
 * Scraper for Manhwaindo website
 */
class ManhwaindoScraper extends BaseMangaScraper
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
    $viewsText = $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Views")]/i)');
    $views = preg_replace('/[^0-9]/', '', $viewsText) ?: 0;

    return [
      'status' => $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Status")]/i)') ?: '-',
      'type' => $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Type")]/a)') ?: '-',
      'releaseYear' => $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Released")]/i)') ?: '-',
      'author' => $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Author")]/i)') ?: '-',
      'artist' => $this->xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Artist")]/i)') ?: '-',
      'views' => $views,
      'synopsis' => $this->xpath->evaluate('string(//div[@class="entry-content entry-content-single"]/p)') ?: 'No synopsis available',
      'coverImageUrl' => $this->xpath->evaluate('string(//div[@class="thumb"]//img/@src)')
    ];
  }

  public function extractGenres(): array
  {
    $genreElements = $this->xpath->query('//span[@class="mgen"]/a');
    $genres = [];

    foreach ($genreElements as $genre) {
      $genres[] = trim($genre->textContent);
    }

    return $genres;
  }
}
