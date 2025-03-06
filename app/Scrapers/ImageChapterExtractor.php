<?php

namespace App\Scrapers;

class ImageChapterExtractor
{
    public function extractImageUrls(string $html): array
    {
        preg_match('/ts_reader\.run\((.*?)\);<\/script>/', $html, $matches);
        if (!isset($matches[1])) {
            return [];
        }

        $jsonData = json_decode($matches[1], true);
        if (!$jsonData || !isset($jsonData['sources'][0]['images'])) {
            return [];
        }

        return $jsonData['sources'][0]['images'];
    }

    /**
     * Extract image URLs from chapter page
     *
     * @param string $html
     * @return array
     */
    function comicasoExtractImageUrls(string $html): array
    {
        preg_match_all('/src="data:text\/javascript;base64,([^"]+)"/', $html, $matches);

        if (empty($matches[1])) {
            return [];
        }

        foreach ($matches[1] as $base64Script) {
            $decodedScript = base64_decode($base64Script);
            if (strpos($decodedScript, 'ts_reader.run') !== false) {
                preg_match('/ts_reader\.run\((.*?)\)/', $decodedScript, $scriptMatches);

                if (!empty($scriptMatches[1])) {
                    $jsonData = json_decode($scriptMatches[1], true);
                    if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
                        preg_match_all('/"images":\s*\[(.*?)\]/s', $scriptMatches[1], $imgMatches);

                        if (!empty($imgMatches[1][0])) {
                            preg_match_all('/"(https:\\\\?\/\\\\?\/[^"]+)"/', $imgMatches[1][0], $urlMatches);

                            if (!empty($urlMatches[1])) {
                                $decodedUrls = [];
                                foreach ($urlMatches[1] as $url) {
                                    $decodedUrls[] = str_replace(['\\/', '\\\\/', '\\\\\\\/'], '/', $url);
                                }
                                return $decodedUrls;
                            }
                        }
                    } else {
                        if (isset($jsonData['sources'][0]['images'])) {
                            $urls = $jsonData['sources'][0]['images'];
                            $decodedUrls = [];

                            foreach ($urls as $url) {
                                $decodedUrls[] = str_replace('\\/', '/', $url);
                            }
                            return $decodedUrls;
                        }
                    }
                }
            }
        }

        return [];
    }
}
