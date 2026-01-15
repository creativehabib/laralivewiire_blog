<?php

namespace App\Observers;

use App\Services\WatermarkService;
use Habib\MediaManager\Models\MediaFile;

class MediaFileObserver
{
    public function saved(MediaFile $mediaFile): void
    {
        if (! $mediaFile->wasRecentlyCreated) {
            return;
        }

        app(WatermarkService::class)->applyToMediaFile($mediaFile);
    }
}
