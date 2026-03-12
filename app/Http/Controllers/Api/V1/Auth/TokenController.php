<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
            'expires_in_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'abilities' => ['nullable', 'array'],
            'abilities.*' => ['string', 'max:80'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 422);
        }

        $expiresAt = isset($validated['expires_in_days'])
            ? Carbon::now()->addDays((int) $validated['expires_in_days'])
            : null;

        $tokenPayload = $user->createApiToken(
            $validated['device_name'] ?? 'flutter-app',
            $expiresAt,
            $validated['abilities'] ?? ['content:read'],
        );

        return response()->json([
            'token' => $tokenPayload['plain_text_token'],
            'token_type' => 'Bearer',
            'expires_at' => $tokenPayload['token']->expires_at?->toISOString(),
            'abilities' => $tokenPayload['token']->abilities ?? [],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return response()->json([
                'message' => 'Missing bearer token.',
            ], 401);
        }

        ApiToken::query()->where('token_hash', hash('sha256', $plainToken))->delete();

        return response()->json([
            'message' => 'Token revoked successfully.',
        ]);
    }
}
