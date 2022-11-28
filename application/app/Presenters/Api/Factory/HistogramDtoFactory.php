<?php

declare(strict_types=1);

namespace App\Presenters\Api\Factory;

use App\Presenters\Api\Dto\HistogramDto;
use App\Utils\Histogram;
use Nette\Utils\Json;

class HistogramDtoFactory
{
    public function createFromJson(string $histogramJson): HistogramDto
    {
        $json = Json::decode($histogramJson, Json::FORCE_ARRAY);
        return new HistogramDto(
            $json['red'],
            $json['green'],
            $json['blue'],
            $json['y'],
            $json['u'],
            $json['v']
        );
    }

    public function createFromHistogram(Histogram $histogram): HistogramDto
    {
        return new HistogramDto(
            $histogram->getRed(),
            $histogram->getGreen(),
            $histogram->getBlue(),
            $histogram->getY(),
            $histogram->getU(),
            $histogram->getV()
        );
    }
}