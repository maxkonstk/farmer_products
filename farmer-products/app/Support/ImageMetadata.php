<?php

namespace App\Support;

class ImageMetadata
{
    /**
     * @var array<string, array{width: int, height: int}>
     */
    private static array $cache = [];

    /**
     * @return array{width: int, height: int}
     */
    public static function attributes(string $url, int $fallbackWidth = 900, int $fallbackHeight = 650): array
    {
        $cacheKey = "{$url}|{$fallbackWidth}x{$fallbackHeight}";

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $path = self::resolvePublicPath($url);
        $dimensions = $path ? self::readDimensions($path) : null;

        return self::$cache[$cacheKey] = $dimensions ?? [
            'width' => $fallbackWidth,
            'height' => $fallbackHeight,
        ];
    }

    private static function resolvePublicPath(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: $url;

        if (! is_string($path) || $path === '') {
            return null;
        }

        $publicPath = public_path(ltrim($path, '/'));

        return is_file($publicPath) ? $publicPath : null;
    }

    /**
     * @return array{width: int, height: int}|null
     */
    private static function readDimensions(string $path): ?array
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'svg') {
            return self::readSvgDimensions($path);
        }

        $imageSize = @getimagesize($path);

        if (! is_array($imageSize) || empty($imageSize[0]) || empty($imageSize[1])) {
            return null;
        }

        return [
            'width' => (int) $imageSize[0],
            'height' => (int) $imageSize[1],
        ];
    }

    /**
     * @return array{width: int, height: int}|null
     */
    private static function readSvgDimensions(string $path): ?array
    {
        $svg = @file_get_contents($path);

        if ($svg === false) {
            return null;
        }

        if (
            preg_match('/\bwidth="([\d.]+)"/i', $svg, $widthMatch) === 1
            && preg_match('/\bheight="([\d.]+)"/i', $svg, $heightMatch) === 1
        ) {
            return [
                'width' => max(1, (int) round((float) $widthMatch[1])),
                'height' => max(1, (int) round((float) $heightMatch[1])),
            ];
        }

        if (preg_match('/\bviewBox="[\d.\-\s]+ ([\d.]+) ([\d.]+)"/i', $svg, $viewBoxMatch) === 1) {
            return [
                'width' => max(1, (int) round((float) $viewBoxMatch[1])),
                'height' => max(1, (int) round((float) $viewBoxMatch[2])),
            ];
        }

        return null;
    }
}
