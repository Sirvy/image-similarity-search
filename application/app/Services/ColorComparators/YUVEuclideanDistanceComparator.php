<?php

namespace App\Services\ColorComparators;

use App\Presenters\Api\Dto\HistogramDto;

class YUVEuclideanDistanceComparator implements ColorComparatorInterface
{
    public function compareHistograms(HistogramDto $h1, HistogramDto $h2, int $colorRangeFrom, int $colorRangeTo): float
    {
        $ys = 0;
        $us = 0;
        $vs = 0;
        for ($i = $colorRangeFrom; $i < $colorRangeTo + 1; $i++) {
            $ys += pow($h1->y[$i] - $h2->y[$i], 2);
            $us += pow($h1->u[$i] - $h2->u[$i], 2);
            $vs += pow($h1->v[$i] - $h2->v[$i], 2);
        }
        $ys = sqrt($ys);
        $us = sqrt($us);
        $vs = sqrt($vs);

        return sqrt(($ys * $ys) + ($us * $us) + ($vs * $vs));
    }
}