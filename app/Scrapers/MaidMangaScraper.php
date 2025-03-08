<?php

namespace App\Scrapers;

use App\Scrapers\Base\BaseMangaScraper;

class MaidMangaScraper extends BaseMangaScraper
{
    public function extractBasicInfo(): array
    {
        $title = trim($this->xpath->evaluate('string(//div[@class="series-titlex"]/h2)'));

        return [
            'title' => $title,
            'slug' => $this->extractSlugFromUrl()
        ];
    }

    public function extractDetails(): array
    {
        return [
            'status' => $this->getStatus() ?: '-',
            'type' => $this->getType() ?: '-',
            'releaseYear' => $this->extractYearFromPublishedDate() ?: '-',
            'author' => $this->getInfoValue('Author') ?: '-',
            'artist' => $this->getInfoValue('Author') ?: '-',
            'views' => 0,
            'synopsis' => $this->xpath->evaluate('string(//div[contains(@class, "series-synops")]/p)') ?: 'No synopsis available',
            'coverImageUrl' => $this->xpath->evaluate('string(//div[@class="series-thumb"]//img/@src)')
        ];
    }

    public function extractGenres(): array
    {
        $genreElements = $this->xpath->query('//div[@class="series-genres"]/a');
        $genres = [];

        foreach ($genreElements as $genre) {
            $genres[] = trim($genre->textContent);
        }

        return $genres;
    }

    private function getStatus(): ?string
    {
        return $this->xpath->evaluate('string(//div[@class="series-infoz block"]/span[contains(@class, "status")])') ?: null;
    }

    private function getType(): ?string
    {
        return $this->xpath->evaluate('string(//div[@class="series-infoz block"]/span[contains(@class, "type")])') ?: null;
    }

    private function getInfoValue(string $label): ?string
    {
        $query = "//ul[@class='series-infolist']/li[b[text()='{$label}']]/span";
        $result = $this->xpath->evaluate("string({$query})");
        return !empty($result) ? trim($result) : null;
    }

    private function extractYearFromPublishedDate(): ?string
    {
        $publishedDate = $this->getInfoValue('Published');
        if (!empty($publishedDate)) {
            if (preg_match('/(\d{4})/', $publishedDate, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
