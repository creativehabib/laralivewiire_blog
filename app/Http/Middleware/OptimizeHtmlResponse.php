<?php

namespace App\Http\Middleware;

use App\Support\HtmlOptimizer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeHtmlResponse
{
    public function __construct(private readonly HtmlOptimizer $optimizer)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! setting('optimize_enabled', false)) {
            return $response;
        }

        if ($this->shouldSkip($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $hosts = $this->collectHosts($content, $request->getHost());

        $optimized = $this->optimizer->optimize($content, [
            'collapse_whitespace' => (bool) setting('optimize_collapse_whitespace', false),
            'elide_attributes' => (bool) setting('optimize_elide_attributes', false),
            'inline_css' => (bool) setting('optimize_inline_css', false),
            'dns_prefetch' => (bool) setting('optimize_dns_prefetch', false),
            'remove_comments' => (bool) setting('optimize_remove_comments', false),
            'remove_quotes' => (bool) setting('optimize_remove_quotes', false),
            'defer_javascript' => (bool) setting('optimize_defer_javascript', false),
            'dns_hosts' => $hosts,
        ]);

        $response->setContent($optimized);

        return $response;
    }

    protected function shouldSkip(Request $request, Response $response): bool
    {
        if ($request->is('admin*') || $request->is('livewire/*') || $request->is('storage*')) {
            return true;
        }

        $contentType = $response->headers->get('Content-Type');
        if ($contentType && ! str_contains($contentType, 'text/html')) {
            return true;
        }

        return false;
    }

    protected function collectHosts(string $html, string $currentHost): array
    {
        preg_match_all('/\\s(?:src|href)=(["\'])(https?:\\/\\/[^"\'\\s>]+)\\1/i', $html, $matches);
        $hosts = [];
        foreach ($matches[2] ?? [] as $url) {
            $host = parse_url($url, PHP_URL_HOST);
            if (! $host || $host === $currentHost) {
                continue;
            }
            $hosts[] = $host;
        }

        return array_values(array_unique($hosts));
    }
}
