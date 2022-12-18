<?php

declare(strict_types=1);

namespace App\Services;

use App\Utils\Histogram;
use Nette\Utils\Image;

class ImageHistogramService
{
    public function createHistogramFromImage(Image $image): Histogram
    {
        $histogram = new Histogram();
        for ($x = 0; $x < $image->getWidth(); $x++) {
            for ($y = 0; $y < $image->getHeight(); $y++) {
                $rgb = imagecolorat($image->getImageResource(), $x, $y);

                $red = ($rgb >> 16) & 0xFF;
                $green = ($rgb >> 8) & 0xFF;
                $blue = $rgb & 0xFF;

                $histogram->increaseRed($red);
                $histogram->increaseGreen($green);
                $histogram->increaseBlue($blue);

                [$yuv_y, $yuv_u, $yuv_v] = $this->rgbToYuv($red, $green, $blue);
                $histogram->increaseY($yuv_y);
                $histogram->increaseU($yuv_u);
                $histogram->increaseV($yuv_v);
            }
        }

        return $histogram;
    }

    // Full swing for YCbCr BT.601 (https://en.wikipedia.org/wiki/YUV#Full_swing_for_YCbCr_BT.601)
    private function rgbToYuv(int $red, int $green, int $blue): array
    {
        $yuvMatrix = [
            [77, 150, 29],
            [-43, -84, 127],
            [127, -106, -21],
        ];

        $yuv = [];
        for ($i = 0; $i < 3; $i++) {
            $yuv[$i] = $yuvMatrix[$i][0] * $red + $yuvMatrix[$i][1] * $green + $yuvMatrix[$i][2] * $blue;
            $yuv[$i] = ($yuv[$i] + 128) >> 8;
        }

        $yuv[1] += 128;
        $yuv[2] += 128;

        return $yuv;
    }
}