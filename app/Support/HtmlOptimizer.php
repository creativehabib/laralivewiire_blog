<?php

namespace App\Support;

class HtmlOptimizer
{
    public function optimize(string $html, array $options): string
    {
        if (($options['elide_attributes'] ?? false) === true) {
            $html = $this->elideAttributes($html);
        }

        if (($options['inline_css'] ?? false) === true) {
            $html = $this->inlineCss($html);
        }

        if (($options['dns_prefetch'] ?? false) === true) {
            $html = $this->insertDnsPrefetch($html, $options['dns_hosts'] ?? []);
        }

        if (($options['defer_javascript'] ?? false) === true) {
            $html = $this->deferJavascript($html);
        }

        if (($options['remove_comments'] ?? false) === true) {
            $html = $this->removeComments($html);
        }

        if (($options['remove_quotes'] ?? false) === true) {
            $html = $this->removeQuotes($html);
        }

        if (($options['collapse_whitespace'] ?? false) === true) {
            $html = $this->collapseWhitespace($html);
        }

        return $html;
    }

    protected function elideAttributes(string $html): string
    {
        $html = preg_replace('/\\s+type=(["\'])text\\/javascript\\1/i', '', $html);
        $html = preg_replace('/\\s+type=(["\'])text\\/css\\1/i', '', $html);
        $html = preg_replace('/\\s+method=(["\'])get\\1/i', '', $html);
        $html = preg_replace('/\\s+frameborder=(["\'])0\\1/i', '', $html);

        return $html;
    }

    protected function inlineCss(string $html): string
    {
        $styleMap = [];
        $styleIndex = 0;

        $html = preg_replace_callback(
            '/<([a-z][^\\s>]*)([^>]*?)\\sstyle=("|\')(.*?)\\3([^>]*)>/i',
            function (array $matches) use (&$styleMap, &$styleIndex): string {
                $style = trim($matches[4]);
                if ($style === '') {
                    return $matches[0];
                }

                if (! isset($styleMap[$style])) {
                    $styleIndex++;
                    $styleMap[$style] = 'opt-style-' . $styleIndex;
                }

                $className = $styleMap[$style];
                $attrs = $matches[2] . $matches[5];

                if (preg_match('/\\sclass=("|\')(.*?)\\1/i', $attrs, $classMatch)) {
                    $existing = trim($classMatch[2]);
                    $replacement = $existing === '' ? $className : $existing . ' ' . $className;
                    $attrs = preg_replace(
                        '/\\sclass=("|\')(.*?)\\1/i',
                        ' class="' . $replacement . '"',
                        $attrs,
                        1
                    );
                } else {
                    $attrs .= ' class="' . $className . '"';
                }

                $attrs = trim($attrs);
                $attrs = $attrs !== '' ? ' ' . $attrs : '';

                return '<' . $matches[1] . $attrs . '>';
            },
            $html
        );

        if (empty($styleMap)) {
            return $html;
        }

        $cssLines = array_map(
            function (string $style, string $className): string {
                $style = rtrim($style, ';');
                return '.' . $className . '{' . $style . ';}';
            },
            array_keys($styleMap),
            array_values($styleMap)
        );

        $styleBlock = '<style>' . implode('', $cssLines) . '</style>';

        if (stripos($html, '</head>') !== false) {
            return preg_replace('/<\\/head>/i', $styleBlock . '</head>', $html, 1);
        }

        return $styleBlock . $html;
    }

    protected function insertDnsPrefetch(string $html, array $hosts): string
    {
        $hosts = array_values(array_unique(array_filter($hosts)));
        if ($hosts === []) {
            return $html;
        }

        $links = [];
        foreach ($hosts as $host) {
            if (stripos($html, 'dns-prefetch') !== false && stripos($html, '//' . $host) !== false) {
                continue;
            }
            $links[] = '<link rel="dns-prefetch" href="//' . $host . '">';
        }

        if ($links === []) {
            return $html;
        }

        $injection = implode('', $links);

        if (stripos($html, '</head>') !== false) {
            return preg_replace('/<\\/head>/i', $injection . '</head>', $html, 1);
        }

        return $injection . $html;
    }

    protected function deferJavascript(string $html): string
    {
        return preg_replace_callback(
            '/<script\\b(?![^>]*\\b(?:defer|async)\\b)(?![^>]*\\bdata-pagespeed-no-defer\\b)([^>]*?)\\bsrc=("|\')(.*?)\\2([^>]*)>/i',
            function (array $matches): string {
                return '<script' . $matches[1] . ' src="' . $matches[3] . '"' . $matches[4] . ' defer>';
            },
            $html
        );
    }

    protected function removeComments(string $html): string
    {
        return preg_replace_callback(
            '/<!--(.*?)-->/s',
            function (array $matches): string {
                $comment = $matches[1];
                if (stripos($comment, '[if') !== false || stripos($comment, '[endif') !== false) {
                    return $matches[0];
                }
                return '';
            },
            $html
        );
    }

    protected function removeQuotes(string $html): string
    {
        return preg_replace_callback(
            '/\\s([a-zA-Z:-]+)=(["\'])([a-zA-Z0-9_\\-\\.:]+)\\2/',
            function (array $matches): string {
                return ' ' . $matches[1] . '=' . $matches[3];
            },
            $html
        );
    }

    protected function collapseWhitespace(string $html): string
    {
        $html = preg_replace('/>\\s+</', '><', $html);
        return trim($html);
    }
}
