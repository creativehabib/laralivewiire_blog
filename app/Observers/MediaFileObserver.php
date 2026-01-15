<?php

namespace App\Observers;

use App\Services\WatermarkService;
use Habib\MediaManager\Models\MediaFile;

class MediaFileObserver
{
    public function created(MediaFile $mediaFile): void
    {
        app(WatermarkService::class)->applyToMediaFile($mediaFile);
    }
}
