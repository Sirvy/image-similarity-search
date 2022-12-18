<?php

declare(strict_types=1);

namespace App\Services;

use App\Presenters\Api\Dto\HistogramDto;
use App\Presenters\Api\Factory\HistogramDtoFactory;
use App\Services\ColorComparators\ColorComparatorInterface;
use App\Utils\ImageValueObject;
use App\Utils\ThreeColorObject;

class ComparatorService
{
    public function __construct(
        private readonly HistogramNormalizer $normalizer,
        private readonly ImageDbService      $imageDbService,
        private readonly HistogramDtoFactory $histogramDtoFactory,
    )
    {
    }

    public function compareHistogramsAndReturnSortedList(
        ColorComparatorInterface $comparator,
        HistogramDto             $histogram,
        int                      $colorRangeFrom,
        int                      $colorRangeTo,
        string                   $colorModel = 'RGB'
    ): array
    {
        $images = $this->imageDbService->getAllImages();

        $arr = [];
        foreach ($images as $image) {
            $imageHistogram = $this->histogramDtoFactory->createFromJson($image->histogram);
            $compareValue = $comparator->compareHistograms(
                $this->getColorModelFromHistogram(
                    $this->normalizer->normalizeHistogram($imageHistogram, $colorRangeFrom, $colorRangeTo),
                    $colorModel
                ),
                $this->getColorModelFromHistogram(
                    $this->normalizer->normalizeHistogram($histogram, $colorRangeFrom, $colorRangeTo),
                    $colorModel
                ),
                $colorRangeFrom,
                $colorRangeTo
            );
            $arr[] = new ImageValueObject($image->id, $image->filename, $imageHistogram, $compareValue);
        }

        usort($arr, function (ImageValueObject $a, ImageValueObject $b) {
            if ($a->value === $b->value) return 0;
            return $a->value < $b->value ? -1 : 1;
        });

        $result = [];
        $i = 1;
        foreach ($arr as $image) {
            $result[] = new ImageValueObject($i++, $image->filename, $image->histogram, $image->value);
        }

        return $result;
    }

    private function getColorModelFromHistogram(HistogramDto $histogram, string $colorModel): ThreeColorObject
    {
        return match ($colorModel) {
            'YUV' => new ThreeColorObject($histogram->y, $histogram->u, $histogram->v),
            default => new ThreeColorObject($histogram->red, $histogram->green, $histogram->blue),
        };
    }
}