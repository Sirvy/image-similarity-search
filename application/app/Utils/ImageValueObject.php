<?php

declare(strict_types=1);

namespace App\Utils;

use App\Presenters\Api\Dto\HistogramDto;

class ImageValueObject
{
    public int $id;

    public string $filename;

    public HistogramDto $histogram;

    public float $value;

    /**
     * @param int $id
     * @param string $filename
     * @param HistogramDto $histogram
     * @param float $value
     */
    public function __construct(int $id, string $filename, HistogramDto $histogram, float $value)
    {
        $this->id = $id;
        $this->filename = $filename;
        $this->histogram = $histogram;
        $this->value = $value;
    }


}