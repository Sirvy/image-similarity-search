<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Presenters\Api\Dto\HistogramDto;

class BhattacharyyaCoefficientComparator implements ColorComparatorInterface
{
    public function compareHistograms(HistogramDto $h1, HistogramDto $h2, int $colorRangeFrom, int $colorRangeTo): float
    {
        $reds = 0;
        $greens = 0;
        $blues = 0;
        for ($i = $colorRangeFrom; $i < $colorRangeTo + 1; $i++) {
            $reds += sqrt($h1->red[$i] * $h2->red[$i]);
            $greens += sqrt($h1->green[$i] * $h2->green[$i]);
            $blues += sqrt($h1->blue[$i] * $h2->blue[$i]);
        }

        return 1 - ($reds * $greens * $blues);
    }
}