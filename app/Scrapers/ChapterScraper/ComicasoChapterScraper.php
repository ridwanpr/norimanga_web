<?php

namespace App\Scrapers\ChapterScraper;

use DOMXPath;
use App\Models\Manga;
use App\Models\MangaChapter;
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
  protected function extractImageUrls(string $html): array
  {
      // Look for base64 encoded scripts
      preg_match_all('/src="data:text\/javascript;base64,([^"]+)"/', $html, $matches);

      if (empty($matches[1])) {
          return [];
      }

      // Check each base64 script
      foreach ($matches[1] as $base64Script) {
          $decodedScript = base64_decode($base64Script);

          // Look for ts_reader.run in the decoded script
          if (strpos($decodedScript, 'ts_reader.run') !== false) {
              preg_match('/ts_reader\.run\((.*?)\);/', $decodedScript, $scriptMatches);

              if (!empty($scriptMatches[1])) {
                  $jsonData = json_decode($scriptMatches[1], true);

                  if ($jsonData && isset($jsonData['sources'][0]['images'])) {
                      return $jsonData['sources'][0]['images'];
                  }
              }
          }
      }

      return [];
  }
}
