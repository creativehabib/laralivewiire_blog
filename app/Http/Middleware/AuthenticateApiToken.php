<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticateApiToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return $this->unauthorizedResponse('Missing bearer token.');
        }

        $tokenHash = hash('sha256', $plainToken);

        $apiToken = ApiToken::query()
            ->with('user')
            ->where('token_hash', $tokenHash)
            ->first();

        if (! $apiToken) {
            return $this->unauthorizedResponse('Invalid token.');
        }

        if ($apiToken->expires_at && $apiToken->expires_at->isPast()) {
            return $this->unauthorizedResponse('Token expired.');
        }

        $apiToken->forceFill([
            'last_used_at' => now(),
        ])->save();

        $request->setUserResolver(fn () => $apiToken->user);

        return $next($request);
    }

    private function unauthorizedResponse(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 401);
    }
}
