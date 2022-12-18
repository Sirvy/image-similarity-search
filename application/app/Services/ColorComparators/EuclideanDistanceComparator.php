<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Utils\ThreeColorObject;

class EuclideanDistanceComparator implements ColorComparatorInterface
{
    public function compareHistograms(ThreeColorObject $h1, ThreeColorObject $h2, int $colorRangeFrom, int $colorRangeTo): float
    {
        $red = 0;
        $green = 0;
        $blue = 0;
        for ($i = $colorRangeFrom; $i < $colorRangeTo + 1; $i++) {
            $red += pow($h1->c1[$i] - $h2->c1[$i], 2);
            $green += pow($h1->c2[$i] - $h2->c2[$i], 2);
            $blue += pow($h1->c3[$i] - $h2->c3[$i], 2);
        }
        $red = sqrt($red);
        $green = sqrt($green);
        $blue = sqrt($blue);

        return sqrt(($red * $red) + ($green * $green) + ($blue * $blue));
    }
}