<?php

namespace App\Scrapers;

use App\Scrapers\Base\BaseMangaScraper;
use Illuminate\Support\Facades\Log;

class ManhwaIDScraper extends BaseMangaScraper
{
  public function extractBasicInfo(): array
  {
    $title = trim($this->xpath->evaluate('string(//h1[@class="entry-title"])'));

    Log::info('Extracted Basic Info', ['title' => $title]);

    return [
      'title' => $title,
      'slug' => $this->extractSlugFromUrl()
    ];
  }

  public function extractDetails(): array
  {
    $details = [
      'status' => $this->getTextContent('//div[contains(@class, "imptdt")][contains(text(), "Status")]/i') ?: '-',
      'type' => $this->getTextContent('//div[contains(@class, "imptdt")][contains(text(), "Tipe")]/a') ?: '-',
      'release_year' => $this->getTextContent('//div[contains(@class, "fmed")][b[text()="Dirilis"]]/span') ?: '-',
      'author' => $this->getTextContent('//div[contains(@class, "fmed")][b[text()="Penulis"]]/span') ?: '-',
      'artist' => $this->getTextContent('//div[contains(@class, "fmed")][b[text()="Artist"]]/span') ?: '-',
      'views' => 0,
      'synopsis' => $this->xpath->evaluate('string(//div[@class="entry-content entry-content-single"])') ?: 'No synopsis available',
      'coverImageUrl' => $this->xpath->evaluate('string(//div[@class="thumb"]//img/@src)') ?: '',
    ];

    Log::info('Extracted Details', $details);

    return $details;
  }

  public function extractGenres(): array
  {
    $genreElements = $this->xpath->query('//div[@class="wd-full"]/span[@class="mgen"]/a');
    $genres = [];

    foreach ($genreElements as $genre) {
      $genres[] = trim($genre->textContent);
    }

    Log::info('Extracted Genres', ['genres' => $genres]);

    return $genres;
  }

  private function getTextContent(string $xpathQuery): ?string
  {
    $result = $this->xpath->evaluate("string({$xpathQuery})");
    return !empty($result) ? trim($result) : null;
  }
}
