<?php

namespace Ella123\HyperfGenerateAvatar;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;

class AvatarUtils
{
    public static function generateAvatar(
        string $username,
        int    $size = 64,
        string $color = '#000000',
        string $fontPath = null,
        string $type = 'jpeg'
    ): EncodedImageInterface
    {
        !$fontPath && $fontPath = __DIR__ . '/simfang.ttf';
        // 创建画布
        $img = ImageManager::gd()->create(120, 120);
        // 将用户名转换为大写，并截取最多四个字符
        $username = mb_substr(strtoupper($username), 0, 4, 'UTF-8');
        $usernameLength = mb_strlen($username, 'UTF-8');
        // 绘制文本
        $lines = [$username];
        // 根据用户名长度动态调整字体大小
        $isChinese = preg_match('/[\x{4e00}-\x{9fa5}]/u', $username);
        $yOffset = 0;
        if ($isChinese) {
            $fontSize = match ($usernameLength) {
                1 => 100,
                2 => 60,
                3 => 36,
                4 => 50,
            };
            if ($usernameLength == 4) {
                // 如果是四个汉字，则分为两行显示
                $lines = [
                    mb_substr($username, 0, 2, 'UTF-8'),
                    mb_substr($username, 2, 2, 'UTF-8')
                ];
                $yOffset = 50;
            }
        } else {
            $fontSize = match ($usernameLength) {
                1 => 120,
                2 => 100,
                3 => 70,
                4 => 50,
            };
        }

        foreach ($lines as $index => $line) {
            $img->text($line, 60, $yOffset ? 36 + ($index * $yOffset) : 60,
                function ($font) use ($fontPath, $fontSize, $color) {
                    $font->file($fontPath);
                    $font->size($fontSize);
                    $font->color($color);
                    $font->align('center');
                    $font->valign('middle');
                });
        }

        return match ($type) {
            'webp' => $img->scale($size)->toWebp(),
            'gif' => $img->scale($size)->toGif(),
            'png' => $img->scale($size)->toPng(),
            default => $img->scale($size)->toJpeg(),
        };
    }
}