<?php

use App\Traits\SendsOneSignalNotifications;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('sends onesignal notification when onesignal settings are enabled', function () {
    set_setting('onesignal_enabled', true, 'onesignal');
    set_setting('onesignal_app_id', 'app-123', 'onesignal');
    set_setting('onesignal_rest_api_key', 'rest-key-123', 'onesignal');
    set_setting('onesignal_included_segment', 'All', 'onesignal');

    Http::fake([
        'https://onesignal.com/api/v1/notifications' => Http::response([
            'id' => 'notification-id',
            'recipients' => 5,
        ], 200),
    ]);

    $sender = new class
    {
        use SendsOneSignalNotifications;

        public function dispatchNotification(object $post, string $event): ?array
        {
            return $this->sendPushNotification($post, $event);
        }
    };

    $response = $sender->dispatchNotification((object) [
        'id' => 10,
        'name' => 'Bangla Job Circular',
    ], 'created');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://onesignal.com/api/v1/notifications'
            && $request->hasHeader('Authorization', 'Basic rest-key-123')
            && $request['app_id'] === 'app-123'
            && $request['included_segments'] === ['All']
            && $request['contents']['en'] === 'নতুন চাকরির খবর: Bangla Job Circular'
            && $request['data']['post_id'] === 10
            && $request['data']['event'] === 'created';
    });

    expect($response)
        ->not->toBeNull()
        ->and($response['id'])->toBe('notification-id');
});

it('does not send onesignal notification when integration is disabled', function () {
    set_setting('onesignal_enabled', false, 'onesignal');

    Http::fake();

    $sender = new class
    {
        use SendsOneSignalNotifications;

        public function dispatchNotification(object $post, string $event): ?array
        {
            return $this->sendPushNotification($post, $event);
        }
    };

    $response = $sender->dispatchNotification((object) [
        'id' => 11,
        'name' => 'No Request',
    ], 'updated');

    Http::assertNothingSent();
    expect($response)->toBeNull();
});
