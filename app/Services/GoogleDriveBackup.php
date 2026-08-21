<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class GoogleDriveBackup
{
    public function configured(): bool
    {
        return filled($this->clientEmail()) && filled($this->privateKey());
    }

    public function testConnection(?string $folderId = null): void
    {
        $folderId = $this->folderId($folderId);

        if ($folderId) {
            $this->assertFolderAccessible($folderId);

            return;
        }

        $response = $this->client()->get('https://www.googleapis.com/drive/v3/about', ['fields' => 'user']);

        if ($response->failed()) {
            throw new RuntimeException('Google Drive connection failed: '.$response->body());
        }
    }

    public function upload(string $path, ?string $folderId = null): array
    {
        if (! $this->configured()) {
            throw new RuntimeException('Google Drive service-account credentials are not configured.');
        }

        $metadata = ['name' => basename($path)];
        $folderId = $this->folderId($folderId);

        if ($folderId) {
            $this->assertFolderAccessible($folderId);
            $metadata['parents'] = [$folderId];
        }

        $response = $this->client()
            ->attach('metadata', json_encode($metadata), null, ['Content-Type' => 'application/json; charset=UTF-8'])
            ->attach('file', fopen($path, 'r'), basename($path), ['Content-Type' => 'application/sql'])
            ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&supportsAllDrives=true&fields=id,name,webViewLink');

        if ($response->failed()) {
            throw new RuntimeException('Google Drive upload failed: '.$response->body());
        }

        return $response->json();
    }

    private function client(): PendingRequest
    {
        // A small leeway prevents otherwise-valid assertions failing when the
        // application server and Google's clock differ by a few seconds.
        $now = time() - 30;
        $header = $this->base64Url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claims = $this->base64Url(json_encode([
            'iss' => $this->clientEmail(),
            // Full Drive scope is required for a service account to address a
            // folder shared by its ID. drive.file can make shared folders look
            // like they do not exist and results in a misleading 404 response.
            'scope' => 'https://www.googleapis.com/auth/drive',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3500,
        ]));

        $privateKey = str_replace('\\n', "\n", $this->privateKey());
        if (! openssl_sign("{$header}.{$claims}", $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('The Google Drive private key is invalid.');
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => "{$header}.{$claims}.".$this->base64Url($signature),
        ]);

        if ($tokenResponse->failed() || ! $tokenResponse->json('access_token')) {
            throw new RuntimeException($this->authenticationError($tokenResponse->json()));
        }

        return Http::withToken($tokenResponse->json('access_token'))->timeout(300);
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function clientEmail(): string
    {
        return trim((string) setting('backup_drive_client_email', config('backup.google_drive.client_email')));
    }

    private function privateKey(): string
    {
        $stored = setting('backup_drive_private_key');

        if (filled($stored)) {
            try {
                return Crypt::decryptString((string) $stored);
            } catch (Throwable) {
                throw new RuntimeException('The saved Google Drive private key could not be decrypted. Please save it again.');
            }
        }

        return (string) config('backup.google_drive.private_key');
    }

    private function authenticationError(?array $error): string
    {
        $code = $error['error'] ?? null;
        $description = (string) ($error['error_description'] ?? 'Unknown authentication error.');

        if ($code === 'invalid_grant' && str_contains(strtolower($description), 'account not found')) {
            return 'Google service account পাওয়া যায়নি। Google Cloud থেকে নতুন service-account JSON key download করে এই settings page-এ import করুন। JSON-এর client_email হাতে পরিবর্তন করবেন না এবং service account-টি deleted/disabled কি না পরীক্ষা করুন।';
        }

        if ($code === 'invalid_grant') {
            return 'Google credentials গ্রহণ করেনি। নতুন JSON key import করুন এবং server date/time সঠিক আছে কি না পরীক্ষা করুন। Google বলেছে: '.$description;
        }

        return 'Google Drive authentication failed: '.$description;
    }

    private function assertFolderAccessible(string $folderId): void
    {
        $response = $this->client()->get("https://www.googleapis.com/drive/v3/files/{$folderId}", [
            'fields' => 'id,name,mimeType,capabilities(canAddChildren)',
            'supportsAllDrives' => 'true',
        ]);

        if ($response->status() === 404) {
            throw new RuntimeException("Google Drive folder পাওয়া যায়নি বা Service Account-এর access নেই। Folder ID [{$folderId}] সঠিক কি না দেখুন এবং folder-টি {$this->clientEmail()}-কে Editor হিসেবে share করুন।");
        }

        if ($response->failed()) {
            throw new RuntimeException('Google Drive folder যাচাই করা যায়নি: '.($response->json('error.message') ?: $response->body()));
        }

        if ($response->json('mimeType') !== 'application/vnd.google-apps.folder') {
            throw new RuntimeException('দেওয়া Google Drive ID-টি কোনো folder-এর নয়।');
        }

        if ($response->json('capabilities.canAddChildren') === false) {
            throw new RuntimeException("Service Account-এর এই folder-এ file upload permission নেই। {$this->clientEmail()}-কে Editor access দিন।");
        }
    }

    private function folderId(?string $folderId): ?string
    {
        $value = trim((string) ($folderId ?: config('backup.google_drive.folder_id')));

        if ($value === '') {
            return null;
        }

        if (preg_match('~/folders/([^/?#]+)~', $value, $matches)) {
            return $matches[1];
        }

        return trim($value, "/ \t\n\r\0\x0B");
    }
}
