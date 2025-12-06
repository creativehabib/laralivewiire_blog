<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaBox extends Model
{
    protected $fillable = [
        'meta_key',
        'meta_value',
        'reference_id',
        'reference_type',
    ];

    protected $casts = [
        'meta_value' => 'array',
    ];

    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }
}
