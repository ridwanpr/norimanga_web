<?php

namespace App\Factories;

use App\Models\Manga;
use App\Scrapers\ChapterScraper\ApkomikChapterScraper;
use App\Scrapers\ChapterScraper\ComicasoChapterScraper;
use App\Scrapers\ChapterScraper\ManhwaIDChapterScraper;
use App\Scrapers\ChapterScraper\WestMangaChapterScraper;
use App\Scrapers\ChapterScraper\ManhwaindoChapterScraper;
use App\Scrapers\Interfaces\MangaChapterScraperInterface;

class ChapterScraperFactory
{
  /**
   * Get the appropriate scraper for the manga source
   *
   * @param Manga $manga
   * @return MangaChapterScraperInterface
   * @throws \InvalidArgumentException
   */
  public static function getScraper(Manga $manga): MangaChapterScraperInterface
  {
    switch ($manga->source) {
      case 'manhwaindo.one':
        return new ManhwaindoChapterScraper();
      case 'westmanga.fun':
        return new WestMangaChapterScraper();
      case 'comicaso.id':
        return new ComicasoChapterScraper();
      case 'manhwaid.id':
        return new ManhwaIDChapterScraper();
      case 'apkomik.cc':
        return new ApkomikChapterScraper();
      default:
        throw new \InvalidArgumentException("Unsupported manga source: {$manga->source}");
    }
  }
}
