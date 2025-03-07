{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @for ($i = 1; $i <= $mangaParts; $i++)
        <sitemap>
            <loc>{{ url('/sitemap-manga.xml?page=' . $i) }}</loc>
            <lastmod>{{ $latestMangaUpdate->toAtomString() }}</lastmod>
        </sitemap>
    @endfor

    @for ($i = 1; $i <= $chapterParts; $i++)
        <sitemap>
            <loc>{{ url('/sitemap-chapters.xml?page=' . $i) }}</loc>
            <lastmod>{{ $latestChapterUpdate->toAtomString() }}</lastmod>
        </sitemap>
    @endfor
</sitemapindex>
