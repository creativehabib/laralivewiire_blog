<?php

namespace App\Models\Concerns;

use App\Support\SlugHelper;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSlug
{
    protected ?string $slugOverride = null;

    public static function bootHasSlug(): void
    {
        static::saved(function ($model): void {
            $model->syncSlug();
        });

        static::deleted(function ($model): void {
            $model->slugRecord()->delete();
        });
    }

    public function slugRecord(): MorphOne
    {
        return $this->morphOne(\App\Models\Slug::class, 'reference');
    }

    public function setSlugAttribute($value): void
    {
        $this->slugOverride = $value === null ? null : (string) $value;
    }

    public function getSlugAttribute(): ?string
    {
        if ($this->slugOverride !== null) {
            return $this->slugOverride;
        }

        $record = $this->relationLoaded('slugRecord')
            ? $this->slugRecord
            : $this->slugRecord()->first();

        if (! $record && $this->exists) {
            $this->syncSlug();
            $record = $this->slugRecord()->first();
        }

        return $record?->key;
    }

    public function syncSlug(?string $value = null): void
    {
        $value ??= $this->slugOverride ?: $this->getSlugSource();

        SlugHelper::createOrUpdate($this, $value);

        $this->slugOverride = null;
    }

    public function getSlugSource(): string
    {
        if (method_exists($this, 'slugSource')) {
            return (string) $this->slugSource();
        }

        foreach (['name', 'title'] as $field) {
            if (property_exists($this, $field) || isset($this->{$field})) {
                return (string) $this->{$field};
            }
        }

        return (string) $this->getKey();
    }
}
