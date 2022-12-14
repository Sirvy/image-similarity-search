<?php

declare(strict_types=1);

namespace App\Presenters\Api;

use App\Presenters\Api\Factory\HistogramDtoFactory;
use App\Services\ColorComparators\BhattacharyyaCoefficientComparator;
use App\Services\ColorComparators\CosineDistanceComparator;
use App\Services\ColorComparators\EuclideanDistanceComparator;
use App\Services\ComparatorService;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\AbortException;

class ApplySearchPresenter extends AbstractApiPresenter
{
    public function __construct(
        private readonly HistogramDtoFactory $histogramDtoFactory,
        private readonly ComparatorService   $comparatorService
    )
    {
        parent::__construct();
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    #[NoReturn] protected function handlePost()
    {
        $sampleHistogram = $this->getRequest()->getPost('sampleHistogram');
        $comparatorKey = $this->getRequest()->getPost('comparator');
        $colorModelKey = $this->getRequest()->getPost('colorModel');
        $colorRangeFrom = (int)$this->getRequest()->getPost('colorRangeFrom');
        $colorRangeTo = (int)$this->getRequest()->getPost('colorRangeTo');

        if (null === $sampleHistogram) {
            throw new Exception('No sample histogram data provided.');
        }

        if ($colorRangeFrom < 0 || $colorRangeFrom >= $colorRangeTo || $colorRangeTo > 255) {
            throw new Exception('Invalid color ranges.');
        }

        $histogram = $this->histogramDtoFactory->createFromJson($sampleHistogram);

        $comparator = match ($comparatorKey) {
            default => new EuclideanDistanceComparator(),
            'bc' => new BhattacharyyaCoefficientComparator(),
            'cos' => new CosineDistanceComparator(),
        };

        $result = $this->comparatorService->compareHistogramsAndReturnSortedList($comparator, $histogram, $colorRangeFrom, $colorRangeTo, $colorModelKey);

        $this->sendJson($result);
    }
}