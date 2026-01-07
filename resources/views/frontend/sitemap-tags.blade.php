@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
@php echo '<?xml-stylesheet type="text/xsl" href="'.asset('xsl/sitemap-urlset.xsl').'"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($tags as $tag)
        <url>
            <loc>{{ tag_permalink($tag) }}</loc>
            <lastmod>{{ optional($tag->updated_at ?? $tag->created_at)->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>{{ $changeFrequency }}</changefreq>
            <priority>{{ $priority }}</priority>
        </url>
    @endforeach
</urlset>
