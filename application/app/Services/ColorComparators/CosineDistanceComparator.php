<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Utils\ThreeColorObject;

class CosineDistanceComparator implements ColorComparatorInterface
{
    public function compareHistograms(ThreeColorObject $h1, ThreeColorObject $h2, int $colorRangeFrom, int $colorRangeTo): float
    {
        $red_ab = 0;
        $red_a = 0;
        $red_b = 0;
        $green_ab = 0;
        $green_a = 0;
        $green_b = 0;
        $blue_ab = 0;
        $blue_a = 0;
        $blue_b = 0;
        for ($i = $colorRangeFrom; $i < $colorRangeTo + 1; $i++) {
            $red_ab += $h1->c1[$i] * $h2->c1[$i];
            $red_a += $h1->c1[$i] * $h1->c1[$i];
            $red_b += $h2->c1[$i] * $h2->c1[$i];

            $green_ab += $h1->c2[$i] * $h2->c2[$i];
            $green_a += $h1->c2[$i] * $h1->c2[$i];
            $green_b += $h2->c2[$i] * $h2->c2[$i];

            $blue_ab += $h1->c3[$i] * $h2->c3[$i];
            $blue_a += $h1->c3[$i] * $h1->c3[$i];
            $blue_b += $h2->c3[$i] * $h2->c3[$i];
        }
        $red_a = sqrt($red_a);
        $red_b = sqrt($red_b);
        $green_a = sqrt($green_a);
        $green_b = sqrt($green_b);
        $blue_a = sqrt($blue_a);
        $blue_b = sqrt($blue_b);

        $denom_red = $red_a * $red_b;
        $denom_green = $green_a * $green_b;
        $denom_blue = $blue_a * $blue_b;

        $red = $red_ab / $denom_red;
        $green = $green_ab / $denom_green;
        $blue = $blue_ab / $denom_blue;

        return 1 - ($red * $green * $blue);
    }
}