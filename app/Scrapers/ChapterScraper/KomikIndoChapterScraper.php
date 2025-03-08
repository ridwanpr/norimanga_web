<?php

namespace App\Scrapers\ChapterScraper;

use DOMXPath;
use DOMDocument;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Scrapers\Base\BaseChapterScraper;
use App\Jobs\FetchChapterImageJob;

class KomikIndoChapterScraper extends BaseChapterScraper
{
    protected function getMangaUrl(Manga $manga): string
    {
        return "https://{$manga->source}/komik/{$manga->slug}/";
    }

    protected function getChapterUrl(MangaChapter $chapter, Manga $manga): string
    {
        return "https://{$manga->source}/{$chapter->slug}";
    }

    protected function extractChapterData(DOMXPath $xpath, \DOMNode $element, Manga $manga): ?array
    {
        $linkElement = $xpath->evaluate('.//span[@class="lchx"]/a', $element)->item(0);
        if (!$linkElement) {
            Log::error('Chapter extraction failed: No link element found.');
            return null;
        }

        $chapterTitle = trim(preg_replace('/\s+/', ' ', $linkElement->textContent));
        $chapterUrl = $linkElement->getAttribute('href');
        $chapterSlug = basename(rtrim($chapterUrl, '/'));

        return [
            'title' => $chapterTitle,
            'slug' => $chapterSlug,
            'chapter_number' => $chapterTitle
        ];
    }

    protected function extractImageUrls(string $html): array
    {
        $imageUrls = [];
        preg_match_all('/<img src=["\'](https?:\/\/(?!blogger\.googleusercontent\.com)(?![^"\']*wp-content\/uploads)[^"\']+)["\']/', $html, $matches);

        if (!empty($matches[1])) {
            $imageUrls = array_unique($matches[1]);
        }

        return $imageUrls;
    }

    public function fetchChapters(Manga $manga, string $bucket): array
    {
        $url = $this->getMangaUrl($manga);
        Log::info("Fetching chapters for manga: {$manga->title} from {$url}");

        $response = Http::withHeaders([
            'User-Agent' => $this->getRandomUserAgent()
        ])->get($url);

        if (!$response->successful()) {
            Log::error("Failed to fetch chapters for: {$manga->title}");
            return [];
        }

        $html = $response->body();
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new DOMXPath($dom);

        $chapterElements = $xpath->query('//div[@id="chapter_list"]//li');

        if (!$chapterElements || $chapterElements->length === 0) {
            Log::warning("No chapters found for manga: {$manga->title}");
            return [];
        }

        $chapters = [];
        foreach ($chapterElements as $element) {
            $chapterData = $this->extractChapterData($xpath, $element, $manga);

            if (empty($chapterData) || empty($chapterData['chapter_number'])) {
                continue;
            }

            $chapter = MangaChapter::updateOrCreate(
                [
                    'manga_id' => $manga->id,
                    'chapter_number' => $chapterData['chapter_number']
                ],
                [
                    'title' => $chapterData['title'],
                    'slug' => $chapterData['slug'],
                    'image' => json_encode([]),
                    'bucket' => $bucket,
                ]
            );

            $chapters[] = $chapter;

            dispatch(new FetchChapterImageJob($chapter, $manga, $bucket))
                ->delay(now()->addSeconds(random_int(5, 50)));
        }

        return $chapters;
    }
}
