{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
{!! '<'.'?xml-stylesheet type="text/xsl" href="'.asset('xsl/sitemap-index.xsl').'"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap.pages') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
    </sitemap>

    @if(isset($postGroups) && count($postGroups) > 0)
        @foreach($postGroups as $group)
            @php
                $totalPages = $group->pages ?? 1;
                // মানগুলো নিশ্চিত হয়ে নিন
                $year = $group->year;
                $month = str_pad($group->month, 2, '0', STR_PAD_LEFT);

                $baseUrl = route('sitemap.posts', [
                    'year' => $year,
                    'month' => $month
                ]);
            @endphp

            @for($page = 1; $page <= $totalPages; $page++)
                @php
                    // পেজ ১ হলে ?page=1 দেওয়ার দরকার নেই, যদি আপনার রাউট ওভাবে কাজ করে
                    $pageUrl = $baseUrl . ($page > 1 ? '?page=' . $page : '');
                @endphp
                <sitemap>
                    <loc>{{ $pageUrl }}</loc>
                    <lastmod>
                        @if(!empty($group->lastmod))
                            {{ \Carbon\Carbon::parse($group->lastmod)->tz('UTC')->toAtomString() }}
                        @else
                            {{ now()->tz('UTC')->toAtomString() }}
                        @endif
                    </lastmod>
                </sitemap>
            @endfor
        @endforeach
    @endif

    <sitemap>
        <loc>{{ route('sitemap.categories') }}</loc>
        @if($categoryLastUpdated)
            <lastmod>
                {{ \Carbon\Carbon::parse($categoryLastUpdated)->tz('UTC')->toAtomString() }}
            </lastmod>
        @endif
    </sitemap>
</sitemapindex>
