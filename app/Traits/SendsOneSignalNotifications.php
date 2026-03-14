<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait SendsOneSignalNotifications
{
    protected function sendPushNotification(object $post, string $event = 'updated'): ?array
    {
        $isEnabled = filter_var(setting('onesignal_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $appId = trim((string) setting('onesignal_app_id', ''));
        $restApiKey = trim((string) setting('onesignal_rest_api_key', ''));

        if (! $isEnabled || $appId === '' || $restApiKey === '') {
            return null;
        }

        $postName = trim((string) ($post->name ?? 'Untitled'));
        $includedSegment = trim((string) setting('onesignal_included_segment', 'All')) ?: 'All';

        $payload = [
            'app_id' => $appId,
            'included_segments' => [$includedSegment],
            'headings' => [
                'en' => 'New update',
            ],
            'contents' => [
                'en' => $postName,
            ],
            'data' => [
                'post_id' => (int) ($post->id ?? 0),
                'event' => $event,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic '.$restApiKey,
            'Content-Type' => 'application/json; charset=utf-8',
        ])->post('https://onesignal.com/api/v1/notifications', $payload);

        if ($response->failed()) {
            Log::warning('Failed to send OneSignal notification.', [
                'status' => $response->status(),
                'response' => $response->body(),
                'post_id' => $post->id ?? null,
                'event' => $event,
            ]);
        }

        return $response->json();
    }
}
