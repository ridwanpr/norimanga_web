<?php

namespace App\Jobs;

use DOMXPath;
use DOMDocument;
use App\Models\Manga;
use App\Models\MangaChapter;
use Illuminate\Bus\Queueable;
use App\Services\BucketManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchChapterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $bucketManager;
    private $manga;

    public function __construct(Manga $manga)
    {
        $this->manga = $manga;
        $this->bucketManager = new BucketManager();
    }

    public function handle()
    {
        $manga = $this->manga;
        $url = "https://manhwaindo.one/series/{$manga->slug}/";

        Log::info("Fetching chapters for: {$manga->title} ({$url})");

        $response = Http::withHeaders([
            'User-Agent' => $this->getRandomUserAgent()
        ])->get($url);

        if (!$response->successful()) {
            Log::error("Failed to fetch chapters for: {$manga->title}");
            return;
        }

        $html = $response->body();
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new DOMXPath($dom);

        $this->fetchChapterList($manga, $xpath);
    }

    private function fetchChapterList(Manga $manga, DOMXPath $xpath)
    {
        try {
            $chapterElements = $xpath->query('//div[@class="eplister"]//li');
            if (!$chapterElements || $chapterElements->length === 0) {
                Log::warning("No chapters found for: {$manga->title}");
                return;
            }

            foreach ($chapterElements as $element) {
                $chapterNum = $element->getAttribute('data-num');
                $linkElement = $xpath->evaluate('.//div[@class="eph-num"]/a', $element)->item(0);

                if (!$linkElement) {
                    continue;
                }

                $chapterTitle = $xpath->evaluate('string(.//span[@class="chapternum"])', $element);
                $chapterDate = $xpath->evaluate('string(.//span[@class="chapterdate"])', $element);
                $chapterUrl = $linkElement->getAttribute('href');

                $chapterSlug = basename(rtrim($chapterUrl, '/'));
                $chapterNumber = intval($chapterNum);

                if ($chapterNumber === 0) {
                    preg_match('/Chapter (\d+)/', $chapterTitle, $matches);
                    $chapterNumber = isset($matches[1]) ? intval($matches[1]) : 0;
                }

                if ($chapterNumber === 0) {
                    Log::warning("Invalid chapter number for {$manga->title}: {$chapterTitle}");
                    continue;
                }

                MangaChapter::updateOrCreate(
                    [
                        'manga_id' => $manga->id,
                        'chapter_number' => $chapterNumber
                    ],
                    [
                        'title' => $chapterTitle,
                        'slug' => $chapterSlug,
                        'image' => json_encode([]),
                    ]
                );

                Log::info("Processed chapter {$chapterNumber} for: {$manga->title}");
            }
        } catch (\Exception $e) {
            Log::error("Error processing chapters for {$manga->title}: " . $e->getMessage());
            throw $e;
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
