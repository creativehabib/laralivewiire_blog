<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;

class ActivityLogger
{
    public static function log(?Authenticatable $user, string $message, $subject = null): void
    {
        if (! $user) {
            return;
        }

        $activity = activity()
            ->causedBy($user)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

        if ($subject) {
            $activity->performedOn($subject);
        }

        $activity->log($message);
    }
}
