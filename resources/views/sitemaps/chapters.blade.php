{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($chapters as $chapter)
        <url>
            <loc>{{ url('/manga/' . $chapter->manga->slug . '/chapter/' . $chapter->slug) }}</loc>
            <lastmod>{{ $chapter->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
