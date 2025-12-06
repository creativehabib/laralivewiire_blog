<?php

namespace App\Models\Concerns;

use App\Support\SeoAnalyzer;

trait HasSeoMeta
{
    /**
     * seo_meta meta-box থেকে meta বের করো
     */
    public function getSeoMeta(): array
    {
        if (method_exists($this, 'getMeta')) {
            return $this->getMeta('seo_meta')[0] ?? [];
        }

        return [];
    }

    /**
     * seo_meta meta-box এ সেভ করো
     */
    public function setSeoMeta(array $data): void
    {
        if (method_exists($this, 'setMeta')) {
            $this->setMeta('seo_meta', [$data]);
        }
    }

    /**
     * Yoast-style SEO analyzer (reusable for any model)
     */
    public function analyzeSeo(
        ?string $focusKeyword = null,
        ?array $overrideMeta = null,
        array $options = []
    ): array {
        // overrideMeta না দিলে default meta পাঠাচ্ছি
        if ($overrideMeta === null) {
            $overrideMeta = $this->getSeoMeta();
        }

        $focusKeyword = $focusKeyword ?? ($overrideMeta['focus_keyword'] ?? null);

        return SeoAnalyzer::analyze($this, $focusKeyword, $overrideMeta, $options);
    }

    /**
     * Backward compatible alias so পুরনো কোড কাজ করে
     */
    public function seoAnalysis(
        ?string $focusKeyword = null,
        ?array $overrideMeta = null,
        array $options = []
    ): array {
        return $this->analyzeSeo($focusKeyword, $overrideMeta, $options);
    }

    /**
     * analysis চালিয়ে seo_score কলাম আপডেট করো
     */
    public function refreshSeoScore(
        ?string $focusKeyword = null,
        ?array $overrideMeta = null,
        array $options = []
    ): void {
        $analysis = $this->seoAnalysis($focusKeyword, $overrideMeta, $options);
        if (property_exists($this, 'seo_score') || $this->isFillable('seo_score')) {
            $this->seo_score = $analysis['score'];
            $this->saveQuietly();
        }
    }
}
