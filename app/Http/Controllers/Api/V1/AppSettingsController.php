<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\SettingManager;
use Illuminate\Http\JsonResponse;

class AppSettingsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $groupConfig = config('settings.groups.flutter_app.fields', []);
        $savedSettings = SettingManager::group('flutter_app');

        $settings = [];

        foreach ($groupConfig as $field) {
            $key = $field['key'] ?? null;

            if (! is_string($key) || $key === '') {
                continue;
            }

            $settings[$key] = $savedSettings[$key] ?? ($field['default'] ?? null);
        }

        return response()->json([
            'group' => 'flutter_app',
            'settings' => $settings,
        ]);
    }
}
