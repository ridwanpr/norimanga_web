<?php

namespace App\Factories;

use DOMXPath;
use App\Scrapers\WestMangaScraper;
use App\Scrapers\ApkomikScraper;
use App\Scrapers\ManhwaindoScraper;
use App\Scrapers\Interfaces\MangaScraperInterface;

class MangaScraperFactory
{
  /**
   * Create an appropriate scraper based on the URL
   */
  public static function create(string $url, DOMXPath $xpath): MangaScraperInterface
  {
    $domain = parse_url($url, PHP_URL_HOST);

    if (str_contains($domain, 'westmanga')) {
      return new WestMangaScraper($xpath, $url);
    } else if (str_contains($domain, 'apkomik')) {
      return new ApkomikScraper($xpath, $url);
    } else {
      // Default to ManhwaindoScraper
      return new ManhwaindoScraper($xpath, $url);
    }
  }
}
