<?php

namespace App\Rules;

use Closure;
use App\Models\Slug;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class UniqueSlugAcrossContent implements ValidationRule
{
    /**
     * @param array<class-string<Model>> $models
     */
    public function __construct(
        protected array $models = [],
        protected ?Model $ignore = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slug = (string) $value;

        $query = Slug::query()->where('key', $slug);

        if (! empty($this->models)) {
            $query->whereIn('reference_type', $this->models);
        }

        if ($this->ignore) {
            $slugId = $this->ignore->slugRecord?->id ?? $this->ignore->slugRecord()->value('id');
            if ($slugId) {
                $query->whereKeyNot($slugId);
            }
        }

        if ($query->exists()) {
            $fail('This slug is already used.');
        }
    }
}
