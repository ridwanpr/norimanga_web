<?php

namespace App\Factories;

use App\Models\Manga;
use App\Scrapers\ChapterScraper\KiryuuChapterScraper;
use App\Scrapers\ChapterScraper\ApkomikChapterScraper;
use App\Scrapers\ChapterScraper\ComicasoChapterScraper;
use App\Scrapers\ChapterScraper\KomikIndoChapterScraper;
use App\Scrapers\ChapterScraper\MaidMangaChapterScraper;
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
            case 'comicaso.xyz':
                return new ComicasoChapterScraper();
            case 'manhwaid.id':
                return new ManhwaIDChapterScraper();
            case 'apkomik.cc':
                return new ApkomikChapterScraper();
            case 'kiryuu01.com':
                return new KiryuuChapterScraper();
            case 'komikindo2.com':
                return new KomikIndoChapterScraper();
            case 'www.maid.my.id':
                return new MaidMangaChapterScraper();
            case 'komiksin.id':
                return new WestMangaChapterScraper();
            default:
                throw new \InvalidArgumentException("Unsupported manga source: {$manga->source}");
        }
    }
}
