<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Utils\ThreeColorObject;

class BhattacharyyaCoefficientComparator implements ColorComparatorInterface
{
    public function compareHistograms(ThreeColorObject $h1, ThreeColorObject $h2, int $colorRangeFrom, int $colorRangeTo): float
    {
        $reds = 0;
        $greens = 0;
        $blues = 0;
        for ($i = $colorRangeFrom; $i < $colorRangeTo + 1; $i++) {
            $reds += sqrt($h1->c1[$i] * $h2->c1[$i]);
            $greens += sqrt($h1->c2[$i] * $h2->c2[$i]);
            $blues += sqrt($h1->c3[$i] * $h2->c3[$i]);
        }

        return 1 - ($reds * $greens * $blues);
    }
}