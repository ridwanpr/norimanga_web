<?php

namespace App\Jobs;

use App\Models\Manga;
use Illuminate\Bus\Queueable;
use App\Factories\ChapterScraperFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchChapterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $manga;
    private $bucket;

    public function __construct($manga, $bucket)
    {
        $this->manga = $manga;
        $this->bucket = $bucket;
    }

    public function handle()
    {
        try {
            $scraper = ChapterScraperFactory::getScraper($this->manga);
            $chapters = $scraper->fetchChapters($this->manga, $this->bucket);

            Log::info("Fetched " . count($chapters) . " chapters for manga: {$this->manga->title}");
        } catch (\Exception $e) {
            Log::error("Failed to fetch chapters for manga {$this->manga->title}: " . $e->getMessage());
            throw $e;
        }
    }
}
