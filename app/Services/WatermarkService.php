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

        $disk = data_get($mediaFile, 'disk') ?: setting('storage_disk', config('filesystems.default'));
        $relativePath = $this->firstNonEmpty([
            data_get($mediaFile, 'path'),
            data_get($mediaFile, 'file_path'),
            data_get($mediaFile, 'file'),
            data_get($mediaFile, 'location'),
            data_get($mediaFile, 'url'),
            data_get($mediaFile, 'file_name'),
        ]);

        if (! $relativePath) {
            return;
        }

        $relativePath = $this->normalizeRelativePath($relativePath, $disk);
        $diskToUse = $this->resolveDiskWithPath($disk, $relativePath);
        if (! $diskToUse) {
            return;
        }

        $mimeType = data_get($mediaFile, 'mime_type')
            ?: Storage::disk($diskToUse)->mimeType($relativePath);

        if (! $mimeType || ! Str::startsWith($mimeType, 'image/')) {
            return;
        }

        $watermarkType = (string) setting('watermark_type', 'image');
        if ($watermarkType === 'text') {
            $this->applyTextWatermark($diskToUse, $relativePath);
            return;
        }

        $watermarkPath = setting('watermark_image');
        if (! $watermarkPath) {
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

        $this->applyImageWatermark($targetPath, $watermarkAbsolutePath, $position, $offsetX, $offsetY, $sizePercent, $opacity);
    }

    private function applyImageWatermark(
        string $targetPath,
        string $watermarkAbsolutePath,
        string $position,
        int $offsetX,
        int $offsetY,
        int $sizePercent,
        int $opacity
    ): void {
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

    private function applyTextWatermark(string $disk, string $relativePath): void
    {
        $text = trim((string) setting('watermark_text', ''));
        if ($text === '') {
            return;
        }

        $fontPath = $this->resolveFontPath();
        if (! $fontPath) {
            return;
        }

        $targetPath = Storage::disk($disk)->path($relativePath);
        if (! is_file($targetPath)) {
            return;
        }

        $size = max(8, min(200, (int) setting('watermark_text_size', 24)));
        $opacity = max(0, min(100, (int) setting('watermark_opacity', 70)));
        $color = $this->colorWithOpacity((string) setting('watermark_text_color', '#ffffff'), $opacity);

        $offsetX = max(0, (int) setting('watermark_offset_x', 10));
        $offsetY = max(0, (int) setting('watermark_offset_y', 10));
        $position = $this->mapPosition((string) setting('watermark_position', 'bottom_right'));

        try {
            $manager = ImageManager::gd();
            $image = $manager->read($targetPath);

            [$textWidth, $textHeight] = $this->measureText($text, $fontPath, $size);
            [$x, $y] = $this->resolveTextCoordinates(
                $position,
                $image->width(),
                $image->height(),
                $textWidth,
                $textHeight,
                $offsetX,
                $offsetY
            );

            $image->text($text, $x, $y, function ($font) use ($fontPath, $size, $color) {
                $font->file($fontPath);
                $font->size($size);
                $font->color($color);
                $font->align('left');
                $font->valign('top');
            });

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

        foreach (['public', 'local'] as $fallback) {
            if ($fallback !== $disk && Storage::disk($fallback)->exists($path)) {
                return $fallback;
            }
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

    private function normalizeRelativePath(string $path, ?string $disk): string
    {
        $path = trim($path);

        if (Str::startsWith($path, ['http://', 'https://'])) {
            $urlPath = parse_url($path, PHP_URL_PATH);
            if (is_string($urlPath)) {
                $path = $urlPath;
            }
        }

        if ($disk) {
            $diskUrl = Storage::disk($disk)->url('');
            $diskPath = parse_url($diskUrl, PHP_URL_PATH);
            if (is_string($diskPath) && $diskPath !== '/' && Str::startsWith($path, $diskPath)) {
                $path = substr($path, strlen($diskPath));
            }
        }

        if (Str::startsWith($path, '/storage/')) {
            $path = substr($path, strlen('/storage/'));
        }

        return ltrim($path, '/');
    }

    private function resolveFontPath(): ?string
    {
        $fontPath = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
        if (is_file($fontPath)) {
            return $fontPath;
        }

        return null;
    }

    private function colorWithOpacity(string $hex, int $opacity): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = "{$hex[0]}{$hex[0]}{$hex[1]}{$hex[1]}{$hex[2]}{$hex[2]}";
        }

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return "rgba(255,255,255," . number_format($opacity / 100, 2, '.', '') . ")";
        }

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));
        $alpha = number_format($opacity / 100, 2, '.', '');

        return "rgba({$red},{$green},{$blue},{$alpha})";
    }

    private function measureText(string $text, string $fontPath, int $size): array
    {
        $box = imagettfbbox($size, 0, $fontPath, $text);
        $width = abs($box[4] - $box[0]);
        $height = abs($box[5] - $box[1]);

        return [$width, $height];
    }

    private function resolveTextCoordinates(
        string $position,
        int $imageWidth,
        int $imageHeight,
        int $textWidth,
        int $textHeight,
        int $offsetX,
        int $offsetY
    ): array {
        $positionKey = str_replace('-', '_', $position);

        return match ($positionKey) {
            'top_left' => [$offsetX, $offsetY],
            'top_right' => [$imageWidth - $textWidth - $offsetX, $offsetY],
            'center' => [
                (int) round(($imageWidth - $textWidth) / 2),
                (int) round(($imageHeight - $textHeight) / 2),
            ],
            'bottom_left' => [$offsetX, $imageHeight - $textHeight - $offsetY],
            default => [$imageWidth - $textWidth - $offsetX, $imageHeight - $textHeight - $offsetY],
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
