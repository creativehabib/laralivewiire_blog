@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
@php echo '<?xml-stylesheet type="text/xsl" href="'.asset('xsl/sitemap-urlset.xsl').'"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" @if(!empty($includeImages))xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"@endif>
    @foreach($posts as $post)
        <url>
            <loc>{{ post_permalink($post) }}</loc>
            <lastmod>{{ optional($post->updated_at ?? $post->created_at)->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>{{ $changeFrequency }}</changefreq>
            <priority>{{ $priority }}</priority>
            @if(!empty($includeImages) && !empty($post->image_url))
                <image:image>
                    <image:loc>{{ $post->image_url }}</image:loc>
                </image:image>
            @endif
        </url>
    @endforeach
</urlset>
