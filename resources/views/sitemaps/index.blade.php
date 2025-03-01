{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/sitemap-manga.xml') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-chapters.xml') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-genres.xml') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
</sitemapindex>
