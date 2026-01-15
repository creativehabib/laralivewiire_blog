<?php

namespace App\Observers;

use App\Services\WatermarkService;
use Habib\MediaManager\Models\MediaFile;
use Illuminate\Support\Facades\DB;

class MediaFileObserver
{
    public function saved(MediaFile $mediaFile): void
    {
        if (! $mediaFile->wasRecentlyCreated) {
            return;
        }

        DB::afterCommit(function () use ($mediaFile) {
            app(WatermarkService::class)->applyToMediaFile($mediaFile);
        });
    }
}
