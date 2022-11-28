<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Presenters\Api\Dto\HistogramDto;

class EuclideanDistanceComparator implements ColorComparatorInterface
{
    public function compareHistograms(HistogramDto $h1, HistogramDto $h2, int $colorRangeFrom, int $colorRangeTo): float
    {
        $red = 0;
        $green = 0;
        $blue = 0;
        for ($i = $colorRangeFrom; $i < $colorRangeTo + 1; $i++) {
            $red += pow($h1->red[$i] - $h2->red[$i], 2);
            $green += pow($h1->green[$i] - $h2->green[$i], 2);
            $blue += pow($h1->blue[$i] - $h2->blue[$i], 2);
        }
        $red = sqrt($red);
        $green = sqrt($green);
        $blue = sqrt($blue);

        return sqrt(($red * $red) + ($green * $green) + ($blue * $blue));
    }
}