<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Utils\ThreeColorObject;

interface ColorComparatorInterface
{
    public function compareHistograms(ThreeColorObject $h1, ThreeColorObject $h2, int $colorRangeFrom, int $colorRangeTo): float;
}