<?php

namespace App\Factories;

use App\Scrapers\KiryuuScraper;
use DOMXPath;
use App\Scrapers\WestMangaScraper;
use App\Scrapers\ApkomikScraper;
use App\Scrapers\ManhwaindoScraper;
use App\Scrapers\Interfaces\MangaScraperInterface;
use App\Scrapers\ManhwaIDScraper;

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
    } else if (str_contains($domain, 'manhwaid')) {
      return new ManhwaIDScraper($xpath, $url);
    } else if (str_contains($domain, 'comicaso')) {
      return new WestMangaScraper($xpath, $url);
    } else if (str_contains($domain, 'kiryuu01')) {
        return new KiryuuScraper($xpath, $url);
    } else {
      return new ManhwaindoScraper($xpath, $url);
    }
  }
}
