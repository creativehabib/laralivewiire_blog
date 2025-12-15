<?php

namespace App\Rules;

use Closure;
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

        foreach ($this->models as $modelClass) {
            /** @var Model $model */
            $model = new $modelClass();

            $query = $modelClass::query()->where($model->getRouteKeyName(), $slug);

            if ($this->ignore && $this->ignore instanceof $modelClass) {
                $query->whereKeyNot($this->ignore->getKey());
            }

            if ($query->exists()) {
                $fail("This slug is already used in {$model->getTable()}.");
                return;
            }
        }
    }
}
