<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Slug extends Model
{
    protected $fillable = [
        'key',
        'prefix',
        'reference_type',
        'reference_id',
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
