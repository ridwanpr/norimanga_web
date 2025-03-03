<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Factories\ChapterScraperFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchChapterImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 3;
    private $chapter;
    private $manga;
    private $bucket;

    public function __construct($chapter, $manga, string $bucket)
    {
        $this->chapter = $chapter;
        $this->manga = $manga;
        $this->bucket = $bucket;
    }

    public function handle()
    {
        try {
            $scraper = ChapterScraperFactory::getScraper($this->manga);
            $images = $scraper->fetchChapterImages($this->chapter, $this->manga, $this->bucket);

            Log::info("Fetched " . count($images) . " images for chapter: {$this->chapter->title}");
        } catch (\Exception $e) {
            Log::error("Failed to fetch images for chapter {$this->chapter->title}: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("FetchChapterImagesJob failed for chapter {$this->chapter->title}: " . $exception->getMessage());
    }
}
