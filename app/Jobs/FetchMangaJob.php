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

        // Determine which domain we're dealing with
        $domain = parse_url($url, PHP_URL_HOST);
        $isWestManga = str_contains($domain, 'westmanga');

        DB::beginTransaction();

        try {
            // Extract and sanitize slug from the URL
            $slug = $this->extractAndSanitizeSlug($url);
            if (empty($slug)) {
                throw new \Exception("Failed to extract slug from URL: {$url}");
            }

            // Extract manga title - different XPath for each domain
            if ($isWestManga) {
                $title = trim($xpath->evaluate('string(//h1[@class="entry-title"])'));
            } else {
                $title = trim($xpath->evaluate('string(//h1[@class="entry-title"])'));
            }

            if (empty($title)) {
                throw new \Exception("Failed to extract manga title from: {$url}");
            }

            $manga = Manga::updateOrCreate(
                ['slug' => $slug],
                ['title' => $title, 'is_project' => 0, 'is_featured' => 0, 'source' => $domain]
            );

            // Extract manga details based on the domain
            if ($isWestManga) {
                // WestManga uses a table structure
                $status = $this->getWestMangaTableValue($xpath, 'Status') ?: '-';
                $type = $this->getWestMangaTableValue($xpath, 'Type') ?: '-';
                $releaseYear = $this->extractYearFromWestManga($xpath) ?: '-';
                $author = $this->getWestMangaTableValue($xpath, 'Posted By') ?: '-';
                $artist = $author; // Use same as author if not explicitly stated
                $views = 0; // WestManga doesn't show views
                $synopsis = $xpath->evaluate('string(//div[contains(@class, "entry-content")]//p)') ?: 'No synopsis available';

                // Extract cover image for WestManga
                $coverImageUrl = $xpath->evaluate('string(//div[contains(@class, "thumb")]//img/@src)');
            } else {
                // Original code for manhwaindo.one
                $status = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Status")]/i)') ?: '-';
                $type = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Type")]/a)') ?: '-';
                $releaseYear = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Released")]/i)') ?: '-';
                $author = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Author")]/i)') ?: '-';
                $artist = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Artist")]/i)') ?: '-';
                $viewsText = $xpath->evaluate('string(//div[contains(@class, "imptdt")][contains(text(), "Views")]/i)');
                $views = preg_replace('/[^0-9]/', '', $viewsText) ?: 0;
                $synopsis = $xpath->evaluate('string(//div[@class="entry-content entry-content-single"]/p)') ?: 'No synopsis available';

                // Extract cover image for manhwaindo.one
                $coverImageUrl = $xpath->evaluate('string(//div[@class="thumb"]//img/@src)');
            }

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

            // Attach genres - different for each domain
            if ($isWestManga) {
                $genreElements = $xpath->query('//div[@class="seriestugenre"]/a');
            } else {
                $genreElements = $xpath->query('//span[@class="mgen"]/a');
            }

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

    /**
     * Helper method to extract values from WestManga's table structure
     */
    private function getWestMangaTableValue(DOMXPath $xpath, string $label): ?string
    {
        $query = "//div[contains(@class, 'seriestucontr')]//table//tr[td[text()='{$label}']]/td[2]";
        $result = $xpath->evaluate("string({$query})");
        return !empty($result) ? trim($result) : null;
    }

    /**
     * Extract year from WestManga's posted date
     */
    private function extractYearFromWestManga(DOMXPath $xpath): ?string
    {
        $postedDate = $this->getWestMangaTableValue($xpath, 'Posted On');
        if (!empty($postedDate)) {
            // Extract year from date string like "May 19, 2019"
            if (preg_match('/(\d{4})/', $postedDate, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Extract and sanitize slug from URL
     */
    private function extractAndSanitizeSlug(string $url): string
    {
        // Parse the URL to get the path
        $path = parse_url($url, PHP_URL_PATH);

        // Remove trailing slash if present
        $path = rtrim($path, '/');

        // Get the last segment of the path (the slug)
        $pathSegments = explode('/', $path);
        $rawSlug = end($pathSegments);

        // URL decode the slug to handle encoded characters
        $decodedSlug = urldecode($rawSlug);

        // Sanitize: Replace special characters and spaces with hyphens
        // Keep alphanumeric characters, hyphens, and underscores
        $sanitizedSlug = preg_replace('/[^a-zA-Z0-9_-]/', '-', $decodedSlug);

        // Remove consecutive hyphens
        $sanitizedSlug = preg_replace('/-+/', '-', $sanitizedSlug);

        // Trim hyphens from beginning and end
        $sanitizedSlug = trim($sanitizedSlug, '-');

        // Convert to lowercase
        $sanitizedSlug = strtolower($sanitizedSlug);

        Log::info("Original slug: {$rawSlug}, Sanitized slug: {$sanitizedSlug}");

        return $sanitizedSlug;
    }

    private function extractSlugFromUrl(string $url): string
    {
        // Extract the slug from the URL
        $path = parse_url($url, PHP_URL_PATH);
        $slug = basename($path);

        return $slug;
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
