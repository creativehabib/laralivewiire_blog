<?php

namespace App\Http\Middleware;

use App\Support\UserAgent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackAuthenticatedSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && config('session.driver') === 'database' && $request->hasSession()) {
            $sessionId = $request->session()->getId();
            $userAgent = (string) $request->userAgent();
            $parsed = UserAgent::parse($userAgent);

            DB::table(config('session.table', 'sessions'))
                ->where('id', $sessionId)
                ->update([
                    'user_id' => $request->user()->getAuthIdentifier(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'device' => $parsed['device'],
                    'browser' => $parsed['browser'],
                    'platform' => $parsed['platform'],
                    'location' => $this->location($request),
                ]);
        }

        return $response;
    }

    private function location(Request $request): string
    {
        $country = $request->headers->get('CF-IPCountry') ?: $request->headers->get('X-App-Country');
        $city = $request->headers->get('X-App-City');

        return collect([$city, $country])->filter()->implode(', ') ?: 'Unknown location';
    }
}
