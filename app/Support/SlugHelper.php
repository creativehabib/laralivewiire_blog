<?php

namespace App\Support;

use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\Slug;
use Illuminate\Database\Eloquent\Model;
use App\Support\PermalinkManager;

class SlugHelper
{
    public static function createOrUpdate(Model $model, ?string $value = null): Slug
    {
        $record = $model->slugRecord()->first();
        $ignoreId = $record?->id;

        $prefix = self::prefixForModel($model);
        $value = $value ?: (method_exists($model, 'getSlugSource') ? $model->getSlugSource() : (string) $model->getKey());

        $key = SlugService::create($value, $prefix, $ignoreId);

        if ($record) {
            $record->update([
                'key' => $key,
                'prefix' => $prefix,
            ]);

            return $record;
        }

        return Slug::create([
            'key' => $key,
            'prefix' => $prefix,
            'reference_type' => $model::class,
            'reference_id' => $model->getKey(),
        ]);
    }

    public static function resolveModel(string $slug, string $modelClass): ?Model
    {
        $record = Slug::query()
            ->where('key', $slug)
            ->where('reference_type', $modelClass)
            ->first();

        if ($record) {
            return $record->reference;
        }

        if (is_numeric($slug)) {
            if ($modelClass === Post::class) {
                return Post::find($slug);
            }

            if (request()->is('admin/*')) {
                return $modelClass::find($slug);
            }
        }

        return null;
    }

    public static function prefixForModel(Model $model): string
    {
        if ($model instanceof Post) {
            return PermalinkManager::postPrefix();
        }

        if ($model instanceof Category) {
            return PermalinkManager::categoryPrefix();
        }

        if ($model instanceof Tag) {
            return PermalinkManager::tagPrefix();
        }

        if ($model instanceof Page) {
            return PermalinkManager::pagePrefix();
        }

        return '';
    }
}
