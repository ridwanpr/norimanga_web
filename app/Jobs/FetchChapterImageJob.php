<?php

namespace App\Jobs;

use DOMXPath;
use DOMDocument;
use Illuminate\Bus\Queueable;
use App\Services\BucketManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchChapterImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout
    public $tries = 3; // Number of retries
    private $chapter;
    private $bucketManager;

    public function __construct($chapter)
    {
        $this->chapter = $chapter;
        $this->bucketManager = new BucketManager();
    }

    public function handle()
    {
        $url = "https://manhwaindo.one/{$this->chapter->slug}";
        Log::info("Fetching images for chapter: {$this->chapter->title} from {$url}");

        try {
            $response = Http::withHeaders([
                'User-Agent' => $this->getRandomUserAgent()
            ])->get($url);

            if (!$response->successful()) {
                Log::error("Failed to fetch chapter page: {$url}");
                return;
            }

            $html = $response->body();

            // Extract image URLs from the script tag
            $imageUrls = $this->extractImageUrlsFromScript($html);

            if (empty($imageUrls)) {
                Log::warning("No images found for chapter: {$this->chapter->title}");
                return;
            }

            Log::info("Found " . count($imageUrls) . " images for chapter: {$this->chapter->title}");

            DB::beginTransaction();
            try {
                $storedImageUrls = [];
                $currentUrls = !empty($this->chapter->image) ? json_decode($this->chapter->image, true) : [];

                // Determine which bucket to use
                $bucket = $this->chapter->bucket;

                // If no bucket is assigned yet, get a new one
                if (empty($bucket)) {
                    $bucket = $this->bucketManager->getCurrentBucket();
                    $this->chapter->bucket = $bucket;
                    $this->chapter->save();
                    Log::info("Assigned bucket {$bucket} for chapter {$this->chapter->title}");
                } else {
                    Log::info("Using existing bucket {$bucket} for chapter {$this->chapter->title}");
                }

                foreach ($imageUrls as $index => $imageUrl) {
                    // Skip advertisement images if needed
                    if (stripos($imageUrl, 'ads') !== false || stripos($imageUrl, 'banner') !== false) {
                        Log::info("Skipping advertisement image: {$imageUrl}");
                        continue;
                    }

                    // Download image
                    Log::info("Downloading image {$index}: {$imageUrl}");
                    $imageResponse = Http::timeout(30)->withHeaders([
                        'User-Agent' => $this->getRandomUserAgent(),
                        'Referer' => $url
                    ])->get($imageUrl);

                    if (!$imageResponse->successful()) {
                        Log::warning("Failed to download image {$index} for chapter {$this->chapter->title}");
                        continue;
                    }

                    $extension = pathinfo($imageUrl, PATHINFO_EXTENSION) ?: 'jpg';
                    $fileName = "chapters/{$this->chapter->manga_id}/{$this->chapter->id}/{$index}.{$extension}";

                    try {
                        // Always use the same bucket that was assigned to this chapter
                        $url = Storage::disk($bucket)->url($fileName);
                        Storage::disk($bucket)->put($fileName, $imageResponse->body(), ['visibility' => 'public']);

                        // Update bucket usage metrics
                        $size = strlen($imageResponse->body());
                        \App\Models\BucketUsage::updateOrCreate(
                            ['bucket_name' => $bucket],
                            ['total_bytes' => DB::raw("total_bytes + {$size}")]
                        );

                        $storedImageUrls[] = $url;

                        Log::info("Stored image {$index} for chapter {$this->chapter->title} in bucket {$bucket}");

                        // Add small delay between image downloads
                        usleep(500000); // 0.5 second delay
                    } catch (\Exception $e) {
                        Log::error("Failed to store image {$index} for chapter {$this->chapter->title} in bucket {$bucket}: " . $e->getMessage());
                        throw $e;
                    }
                }

                // Update chapter with stored images (merge with existing if any)
                $this->chapter->image = json_encode(array_merge($currentUrls, $storedImageUrls));
                $this->chapter->save();

                DB::commit();
                Log::info("Successfully processed {$this->chapter->title} with " . count($storedImageUrls) . " images in bucket {$bucket}");
            } catch (\Exception $e) {
                DB::rollBack();

                // Clean up any stored images on failure
                if (!empty($storedImageUrls) && !empty($bucket)) {
                    foreach ($storedImageUrls as $url) {
                        $pathParts = parse_url($url);
                        $relativePath = ltrim($pathParts['path'], '/');
                        $this->bucketManager->deleteFile($bucket, $relativePath);
                    }
                }

                throw $e;
            }
        } catch (\Exception $e) {
            Log::error("Error processing chapter {$this->chapter->title}: " . $e->getMessage());
            throw $e;
        }
    }

    private function extractImageUrlsFromScript($html)
    {
        // Pattern to find the ts_reader.run script
        if (preg_match('/<script>ts_reader\.run\((.*?)\);<\/script>/s', $html, $matches)) {
            $jsonData = $matches[1];

            // Replace improperly escaped quotes
            $jsonData = str_replace('\"', '"', $jsonData);

            // Decode the JSON data
            $data = json_decode($jsonData, true);

            // Extract image URLs
            if ($data && isset($data['sources']) && !empty($data['sources'])) {
                foreach ($data['sources'] as $source) {
                    if (isset($source['images']) && !empty($source['images'])) {
                        Log::info("Found " . count($source['images']) . " images from source: " . ($source['source'] ?? 'Unknown'));
                        return $source['images'];
                    }
                }
            }

            Log::warning("JSON data found but no images extracted: " . substr($jsonData, 0, 100) . "...");
        } else {
            Log::warning("No ts_reader.run script found in HTML");
        }

        return [];
    }

    private function getRandomUserAgent()
    {
        $userAgents = [
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
        ];
        return $userAgents[array_rand($userAgents)];
    }

    public function failed(\Throwable $exception)
    {
        Log::error("FetchChapterImagesJob failed for chapter {$this->chapter->title}: " . $exception->getMessage());
    }
}
