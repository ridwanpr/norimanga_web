<?php

namespace App\Scrapers\Interfaces;

use App\Models\Manga;
use App\Models\MangaChapter;

interface MangaChapterScraperInterface
{
  /**
   * Fetch chapters from the source website
   * 
   * @param Manga $manga The manga to fetch chapters for
   * @param string $bucket The storage bucket to use
   * @return array Array of fetched chapters data
   */
  public function fetchChapters(Manga $manga, string $bucket): array;

  /**
   * Fetch images for a specific chapter
   * 
   * @param MangaChapter $chapter The chapter to fetch images for
   * @param Manga $manga The manga the chapter belongs to
   * @param string $bucket The storage bucket to use
   * @return array Array of image URLs
   */
  public function fetchChapterImages(MangaChapter $chapter, Manga $manga, string $bucket): array;
}
