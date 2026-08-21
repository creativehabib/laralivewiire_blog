<?php

namespace App\Services;

use JsonException;
use RuntimeException;

class GoogleServiceAccountCredentials
{
    public function parse(string $contents): array
    {
        $contents = preg_replace('/^\xEF\xBB\xBF/', '', $contents) ?? '';

        try {
            $credentials = json_decode(trim($contents), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('ফাইলটি valid JSON নয়: '.$exception->getMessage());
        }

        if (! is_array($credentials)) {
            throw new RuntimeException('JSON file-এর root অবশ্যই একটি object হতে হবে।');
        }

        if (isset($credentials['installed']) || isset($credentials['web'])) {
            throw new RuntimeException('এটি OAuth Client JSON, Service Account key নয়। Google Cloud → IAM & Admin → Service Accounts → Keys → Add key → Create new key → JSON থেকে সঠিক file download করুন।');
        }

        if (($credentials['type'] ?? null) !== 'service_account') {
            throw new RuntimeException('JSON-এর type "service_account" নয়। OAuth client secret নয়, Service Account-এর JSON key upload করুন।');
        }

        if (blank($credentials['client_email'] ?? null)) {
            throw new RuntimeException('JSON file-এ client_email পাওয়া যায়নি। নতুন Service Account JSON key download করুন।');
        }

        if (blank($credentials['private_key'] ?? null)) {
            throw new RuntimeException('JSON file-এ private_key পাওয়া যায়নি। নতুন Service Account JSON key download করুন।');
        }

        if (! filter_var($credentials['client_email'], FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('JSON file-এর client_email সঠিক নয়।');
        }

        if (openssl_pkey_get_private($credentials['private_key']) === false) {
            throw new RuntimeException('JSON file-এর private_key সঠিক PEM key নয়। Google Cloud থেকে নতুন key তৈরি করে download করুন।');
        }

        return [
            'client_email' => trim($credentials['client_email']),
            'private_key' => trim($credentials['private_key']),
        ];
    }
}
