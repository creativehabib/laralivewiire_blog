<?php

namespace App\Livewire\Concerns;

use App\Support\SlugService;

trait HandlesSlug
{
    public string $slug = '';
    public ?int $slugId = null;
    public string $slugSeparator = '-';
    public bool $syncSlugWithSeoTitle = false;

    protected function generateSlugValue(string $value): string
    {
        return SlugService::create($value, $this->slugSeparator, $this->slugId);
    }

    public function syncSlugFromName(string $value): void
    {
        $this->name = $value;
        $this->slug = $this->generateSlugValue($value);

        if (property_exists($this, 'autoSeoTitle') && property_exists($this, 'seo_title') && $this->autoSeoTitle) {
            $this->seo_title = $value;
        }
    }

    public function updatedSlug($value): void
    {
        $this->slug = $this->generateSlugValue((string) $value);
    }

    public function updatedSeoTitle($value): void
    {
        if ($this->syncSlugWithSeoTitle) {
            $this->slug = $this->generateSlugValue((string) $value);
        }

        if (property_exists($this, 'autoSeoTitle')) {
            $this->autoSeoTitle = trim((string) $value) === '';
        }
    }

    public function generateSlug(): void
    {
        $source = $this->name ?? $this->slug ?? '';
        $this->slug = $this->generateSlugValue((string) $source);
    }
}
