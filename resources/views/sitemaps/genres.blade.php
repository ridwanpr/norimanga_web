{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($genres as $genre)
        <url>
            <loc>{{ url('/genre/' . $genre->slug) }}</loc>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
        </url>
    @endforeach
</urlset>
