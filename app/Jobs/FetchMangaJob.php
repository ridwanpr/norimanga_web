<?php

namespace App\Jobs;

use DOMXPath;
use DOMDocument;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\MangaDetail;
use App\Models\MangaChapter;
use Illuminate\Bus\Queueable;
use App\Services\BucketManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\ErrorHandler\Debug;

class FetchMangaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $bucketManager;
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
        $this->bucketManager = new BucketManager();
    }

    public function handle(): void
    {
        $url = $this->url;
        Log::info("Fetching: {$url}");

        $response = Http::withHeaders([
            'User-Agent' => $this->getRandomUserAgent()
        ])->get($url);

        if (!$response->successful()) {
            Log::error("Failed to fetch: {$url}");
            return;
        }

        $html = $response->body();
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new DOMXPath($dom);

        DB::beginTransaction();

        try {
            // Extract manga title
            $title = trim($xpath->evaluate('string(//h1[@class="entry-title"])'));
            if (empty($title)) {
                throw new \Exception("Failed to extract manga title from: {$url}");
            }

            // Generate slug
            $slug = strtolower(str_replace(' ', '-', $title));

            // Create or update manga record
            $manga = Manga::updateOrCreate(
                ['slug' => $slug],
                ['title' => $title, 'is_project' => 0, 'is_featured' => 0]
            );

            // Extract manga details
            $status = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Status")]/i)') ?: '-';
            $type = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Type")]/a)') ?: '-';
            $releaseYear = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Released")]/i)') ?: '-';
            $author = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Author")]/i)') ?: '-';
            $artist = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Artist")]/i)') ?: '-';
            $viewsText = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Views")]/i)');
            $views = preg_replace('/[^0-9]/', '', $viewsText) ?: 0;
            $synopsis = $xpath->evaluate('string(//div[@class="entry-content entry-content-single"]/p)') ?: 'No synopsis available';

            // Extract cover image
            $coverImageUrl = $xpath->evaluate('string(//div[@class="thumb"]//img/@src)');
            $coverPath = null;
            $bucket = null;

            if (!empty($coverImageUrl)) {
                Log::debug("Fetching cover image: {$coverImageUrl}");
                $imageResponse = Http::get($coverImageUrl);
                if ($imageResponse->successful()) {
                    $extension = pathinfo($coverImageUrl, PATHINFO_EXTENSION);
                    $fileName = 'covers/' . $manga->id . '.' . $extension;

                    try {
                        Log::debug("Attempting to store cover image to bucket: {$fileName}");
                        $storageInfo = $this->bucketManager->storeFile(
                            $fileName,
                            $imageResponse->body(),
                            ['visibility' => 'public']
                        );

                        Log::debug("Stored cover image to bucket: {$fileName}");

                        $coverPath = $storageInfo['url'];
                        $bucket = $storageInfo['bucket'];
                    } catch (\Exception $e) {
                        Log::error("Failed to store cover image for manga {$title}: " . $e->getMessage());
                        throw $e;
                    }
                }
            }

            // Save manga details
            MangaDetail::updateOrCreate(
                ['manga_id' => $manga->id],
                [
                    'status' => $status,
                    'type' => $type,
                    'release_year' => $releaseYear,
                    'author' => $author,
                    'artist' => $artist,
                    'views' => $views,
                    'synopsis' => $synopsis,
                    'cover' => $coverPath,
                    'bucket' => $bucket,
                ]
            );

            // Attach genres
            $genreElements = $xpath->query('//span[@class="mgen"]/a');
            $genres = [];

            foreach ($genreElements as $genre) {
                $genres[] = trim($genre->textContent);
            }

            if (!empty($genres)) {
                $genreIds = [];
                foreach ($genres as $genreName) {
                    $genre = Genre::firstOrCreate(['name' => $genreName], ['slug' => strtolower(str_replace(' ', '-', $genreName))]);
                    $genreIds[] = $genre->id;
                }
                $manga->genres()->sync($genreIds);
            }

            DB::commit();

            Cache::flush();

            Log::info("Successfully updated: {$manga->title}");
        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($coverPath) && !empty($bucket)) {
                $pathParts = parse_url($coverPath);
                $relativePath = ltrim($pathParts['path'], '/');
                $this->bucketManager->deleteFile($bucket, $relativePath);
                Log::warning("Deleted uploaded cover for {$title} due to failure.");
            }

            Log::error("Error processing {$title}: " . $e->getMessage());
        }
    }

    private function getRandomUserAgent()
    {
        $userAgents = [
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36"
        ];
        return $userAgents[array_rand($userAgents)];
    }
}
