<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiTokenRequestLog extends Model
{
    use HasFactory;

    public $timestamps = true;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'api_token_id',
    ];

    public function apiToken(): BelongsTo
    {
        return $this->belongsTo(ApiToken::class);
    }
}
