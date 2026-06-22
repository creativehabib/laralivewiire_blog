<?php

namespace App\Models;

use App\Support\UserAgent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class UserSession extends Model
{
    protected $table = 'sessions';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function lastSeenAt(): Attribute
    {
        return Attribute::get(fn (): Carbon => Carbon::createFromTimestamp((int) $this->last_activity));
    }

    protected function browserName(): Attribute
    {
        return Attribute::get(fn (): string => $this->browser ?: UserAgent::browser((string) $this->user_agent));
    }

    protected function platformName(): Attribute
    {
        return Attribute::get(fn (): string => $this->platform ?: UserAgent::platform((string) $this->user_agent));
    }

    protected function deviceName(): Attribute
    {
        return Attribute::get(fn (): string => $this->device ?: UserAgent::device((string) $this->user_agent));
    }
}
