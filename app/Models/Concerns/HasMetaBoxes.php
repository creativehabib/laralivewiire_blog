<?php

namespace App\Models\Concerns;

use App\Models\MetaBox;

trait HasMetaBoxes
{
    public static function bootHasMetaBoxes()
    {
        static::deleting(function ($model) {
            $model->metaBoxes()->delete();
        });
    }
    public function metaBoxes()
    {
        return $this->morphMany(MetaBox::class, 'reference', 'reference_type', 'reference_id');
    }

    public function getMeta(string $key, $default = null)
    {
        $value = $this->metaBoxes()
            ->where('meta_key', $key)
            ->value('meta_value');

        return is_null($value) ? $default : $value;
    }

    public function setMeta(string $key, $value)
    {
        // $value = array / string, আমরা JSON হিসেবে save করব
        $this->metaBoxes()->updateOrCreate(
            ['meta_key' => $key],
            ['meta_value' => $value]
        );
    }

    public function deleteMeta(string $key)
    {
        $this->metaBoxes()->where('meta_key', $key)->delete();
    }
}
