<?php
namespace App\Scrapers;
use Illuminate\Support\Facades\Log;
use App\Scrapers\Base\BaseMangaScraper;

class KomikIndoScraper extends BaseMangaScraper
{
    public function extractBasicInfo(): array
    {
        $rawTitle = trim($this->xpath->evaluate('string(//h1[@class="entry-title"])'));
        $title = preg_replace('/^Komik\s*/i', '', $rawTitle);

        // Extract alternative title
        $alternativeTitle = '';
        $altTitleNode = $this->xpath->query('//div[@class="spe"]/span[b[contains(text(), "Judul Alternatif")]]/text()');
        if ($altTitleNode->length > 0) {
            foreach ($altTitleNode as $node) {
                $text = trim($node->nodeValue);
                if (!empty($text)) {
                    $alternativeTitle = $text;
                    break;
                }
            }
        }

        return [
            'title' => $title,
            'alternative_title' => $alternativeTitle,
            'slug' => $this->extractSlugFromUrl()
        ];
    }

    public function extractDetails(): array
    {
        // Extract status
        $status = '';
        $statusNode = $this->xpath->query('//div[@class="spe"]/span[b[contains(text(), "Status")]]/text()');
        if ($statusNode->length > 0) {
            foreach ($statusNode as $node) {
                $text = trim($node->nodeValue);
                if (!empty($text)) {
                    $status = $text;
                    break;
                }
            }
        }

        // Translate status from Indonesian to English
        $status = $this->translateStatus($status);

        // Extract author
        $author = '';
        $authorNode = $this->xpath->query('//div[@class="spe"]/span[b[contains(text(), "Pengarang")]]/text()');
        if ($authorNode->length > 0) {
            foreach ($authorNode as $node) {
                $text = trim($node->nodeValue);
                if (!empty($text)) {
                    $author = $text;
                    break;
                }
            }
        }

        // Extract artist
        $artist = '';
        $artistNode = $this->xpath->query('//div[@class="spe"]/span[b[contains(text(), "Ilustrator")]]/text()');
        if ($artistNode->length > 0) {
            foreach ($artistNode as $node) {
                $text = trim($node->nodeValue);
                if (!empty($text)) {
                    $artist = $text;
                    break;
                }
            }
        }

        // Extract type
        $type = trim($this->xpath->evaluate('string(//div[@class="spe"]/span[b[contains(text(), "Jenis Komik")]]/a)'));

        // Extract cover image URL
        $coverImageUrl = trim($this->xpath->evaluate('string(//div[contains(@class, "thumb")]//img/@src)'));

        // Extract synopsis
        $synopsis = trim($this->xpath->evaluate('string(//div[@class="shortcsc sht2"]/p)'));

        return [
            'status' => $status ?: '-',
            'type' => $type ?: '-',
            'author' => $author ?: '-',
            'artist' => $artist ?: '-',
            'synopsis' => $synopsis ?: 'No synopsis available',
            'coverImageUrl' => $coverImageUrl
        ];
    }

    /**
     * Translate status from Indonesian to English
     */
    private function translateStatus(string $status): string
    {
        $status = trim($status);

        switch ($status) {
            case 'Berjalan':
                return 'Ongoing';
            case 'Tamat':
                return 'Completed';
            default:
                return $status;
        }
    }

    public function extractGenres(): array
    {
        // Fixed selector for genres - this is the key part that was failing
        $genreElements = $this->xpath->query('//div[contains(@class, "genre-info")]/a');
        $genres = [];

        if ($genreElements->length > 0) {
            foreach ($genreElements as $genre) {
                $genreName = trim($genre->textContent);
                if (!empty($genreName)) {
                    $genres[] = $genreName;
                }
            }
        }

        // Debug log to see what's being extracted
        Log::debug("Extracted genres: " . implode(", ", $genres));

        return $genres;
    }

    public function extractChapters(): array
    {
        $chapters = [];
        $chapterElements = $this->xpath->query('//div[@class="epsbr"]/a');

        if ($chapterElements->length === 0) {
            // Try alternative selector for chapters
            $chapterElements = $this->xpath->query('//div[contains(@class, "epsbr")]/a');
        }

        foreach ($chapterElements as $chapter) {
            $title = '';
            $titleNode = $this->xpath->query('.//span[contains(@class, "barunew")]', $chapter);
            if ($titleNode->length > 0) {
                $title = trim($titleNode->item(0)->textContent);
            }

            if (empty($title)) {
                $title = trim($chapter->textContent);
            }

            $url = trim($chapter->getAttribute('href'));

            if ($title && $url) {
                $chapters[] = [
                    'title' => $title,
                    'url' => $url
                ];
            }
        }

        return $chapters;
    }
}
