<?php

return [
    'directory' => storage_path('app/backups/database'),
    'google_drive' => [
        'client_email' => env('GOOGLE_DRIVE_CLIENT_EMAIL'),
        'private_key' => env('GOOGLE_DRIVE_PRIVATE_KEY'),
        'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
    ],
];
