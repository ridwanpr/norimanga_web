<?php

namespace App\Jobs;

use DOMXPath;
use DOMDocument;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\MangaDetail;
use Illuminate\Bus\Queueable;
use App\Services\BucketManager;
use App\Factories\MangaScraperFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchMangaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url;
    private $bucket;
    private $bucketManager;

    public function __construct(string $url, string $bucket)
    {
        $this->url = $url;
        $this->bucket = $bucket;
        $this->bucketManager = new BucketManager();
    }

    public function handle(): void
    {
        $url = $this->url;
        Log::info("Fetching: {$url}");

        try {
            // Fetch the HTML content
            $html = $this->fetchContent($url);

            // Parse the HTML
            $dom = new DOMDocument();
            @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($dom);

            // Create appropriate scraper based on the domain
            $scraper = MangaScraperFactory::create($url, $xpath);
            // Process the manga
            $this->processManga($scraper, $url);
        } catch (\Exception $e) {
            Log::error("Error in FetchMangaJob: " . $e->getMessage());
        }
    }

    /**
     * Fetch content from URL
     */
    private function fetchContent(string $url): string
    {
        $response = Http::withHeaders([
            'User-Agent' => $this->getRandomUserAgent()
        ])->get($url);

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch: {$url}");
        }

        return $response->body();
    }

    /**
     * Process manga data
     */
    private function processManga($scraper, $url): void
    {
        DB::beginTransaction();
        $coverPath = null;
        $bucket = null;

        try {
            // Extract manga data
            $mangaData = $scraper->extractBasicInfo();
            $slug = $this->sanitizeSlug($mangaData['slug'] ?? $this->extractSlugFromUrl($url));

            if (empty($slug) || empty($mangaData['title'])) {
                throw new \Exception("Failed to extract essential data from URL: {$url}");
            }

            // Create or update manga record
            $manga = Manga::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $mangaData['title'],
                    'is_project' => 0,
                    'is_featured' => 0,
                    'source' => parse_url($url, PHP_URL_HOST)
                ]
            );

            // Extract detailed manga information
            $detailsData = $scraper->extractDetails();

            // Process cover image if available
            if (!empty($detailsData['coverImageUrl'])) {
                $coverUploadResult = $this->processCoverImage(
                    $detailsData['coverImageUrl'],
                    $manga->id
                );

                if ($coverUploadResult) {
                    $coverPath = $coverUploadResult['url'];
                    $bucket = $coverUploadResult['bucket'];
                }
            }

            // Save manga details
            MangaDetail::updateOrCreate(
                ['manga_id' => $manga->id],
                [
                    'status' => $detailsData['status'] ?? '-',
                    'type' => $detailsData['type'] ?? '-',
                    'release_year' => $detailsData['releaseYear'] ?? '-',
                    'author' => $detailsData['author'] ?? '-',
                    'artist' => $detailsData['artist'] ?? '-',
                    'views' => $detailsData['views'] ?? 0,
                    'synopsis' => $detailsData['synopsis'] ?? 'No synopsis available',
                    'cover' => $coverPath,
                    'bucket' => $bucket,
                ]
            );

            // Process genres
            $genres = $scraper->extractGenres();
            if (!empty($genres)) {
                $this->processGenres($manga, $genres);
            }

            DB::commit();
            Cache::flush();
            Log::info("Successfully updated: {$manga->title}");
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded cover if transaction failed
            if (!empty($coverPath) && !empty($bucket)) {
                $pathParts = parse_url($coverPath);
                $relativePath = ltrim($pathParts['path'], '/');
                $this->bucketManager->deleteFile($bucket, $relativePath);
                Log::warning("Deleted uploaded cover due to failure.");
            }

            Log::error("Error processing manga: " . $e->getMessage());
        }
    }

    /**
     * Process cover image
     */
    private function processCoverImage(string $coverImageUrl, int $mangaId): ?array
    {
        try {
            Log::debug("Fetching cover image: {$coverImageUrl}");
            $imageResponse = Http::get($coverImageUrl);

            if (!$imageResponse->successful()) {
                Log::warning("Failed to download cover image: {$coverImageUrl}");
                return null;
            }

            $extension = pathinfo($coverImageUrl, PATHINFO_EXTENSION);
            $fileName = 'covers/' . $mangaId . '.' . $extension;

            $storageInfo = $this->bucketManager->storeFile(
                $fileName,
                $imageResponse->body(),
                $this->bucket,
                ['visibility' => 'public']
            );

            Log::debug("Stored cover image to bucket: {$fileName}");
            return $storageInfo;
        } catch (\Exception $e) {
            Log::error("Failed to process cover image: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Process and associate genres with manga
     */
    private function processGenres(Manga $manga, array $genres): void
    {
        $genreIds = [];
        foreach ($genres as $genreName) {
            $genre = Genre::firstOrCreate(
                ['name' => $genreName],
                ['slug' => strtolower(str_replace(' ', '-', $genreName))]
            );
            $genreIds[] = $genre->id;
        }
        $manga->genres()->sync($genreIds);
    }

    /**
     * Sanitize a slug
     */
    private function sanitizeSlug(string $rawSlug): string
    {
        // URL decode the slug to handle encoded characters
        $decodedSlug = urldecode($rawSlug);

        // Sanitize: Replace special characters and spaces with hyphens
        $sanitizedSlug = preg_replace('/[^a-zA-Z0-9_-]/', '-', $decodedSlug);

        // Remove consecutive hyphens
        $sanitizedSlug = preg_replace('/-+/', '-', $sanitizedSlug);

        // Trim hyphens from beginning and end
        $sanitizedSlug = trim($sanitizedSlug, '-');

        // Convert to lowercase
        return strtolower($sanitizedSlug);
    }

    /**
     * Extract slug from URL
     */
    private function extractSlugFromUrl(string $url): string
    {
        // Parse the URL to get the path
        $path = parse_url($url, PHP_URL_PATH);

        // Remove trailing slash if present
        $path = rtrim($path, '/');

        // Get the last segment of the path (the slug)
        $pathSegments = explode('/', $path);
        return end($pathSegments);
    }

    /**
     * Get a random User-Agent
     */
    private function getRandomUserAgent(): string
    {
        $userAgents = [
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36"
        ];
        return $userAgents[array_rand($userAgents)];
    }
}
