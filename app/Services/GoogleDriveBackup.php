<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleDriveBackup
{
    public function configured(): bool
    {
        return filled(config('backup.google_drive.client_email'))
            && filled(config('backup.google_drive.private_key'));
    }

    public function upload(string $path, ?string $folderId = null): array
    {
        if (! $this->configured()) {
            throw new RuntimeException('Google Drive service-account credentials are not configured.');
        }

        $metadata = ['name' => basename($path)];
        $folderId = $folderId ?: config('backup.google_drive.folder_id');

        if ($folderId) {
            $metadata['parents'] = [$folderId];
        }

        $response = $this->client()
            ->attach('metadata', json_encode($metadata), null, ['Content-Type' => 'application/json; charset=UTF-8'])
            ->attach('file', fopen($path, 'r'), basename($path), ['Content-Type' => 'application/sql'])
            ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&fields=id,name,webViewLink');

        if ($response->failed()) {
            throw new RuntimeException('Google Drive upload failed: '.$response->body());
        }

        return $response->json();
    }

    private function client(): PendingRequest
    {
        $now = time();
        $header = $this->base64Url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claims = $this->base64Url(json_encode([
            'iss' => config('backup.google_drive.client_email'),
            'scope' => 'https://www.googleapis.com/auth/drive.file',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ]));

        $privateKey = str_replace('\\n', "\n", (string) config('backup.google_drive.private_key'));
        if (! openssl_sign("{$header}.{$claims}", $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('The Google Drive private key is invalid.');
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => "{$header}.{$claims}.".$this->base64Url($signature),
        ]);

        if ($tokenResponse->failed() || ! $tokenResponse->json('access_token')) {
            throw new RuntimeException('Google Drive authentication failed: '.$tokenResponse->body());
        }

        return Http::withToken($tokenResponse->json('access_token'))->timeout(300);
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
