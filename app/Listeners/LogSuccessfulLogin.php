<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogSuccessfulLogin
{
    public function __construct(public Request $request) {}

    public function handle(Login $event)
    {
        activity()
            ->causedBy($event->user)
            ->withProperties(['ip' => $this->request->ip(), 'user_agent' => $this->request->userAgent()])
            ->log('logged in to the system');
    }
}
