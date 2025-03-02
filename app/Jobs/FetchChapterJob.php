<?php

namespace App\Jobs;

use DOMXPath;
use DOMDocument;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Bus\Queueable;
use App\Jobs\FetchChapterImageJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchChapterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $manga;
    private $bucket; // Required bucket parameter

    public function __construct(Manga $manga, string $bucket)
    {
        $this->manga = $manga;
        $this->bucket = $bucket; // Store the required bucket
    }

    public function handle()
    {
        if ($this->manga->source == 'manhwaindo.one') {
            $url = "https://{$this->manga->source}/series/{$this->manga->slug}/";
        } elseif ($this->manga->source == 'westmanga.fun') {
            $url = "https://{$this->manga->source}/manga/{$this->manga->slug}/";
        }

        Log::info("Fetching chapters for manga: {$this->manga->title} from {$url}");
        $response = Http::withHeaders([
            'User-Agent' => $this->getRandomUserAgent()
        ])->get($url);

        if (!$response->successful()) {
            Log::error("Failed to fetch chapters for: {$this->manga->title}");
            return;
        }

        $html = $response->body();
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new DOMXPath($dom);
        $chapterElements = $xpath->query('//div[@class="eplister"]//li');

        if (!$chapterElements || $chapterElements->length === 0) {
            Log::warning("No chapters found for manga: {$this->manga->title}");
            return;
        }

        $delay = 0;
        foreach ($chapterElements as $element) {
            $linkElement = $xpath->evaluate('.//div[@class="eph-num"]/a', $element)->item(0);
            if (!$linkElement) {
                continue;
            }

            $chapterTitle = $xpath->evaluate('string(.//span[@class="chapternum"])', $element);
            $chapterUrl = $linkElement->getAttribute('href');
            $chapterSlug = basename(rtrim($chapterUrl, '/'));

            // Extract chapter number as a string
            $chapterNumber = $xpath->evaluate('string(.//span[@class="chapternum"])', $element);

            // Ensure chapter number is stored as a string, including "0"
            if (empty($chapterNumber)) {
                Log::warning("Missing chapter number for {$this->manga->title}: {$chapterTitle}");
                continue;
            }

            $chapter = MangaChapter::updateOrCreate(
                [
                    'manga_id' => $this->manga->id,
                    'chapter_number' => $chapterNumber // Store as string
                ],
                [
                    'title' => $chapterTitle,
                    'slug' => $chapterSlug,
                    'image' => json_encode([]),
                    'bucket' => $this->bucket, // Save bucket to the chapter
                ]
            );

            // Pass the bucket parameter to the FetchChapterImageJob
            dispatch(new FetchChapterImageJob($chapter, $this->manga, $this->bucket))
                ->delay(now()->addSeconds(random_int(5, 50)));
        }
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
}
