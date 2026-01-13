@props(['seo' => []])
@php
    $meta = \App\Support\Seo::fromArray($seo);
@endphp

<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<meta name="robots" content="{{ $meta['robots'] }}">
<link rel="canonical" href="{{ $meta['canonical'] }}">

<meta property="og:site_name" content="{{ $meta['site_name'] }}" />
<meta property="og:title" content="{{ $meta['title'] }}" />
<meta property="og:description" content="{{ $meta['description'] }}" />
<meta property="og:url" content="{{ $meta['url'] }}" />
<meta property="og:type" content="{{ $meta['type'] }}" />
@if(!empty($meta['image']))
    <meta property="og:image" content="{{ $meta['image'] }}" />
@endif

<meta name="twitter:card" content="{{ $meta['twitter_card'] }}" />
<meta name="twitter:title" content="{{ $meta['title'] }}" />
<meta name="twitter:description" content="{{ $meta['description'] }}" />
@if(!empty($meta['image']))
    <meta name="twitter:image" content="{{ $meta['image'] }}" />
@endif

@if(!empty($meta['published_time']))
    <meta property="article:published_time" content="{{ $meta['published_time'] }}">
@endif
@if(!empty($meta['modified_time']))
    <meta property="article:modified_time" content="{{ $meta['modified_time'] }}">
@endif

<script type="application/ld+json">
    {!! json_encode($meta['schema'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
