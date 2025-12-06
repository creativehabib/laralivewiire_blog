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
     * SeoAnalyzer দিয়ে analysis চালাও
     */
    public function seoAnalysis(?string $focusKeyword = null, ?array $overrideMeta = null): array
    {
        // overrideMeta না দিলে default meta পাঠাচ্ছি
        if ($overrideMeta === null) {
            $overrideMeta = $this->getSeoMeta();
        }

        $focusKeyword = $focusKeyword ?? ($overrideMeta['focus_keyword'] ?? null);

        return SeoAnalyzer::analyze($this, $focusKeyword, $overrideMeta);
    }

    /**
     * analysis চালিয়ে seo_score কলাম আপডেট করো
     */
    public function refreshSeoScore(?string $focusKeyword = null, ?array $overrideMeta = null): void
    {
        $analysis = $this->seoAnalysis($focusKeyword, $overrideMeta);
        if (property_exists($this, 'seo_score') || $this->isFillable('seo_score')) {
            $this->seo_score = $analysis['score'];
            $this->saveQuietly();
        }
    }
}
