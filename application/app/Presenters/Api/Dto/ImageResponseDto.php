<?php

declare(strict_types=1);

namespace App\Presenters\Api\Dto;

class ImageResponseDto
{
    public int $id;

    public string $filename;

    public HistogramDto $histogram;

    /**
     * @param int $id
     * @param string $filename
     * @param HistogramDto $histogram
     */
    public function __construct(int $id, string $filename, HistogramDto $histogram)
    {
        $this->id = $id;
        $this->filename = $filename;
        $this->histogram = $histogram;
    }


}