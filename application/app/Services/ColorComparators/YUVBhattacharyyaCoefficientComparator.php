<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Presenters\Api\Dto\HistogramDto;

class YUVBhattacharyyaCoefficientComparator implements ColorComparatorInterface
{
    public function compareHistograms(HistogramDto $h1, HistogramDto $h2, int $colorRangeFrom, int $colorRangeTo): float
    {
        $ys = 0;
        $us = 0;
        $vs = 0;
        for ($i = $colorRangeFrom; $i < $colorRangeTo + 1; $i++) {
            $ys += sqrt($h1->y[$i] * $h2->y[$i]);
            $us += sqrt($h1->u[$i] * $h2->u[$i]);
            $vs += sqrt($h1->v[$i] * $h2->v[$i]);
        }

        return 1 - ($ys * $us * $vs);
    }
}