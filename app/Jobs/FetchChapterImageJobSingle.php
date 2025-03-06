<?php

namespace App\Jobs;

use App\Models\MangaChapter;
use App\Services\BucketManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Scrapers\ImageChapterExtractor;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchChapterImageJobSingle implements ShouldQueue
{
    use Queueable;

    private $manga_id;
    private $bucket;
    private $title;
    private $chapter_number;
    private $chapter_url;
    private $bucketManager;
    private $imageChapterExtractor;

    public function __construct($manga_id, $bucket, $title, $chapter_number, $chapter_url)
    {
        $this->manga_id = $manga_id;
        $this->bucket = $bucket;
        $this->title = $title;
        $this->chapter_number = $chapter_number;
        $this->chapter_url = $chapter_url;
        $this->bucketManager = new BucketManager();
        $this->imageChapterExtractor = new ImageChapterExtractor();
    }

    public function handle(): void
    {
        $url = $this->chapter_url;
        Log::info("Processing chapter: {$this->title}, {$this->chapter_url}");

        try {
            $response = Http::withHeaders([
                'User-Agent' => $this->getRandomUserAgent()
            ])->get($url);

            if (!$response->successful()) {
                Log::error("Failed to fetch chapter page: {$url}, Status: " . $response->status());
                return;
            }

            $slug = $this->sanitizeSlug(basename(parse_url($this->chapter_url, PHP_URL_PATH)));
            $html = $response->body();
            $domain = parse_url($url, PHP_URL_HOST);

            if (str_contains($domain, 'comicaso')) {
                $imageUrls = $this->imageChapterExtractor->comicasoExtractImageUrls($html);
            } elseif (str_contains($domain, 'kiryuu01')) {
                $imageUrls = $this->imageChapterExtractor->kiryuuExtractImageUrls($html);
            } else {
                $imageUrls = $this->imageChapterExtractor->extractImageUrls($html);
            }

            if (empty($imageUrls)) {
                Log::warning("No images found for {$this->title}");
                return;
            }

            $storedImages = [];
            DB::beginTransaction();

            try {
                foreach ($imageUrls as $index => $imageUrl) {
                    if (empty($imageUrl)) {
                        continue;
                    }

                    $chapter = MangaChapter::updateOrCreate(
                        [
                            'manga_id' => $this->manga_id,
                            'chapter_number' => $this->chapter_number
                        ],
                        [
                            'title' => $this->title,
                            'slug' => $slug,
                            'image' => json_encode([]),
                            'bucket' => $this->bucket,
                        ]
                    );

                    $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
                    if (preg_match('/(ads|banner|advertisement)/i', $filename)) {
                        continue;
                    }

                    $imageResponse = Http::timeout(120)->get($imageUrl);
                    if (!$imageResponse->successful()) {
                        Log::warning("Failed to download image {$index} for chapter {$chapter->title}");
                        continue;
                    }

                    $extension = pathinfo($filename, PATHINFO_EXTENSION) ?: 'jpg';
                    $fileName = "chapters/{$chapter->manga_id}/{$chapter->id}/{$index}.{$extension}";

                    try {
                        $storageInfo = $this->bucketManager->storeFile(
                            $fileName,
                            $imageResponse->body(),
                            $this->bucket,
                            ['visibility' => 'public']
                        );

                        $storedImages[] = $storageInfo['url'];
                    } catch (\Exception $e) {
                        Log::error("Failed to store image {$index} for chapter {$chapter->title}: " . $e->getMessage());
                        throw $e;
                    }
                }

                if (!empty($storedImages)) {
                    $chapter->image = json_encode($storedImages);
                    $chapter->bucket = $this->bucket;
                    $chapter->save();
                    DB::table('manga_detail')->where('manga_id', $chapter->manga_id)->update([
                        'updated_at' => now()
                    ]);
                }

                DB::commit();

                Cache::flush();
                Log::info("Processed {$chapter->title} with " . count($storedImages) . " images.");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Transaction failed: " . $e->getMessage());

                foreach ($storedImages as $url) {
                    $pathParts = parse_url($url);
                    $relativePath = ltrim($pathParts['path'], '/');
                    $this->bucketManager->deleteFile($this->bucket, $relativePath);
                }

                throw $e;
            }
        } catch (\Exception $e) {
            Log::error("Job failed for chapter {$this->title}: " . $e->getMessage());
            throw $e;
        }
    }

    private function sanitizeSlug(string $rawSlug): string
    {
        $decodedSlug = urldecode($rawSlug);
        $sanitizedSlug = preg_replace('/[^a-zA-Z0-9_-]/', '-', $decodedSlug);
        $sanitizedSlug = preg_replace('/-+/', '-', $sanitizedSlug);
        $sanitizedSlug = trim($sanitizedSlug, '-');

        return strtolower($sanitizedSlug);
    }

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
