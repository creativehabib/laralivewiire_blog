<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class WatermarkService
{
    public function applyToMediaFile(object $mediaFile): void
    {
        if (! setting('watermark_enabled', false)) {
            return;
        }

        $watermarkPath = setting('watermark_image');
        if (! $watermarkPath) {
            return;
        }

        $disk = data_get($mediaFile, 'disk') ?: setting('storage_disk', config('filesystems.default'));
        $relativePath = $this->firstNonEmpty([
            data_get($mediaFile, 'path'),
            data_get($mediaFile, 'file_path'),
            data_get($mediaFile, 'file'),
            data_get($mediaFile, 'location'),
        ]);

        if (! $relativePath) {
            return;
        }

        $diskToUse = $this->resolveDiskWithPath($disk, $relativePath);
        if (! $diskToUse) {
            return;
        }

        $mimeType = data_get($mediaFile, 'mime_type')
            ?: Storage::disk($diskToUse)->mimeType($relativePath);

        if (! $mimeType || ! Str::startsWith($mimeType, 'image/')) {
            return;
        }

        $watermarkDisk = $this->resolveDiskWithPath($disk, $watermarkPath)
            ?? $this->resolveDiskWithPath('public', $watermarkPath);

        if (! $watermarkDisk) {
            return;
        }

        $targetPath = Storage::disk($diskToUse)->path($relativePath);
        $watermarkAbsolutePath = Storage::disk($watermarkDisk)->path($watermarkPath);

        if (! is_file($targetPath) || ! is_file($watermarkAbsolutePath)) {
            return;
        }

        $opacity = max(0, min(100, (int) setting('watermark_opacity', 70)));
        $sizePercent = max(1, min(100, (int) setting('watermark_size', 10)));
        $offsetX = max(0, (int) setting('watermark_offset_x', 10));
        $offsetY = max(0, (int) setting('watermark_offset_y', 10));
        $position = $this->mapPosition((string) setting('watermark_position', 'bottom_right'));

        try {
            $manager = ImageManager::gd();
            $image = $manager->read($targetPath);
            $watermark = $manager->read($watermarkAbsolutePath);

            $targetWidth = max(1, (int) round($image->width() * ($sizePercent / 100)));
            $watermarkRatio = $watermark->width() > 0
                ? ($watermark->height() / $watermark->width())
                : 1;
            $targetHeight = max(1, (int) round($targetWidth * $watermarkRatio));

            $watermark->resize($targetWidth, $targetHeight);

            if (method_exists($watermark, 'opacity')) {
                $watermark->opacity($opacity);
            }

            $image->place($watermark, $position, $offsetX, $offsetY);
            $image->save($targetPath);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    private function resolveDiskWithPath(?string $disk, string $path): ?string
    {
        $disk = $disk ?: config('filesystems.default');

        if (Storage::disk($disk)->exists($path)) {
            return $disk;
        }

        return null;
    }

    private function mapPosition(string $position): string
    {
        return match ($position) {
            'top_left' => 'top-left',
            'top_right' => 'top-right',
            'center' => 'center',
            'bottom_left' => 'bottom-left',
            default => 'bottom-right',
        };
    }

    private function firstNonEmpty(array $values): ?string
    {
        foreach ($values as $value) {
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
