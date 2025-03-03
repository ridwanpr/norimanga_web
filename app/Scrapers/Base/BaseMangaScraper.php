<?php

namespace App\Scrapers\Base;

use DOMXPath;
use App\Scrapers\Interfaces\MangaScraperInterface;

/**
 * Base scraper with common functionality
 */
abstract class BaseMangaScraper implements MangaScraperInterface
{
  protected $xpath;
  protected $url;

  public function __construct(DOMXPath $xpath, string $url)
  {
    $this->xpath = $xpath;
    $this->url = $url;
  }

  /**
   * Extract slug from URL
   */
  protected function extractSlugFromUrl(): string
  {
    $path = parse_url($this->url, PHP_URL_PATH);
    $path = rtrim($path, '/');
    $pathSegments = explode('/', $path);
    return end($pathSegments);
  }
}
