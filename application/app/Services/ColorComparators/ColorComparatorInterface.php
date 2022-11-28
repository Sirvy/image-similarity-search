<?php

declare(strict_types=1);

namespace App\Services\ColorComparators;

use App\Presenters\Api\Dto\HistogramDto;

interface ColorComparatorInterface
{
    public function compareHistograms(HistogramDto $h1, HistogramDto $h2, int $colorRangeFrom, int $colorRangeTo): float;
}