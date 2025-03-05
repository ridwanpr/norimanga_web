<?php

namespace App\Scrapers\Base;

use DOMXPath;
use DOMDocument;
use App\Models\Manga;
use App\Models\MangaChapter;
use App\Services\BucketManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Scrapers\Interfaces\MangaChapterScraperInterface;
use App\Jobs\FetchChapterImageJob;

abstract class BaseChapterScraper implements MangaChapterScraperInterface
{
    protected $bucketManager;

    public function __construct()
    {
        $this->bucketManager = new BucketManager();
    }

    /**
     * Get the manga detail URL for the specific source
     *
     * @param Manga $manga
     * @return string
     */
    abstract protected function getMangaUrl(Manga $manga): string;

    /**
     * Get the chapter detail URL for the specific source
     *
     * @param MangaChapter $chapter
     * @param Manga $manga
     * @return string
     */
    abstract protected function getChapterUrl(MangaChapter $chapter, Manga $manga): string;

    /**
     * Extract chapter data from DOM elements
     *
     * @param DOMXPath $xpath
     * @param \DOMNode $element
     * @param Manga $manga
     * @return array|null
     */
    abstract protected function extractChapterData(DOMXPath $xpath, \DOMNode $element, Manga $manga): ?array;

    /**
     * Extract image URLs from chapter page
     *
     * @param string $html
     * @return array
     */
    abstract protected function extractImageUrls(string $html): array;

    /**
     * Fetch chapters from the source website
     *
     * @param Manga $manga
     * @param string $bucket
     * @return array
     */
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

        // Get chapter elements - this XPath seems consistent across sites
        $chapterElements = $xpath->query('//div[@class="eplister"]//li');

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

            // Schedule image fetching job
            dispatch(new FetchChapterImageJob($chapter, $manga, $bucket))
                ->delay(now()->addSeconds(random_int(5, 50)));
        }

        return $chapters;
    }

    /**
     * Fetch images for a specific chapter
     *
     * @param MangaChapter $chapter
     * @param Manga $manga
     * @param string $bucket
     * @return array
     */
    public function fetchChapterImages(MangaChapter $chapter, Manga $manga, string $bucket): array
    {
        $url = $this->getChapterUrl($chapter, $manga);
        Log::info("Fetching images for chapter: {$chapter->title} from {$url}");

        try {
            $response = Http::withHeaders([
                'User-Agent' => $this->getRandomUserAgent()
            ])->get($url);

            if (!$response->successful()) {
                Log::error("Failed to fetch chapter page: {$url}");
                return [];
            }

            $html = $response->body();
            $imageUrls = $this->extractImageUrls($html);

            if (empty($imageUrls)) {
                Log::warning("No images found for chapter: {$chapter->title}");
                return [];
            }

            $storedImages = [];

            DB::beginTransaction();
            try {
                foreach ($imageUrls as $index => $imageUrl) {
                    if (empty($imageUrl)) {
                        Log::warning("Image url: {$index} not found");
                        continue;
                    }

                    $filename = basename(parse_url($imageUrl, PHP_URL_PATH));

                    if (preg_match('/(ads|banner|advertisement)/i', $filename)) {
                        Log::info("Skipping ad/banner image: {$filename}");
                        continue;
                    }

                    $imageResponse = Http::timeout(60)->get($imageUrl);
                    if (!$imageResponse->successful()) {
                        Log::warning("Failed to download image {$index} for chapter {$chapter->title} for manga {$chapter->manga_id}");
                        continue;
                    }

                    $extension = pathinfo($filename, PATHINFO_EXTENSION) ?: 'jpg';
                    $fileName = "chapters/{$chapter->manga_id}/{$chapter->id}/{$index}.{$extension}";

                    try {
                        $storageInfo = $this->bucketManager->storeFile(
                            $fileName,
                            $imageResponse->body(),
                            $bucket,
                            ['visibility' => 'public']
                        );

                        $storedImages[] = $storageInfo['url'];

                        usleep(2000000);
                    } catch (\Exception $e) {
                        Log::error("Failed to store image {$index} for chapter {$chapter->title}: " . $e->getMessage());
                        throw $e;
                    }
                }

                $chapter->image = json_encode($storedImages);
                $chapter->bucket = $bucket;
                $chapter->save();

                DB::table('manga_detail')->where('manga_id', $chapter->manga_id)->update([
                    'updated_at' => now()
                ]);

                DB::commit();
                Log::info("Successfully processed {$chapter->title} with " . count($storedImages) . " images in bucket {$bucket}");

                return $storedImages;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('error process chapter image: ' / $e);

                if (!empty($storedImages)) {
                    foreach ($storedImages as $url) {
                        $pathParts = parse_url($url);
                        $relativePath = ltrim($pathParts['path'], '/');
                        $this->bucketManager->deleteFile($bucket, $relativePath);
                    }
                }

                throw $e;
            }
        } catch (\Exception $e) {
            Log::error("Error processing chapter {$chapter->title}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get a random user agent
     *
     * @return string
     */
    protected function getRandomUserAgent(): string
    {
        $userAgents = [
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
        ];
        return $userAgents[array_rand($userAgents)];
    }
}
