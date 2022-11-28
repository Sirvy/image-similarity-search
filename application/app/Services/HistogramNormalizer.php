<?php

declare(strict_types=1);

namespace App\Services;

use App\Presenters\Api\Dto\HistogramDto;

class HistogramNormalizer
{
    public function normalizeHistogram(HistogramDto $h, int $rangeFrom = 0, int $rangeTo = 255): HistogramDto
    {
        $maxRed = 1;
        $maxGreen = 1;
        $maxBlue = 1;
        $maxY = 1;
        $maxU = 1;
        $maxV = 1;

        for ($i = $rangeFrom; $i < $rangeTo + 1; $i++) {
            $maxRed += $h->red[$i];
            $maxGreen += $h->green[$i];
            $maxBlue += $h->blue[$i];
            $maxY += $h->y[$i];
            $maxU += $h->u[$i];
            $maxV += $h->v[$i];
        }

        $result = new HistogramDto([], [], [], [], [], []);

        for ($i = $rangeFrom; $i < $rangeTo + 1; $i++) {
            $result->red[$i] = $h->red[$i] * 1.0 / $maxRed;
            $result->green[$i] = $h->green[$i] * 1.0 / $maxGreen;
            $result->blue[$i] = $h->blue[$i] * 1.0 / $maxBlue;
            $result->y[$i] = $h->y[$i] * 1.0 / $maxY;
            $result->u[$i] = $h->u[$i] * 1.0 / $maxU;
            $result->v[$i] = $h->v[$i] * 1.0 / $maxV;
        }

        return $result;
    }
}